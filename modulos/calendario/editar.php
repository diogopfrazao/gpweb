<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $Aplic, $config, $cal_sdf;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$sql = new BDConsulta;

$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);

$evento_id=getParam($_REQUEST, 'evento_id',null);
$eh_conflito = isset($_SESSION['evento_eh_conflito']) ? $_SESSION['evento_eh_conflito'] : false;

$evento_projeto=getParam($_REQUEST, 'evento_projeto', null);
$evento_tarefa=getParam($_REQUEST, 'evento_tarefa', null);
$evento_perspectiva=getParam($_REQUEST, 'evento_perspectiva', null);
$evento_tema=getParam($_REQUEST, 'evento_tema', null);
$evento_objetivo=getParam($_REQUEST, 'evento_objetivo', null);
$evento_fator=getParam($_REQUEST, 'evento_fator', null);
$evento_estrategia=getParam($_REQUEST, 'evento_estrategia', null);
$evento_meta=getParam($_REQUEST, 'evento_meta', null);
$evento_pratica=getParam($_REQUEST, 'evento_pratica', null);
$evento_acao=getParam($_REQUEST, 'evento_acao', null);
$evento_canvas=getParam($_REQUEST, 'evento_canvas', null);
$evento_risco=getParam($_REQUEST, 'evento_risco', null);
$evento_risco_resposta=getParam($_REQUEST, 'evento_risco_resposta', null);
$evento_indicador=getParam($_REQUEST, 'evento_indicador', null);
$evento_calendario=getParam($_REQUEST, 'evento_calendario', null);
$evento_monitoramento=getParam($_REQUEST, 'evento_monitoramento', null);
$evento_ata=getParam($_REQUEST, 'evento_ata', null);
$evento_mswot=getParam($_REQUEST, 'evento_mswot', null);
$evento_swot=getParam($_REQUEST, 'evento_swot', null);
$evento_operativo=getParam($_REQUEST, 'evento_operativo', null);
$evento_instrumento=getParam($_REQUEST, 'evento_instrumento', null);
$evento_recurso=getParam($_REQUEST, 'evento_recurso', null);
$evento_problema=getParam($_REQUEST, 'evento_problema', null);
$evento_demanda=getParam($_REQUEST, 'evento_demanda', null);
$evento_programa=getParam($_REQUEST, 'evento_programa', null);
$evento_licao=getParam($_REQUEST, 'evento_licao', null);
$evento_evento=getParam($_REQUEST, 'evento_evento', null);
$evento_link=getParam($_REQUEST, 'evento_link', null);
$evento_avaliacao=getParam($_REQUEST, 'evento_avaliacao', null);
$evento_tgn=getParam($_REQUEST, 'evento_tgn', null);
$evento_brainstorm=getParam($_REQUEST, 'evento_brainstorm', null);
$evento_gut=getParam($_REQUEST, 'evento_gut', null);
$evento_causa_efeito=getParam($_REQUEST, 'evento_causa_efeito', null);
$evento_arquivo=getParam($_REQUEST, 'evento_arquivo', null);
$evento_forum=getParam($_REQUEST, 'evento_forum', null);
$evento_checklist=getParam($_REQUEST, 'evento_checklist', null);
$evento_agenda=getParam($_REQUEST, 'evento_agenda', null);
$evento_agrupamento=getParam($_REQUEST, 'evento_agrupamento', null);
$evento_patrocinador=getParam($_REQUEST, 'evento_patrocinador', null);
$evento_template=getParam($_REQUEST, 'evento_template', null);
$evento_painel=getParam($_REQUEST, 'evento_painel', null);
$evento_painel_odometro=getParam($_REQUEST, 'evento_painel_odometro', null);
$evento_painel_composicao=getParam($_REQUEST, 'evento_painel_composicao', null);
$evento_tr=getParam($_REQUEST, 'evento_tr', null);
$evento_me=getParam($_REQUEST, 'evento_me', null);
$evento_acao_item=getParam($_REQUEST, 'evento_acao_item', null);
$evento_beneficio=getParam($_REQUEST, 'evento_beneficio', null);
$evento_painel_slideshow=getParam($_REQUEST, 'evento_painel_slideshow', null);
$evento_projeto_viabilidade=getParam($_REQUEST, 'evento_projeto_viabilidade', null);
$evento_projeto_abertura=getParam($_REQUEST, 'evento_projeto_abertura', null);
$evento_plano_gestao=getParam($_REQUEST, 'evento_plano_gestao', null);
$evento_ssti=getParam($_REQUEST, 'evento_ssti', null);
$evento_laudo=getParam($_REQUEST, 'evento_laudo', null);
$evento_trelo=getParam($_REQUEST, 'evento_trelo', null);
$evento_trelo_cartao=getParam($_REQUEST, 'evento_trelo_cartao', null);
$evento_pdcl=getParam($_REQUEST, 'evento_pdcl', null);
$evento_pdcl_item=getParam($_REQUEST, 'evento_pdcl_item', null);
$evento_os=getParam($_REQUEST, 'evento_os', null);

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();




$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');

$direcao=getParam($_REQUEST, 'cmd', '');
$evento_arquivo_id=getParam($_REQUEST, 'evento_arquivo_id', '0');
$ordem=getParam($_REQUEST, 'ordem', '0');
$salvaranexo=getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo=getParam($_REQUEST, 'excluiranexo', 0);


if (!$podeAdicionar && !$evento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$podeEditar && $evento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

$data=getParam($_REQUEST, 'data', null);
$obj = new CEvento();

$vazio=array();

//vindo de conflito
$objeto=getParam($_REQUEST, 'objeto', null);
if ($objeto) {
	$_REQUEST=unserialize(base64_decode($objeto));
	$obj->join($_REQUEST);
	if ($obj->evento_inicio) {
		$data_inicio = new CData($obj->evento_inicio.getParam($_REQUEST, 'inicio_hora', null));
		$obj->evento_inicio = $data_inicio->format('%Y-%m-%d %H:%M:%S');
		}
	if ($obj->evento_fim) {
		$data_fim = new CData($obj->evento_fim.getParam($_REQUEST, 'fim_hora', null));
		$obj->evento_fim = $data_fim->format('%Y-%m-%d %H:%M:%S');
		}
	if ($obj->evento_id) $evento_id=$obj->evento_id;
	if ($obj->evento_cia) $cia_id=$obj->evento_cia;
	else $cia_id=$Aplic->usuario_cia;
	}
else if ($evento_id){
	$obj->load($evento_id);
	$cia_id=$obj->evento_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$evento_projeto || 
		$evento_tarefa || 
		$evento_perspectiva || 
		$evento_tema || 
		$evento_objetivo || 
		$evento_fator || 
		$evento_estrategia || 
		$evento_meta || 
		$evento_pratica || 
		$evento_acao || 
		$evento_canvas || 
		$evento_risco || 
		$evento_risco_resposta || 
		$evento_indicador || 
		$evento_calendario || 
		$evento_monitoramento || 
		$evento_ata || 
		$evento_mswot || 
		$evento_swot || 
		$evento_operativo || 
		$evento_instrumento || 
		$evento_recurso || 
		$evento_problema || 
		$evento_demanda || 
		$evento_programa || 
		$evento_licao || 
		$evento_evento || 
		$evento_link || 
		$evento_avaliacao || 
		$evento_tgn || 
		$evento_brainstorm || 
		$evento_gut || 
		$evento_causa_efeito || 
		$evento_arquivo || 
		$evento_forum || 
		$evento_checklist || 
		$evento_agenda || 
		$evento_agrupamento || 
		$evento_patrocinador || 
		$evento_template || 
		$evento_painel || 
		$evento_painel_odometro || 
		$evento_painel_composicao || 
		$evento_tr || 
		$evento_me || 
		$evento_acao_item || 
		$evento_beneficio || 
		$evento_painel_slideshow || 
		$evento_projeto_viabilidade || 
		$evento_projeto_abertura || 
		$evento_plano_gestao|| 
		$evento_ssti || 
		$evento_laudo || 
		$evento_trelo || 
		$evento_trelo_cartao || 
		$evento_pdcl || 
		$evento_pdcl_item ||
		$evento_os
		){
		$sql->adTabela('cias');
		if ($evento_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($evento_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($evento_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($evento_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($evento_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($evento_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($evento_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($evento_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($evento_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($evento_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($evento_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($evento_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($evento_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($evento_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($evento_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($evento_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($evento_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($evento_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($evento_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($evento_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($evento_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($evento_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($evento_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($evento_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($evento_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($evento_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($evento_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($evento_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($evento_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($evento_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($evento_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($evento_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($evento_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($evento_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($evento_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($evento_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($evento_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($evento_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($evento_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($evento_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($evento_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($evento_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($evento_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($evento_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($evento_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($evento_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($evento_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($evento_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($evento_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($evento_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($evento_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($evento_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($evento_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($evento_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($evento_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($evento_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($evento_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
		elseif ($evento_os) $sql->esqUnir('os','os','os_cia=cias.cia_id');
	
		if ($evento_tarefa) $sql->adOnde('tarefa_id = '.(int)$evento_tarefa);
		elseif ($evento_projeto) $sql->adOnde('projeto_id = '.(int)$evento_projeto);
		elseif ($evento_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$evento_perspectiva);
		elseif ($evento_tema) $sql->adOnde('tema_id = '.(int)$evento_tema);
		elseif ($evento_objetivo) $sql->adOnde('objetivo_id = '.(int)$evento_objetivo);
		elseif ($evento_fator) $sql->adOnde('fator_id = '.(int)$evento_fator);
		elseif ($evento_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$evento_estrategia);
		elseif ($evento_meta) $sql->adOnde('pg_meta_id = '.(int)$evento_meta);
		elseif ($evento_pratica) $sql->adOnde('pratica_id = '.(int)$evento_pratica);
		elseif ($evento_acao) $sql->adOnde('plano_acao_id = '.(int)$evento_acao);
		elseif ($evento_canvas) $sql->adOnde('canvas_id = '.(int)$evento_canvas);
		elseif ($evento_risco) $sql->adOnde('risco_id = '.(int)$evento_risco);
		elseif ($evento_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$evento_risco_resposta);
		elseif ($evento_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$evento_indicador);
		elseif ($evento_calendario) $sql->adOnde('calendario_id = '.(int)$evento_calendario);
		elseif ($evento_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$evento_monitoramento);
		elseif ($evento_ata) $sql->adOnde('ata_id = '.(int)$evento_ata);
		elseif ($evento_mswot) $sql->adOnde('mswot_id = '.(int)$evento_mswot);
		elseif ($evento_swot) $sql->adOnde('swot_id = '.(int)$evento_swot);
		elseif ($evento_operativo) $sql->adOnde('operativo_id = '.(int)$evento_operativo);
		elseif ($evento_instrumento) $sql->adOnde('instrumento_id = '.(int)$evento_instrumento);
		elseif ($evento_recurso) $sql->adOnde('recurso_id = '.(int)$evento_recurso);
		elseif ($evento_problema) $sql->adOnde('problema_id = '.(int)$evento_problema);
		elseif ($evento_demanda) $sql->adOnde('demanda_id = '.(int)$evento_demanda);
		elseif ($evento_programa) $sql->adOnde('programa_id = '.(int)$evento_programa);
		elseif ($evento_licao) $sql->adOnde('licao_id = '.(int)$evento_licao);
		elseif ($evento_evento) $sql->adOnde('evento_id = '.(int)$evento_evento);
		elseif ($evento_link) $sql->adOnde('link_id = '.(int)$evento_link);
		elseif ($evento_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$evento_avaliacao);
		elseif ($evento_tgn) $sql->adOnde('tgn_id = '.(int)$evento_tgn);
		elseif ($evento_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$evento_brainstorm);
		elseif ($evento_gut) $sql->adOnde('gut_id = '.(int)$evento_gut);
		elseif ($evento_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$evento_causa_efeito);
		elseif ($evento_arquivo) $sql->adOnde('arquivo_id = '.(int)$evento_arquivo);
		elseif ($evento_forum) $sql->adOnde('forum_id = '.(int)$evento_forum);
		elseif ($evento_checklist) $sql->adOnde('checklist_id = '.(int)$evento_checklist);
		elseif ($evento_agenda) $sql->adOnde('agenda_id = '.(int)$evento_agenda);
		elseif ($evento_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$evento_agrupamento);
		elseif ($evento_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$evento_patrocinador);
		elseif ($evento_template) $sql->adOnde('template_id = '.(int)$evento_template);
		elseif ($evento_painel) $sql->adOnde('painel_id = '.(int)$evento_painel);
		elseif ($evento_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$evento_painel_odometro);
		elseif ($evento_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$evento_painel_composicao);
		elseif ($evento_tr) $sql->adOnde('tr_id = '.(int)$evento_tr);
		elseif ($evento_me) $sql->adOnde('me_id = '.(int)$evento_me);
		elseif ($evento_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$evento_acao_item);
		elseif ($evento_beneficio) $sql->adOnde('beneficio_id = '.(int)$evento_beneficio);
		elseif ($evento_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$evento_painel_slideshow);
		elseif ($evento_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$evento_projeto_viabilidade);
		elseif ($evento_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$evento_projeto_abertura);
		elseif ($evento_plano_gestao) $sql->adOnde('pg_id = '.(int)$evento_plano_gestao);
		elseif ($evento_ssti) $sql->adOnde('ssti_id = '.(int)$evento_ssti);
		elseif ($evento_laudo) $sql->adOnde('laudo_id = '.(int)$evento_laudo);
		elseif ($evento_trelo) $sql->adOnde('trelo_id = '.(int)$evento_trelo);
		elseif ($evento_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$evento_trelo_cartao);
		elseif ($evento_pdcl) $sql->adOnde('pdcl_id = '.(int)$evento_pdcl);
		elseif ($evento_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$evento_pdcl_item);
		elseif ($evento_os) $sql->adOnde('os_id = '.(int)$evento_os);
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}
	

$tipos = getSisValor('TipoEvento');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'evento\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

//vindo do adicionar evento no plano de comunicacoes
$projeto_comunicacao_evento_id=getParam($_REQUEST, 'projeto_comunicacao_evento_id', null);

$botoesTitulo = new CBlocoTitulo(($evento_id ? 'Editar Evento' : 'Adicionar Evento'), 'calendario.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

	
$recorrencia = array(0 =>'Nunca', 2=> 'Diaria', 3=>'Semanalmente', 4=>'Quinzenal', 5=>'Mensal', 9=>'Bimestral', 10=>'Trimestral', 6=>'Quadrimestral', 7=>'Semestral', 8=>'Anual');
$lembrar = array(''=>'', '900' => '15 mins', '1800' => '30 mins', '3600' => '1 hora', '7200' => '2 horas', '14400' => '4 horas', '28800' => '8 horas', '56600' => '16 horas', '86400' => '1 dia', '172800' => '2 dias');

echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_evento_aed" />';
echo '<input type="hidden" name="del" value="1" />';

echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';


//vindo do adicionar evento no plano de comunicacoes
echo '<input type="hidden" name="projeto_comunicacao_evento_id" value="'.$projeto_comunicacao_evento_id.'" />';

echo '</form>';

$usuarios_selecionados=array();
$depts_selecionados=array();
$cias_selecionadas = array();
if ($evento_id) {
	
	$sql->adTabela('evento_usuario');
	$sql->adCampo('evento_usuario_usuario');
	$sql->adOnde('evento_usuario_evento = '.(int)$evento_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();
	
	
	
	$sql->adTabela('evento_dept');
	$sql->adCampo('evento_dept_dept');
	$sql->adOnde('evento_dept_evento ='.(int)$evento_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('evento_cia');
		$sql->adCampo('evento_cia_cia');
		$sql->adOnde('evento_cia_evento = '.(int)$evento_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_evento_aed" />';
echo '<input type="hidden" name="evento_id" value="'.$evento_id.'" />';
$uuid=($evento_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';
echo '<input type="hidden" name="profissional" id="profissional" value="'.($Aplic->profissional ? 1 : 0).'" />';
echo '<input type="hidden" name="evento_designado" value="" />';
echo '<input type="hidden" name="evento_designado_porcentagem" value="" />';
echo '<input type="hidden" name="evento_inicio_antigo" value="'.$obj->evento_inicio.'" />';
echo '<input type="hidden" name="evento_fim_antigo" value="'.$obj->evento_fim.'" />';
echo '<input type="hidden" name="evento_recorrencia_pai" value="'.$obj->evento_recorrencia_pai.'" />';
//vindo do adicionar evento no plano de comunicacoes
echo '<input type="hidden" name="projeto_comunicacao_evento_id" value="'.$projeto_comunicacao_evento_id.'" />';
echo '<input name="evento_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="evento_cias"  id="evento_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input name="evento_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';


echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Nome', 'Qual o nome do evento.Cada evento deve ter um nome que facilite a compreens�o do mesmo').'Nome:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="evento_titulo" value="'.$obj->evento_titulo.'" maxlength="255" />*</td></tr>';
echo '<tr><td align=right style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' do evento.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'evento_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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
if ($Aplic->profissional) {
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', 'Escolha pressionando o �cone � direita qual '.$config['genero_dept'].' '.$config['dept'].' respons�vel por este evento.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td><input type="hidden" name="evento_dept" id="evento_dept" value="'.($evento_id ? $obj->evento_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($evento_id ? $obj->evento_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
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
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].' com este evento.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';
	}

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Respons�vel', 'Todo evento deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td><input type="hidden" id="evento_dono" name="evento_dono" value="'.($obj->evento_dono ? $obj->evento_dono : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->evento_dono ? $obj->evento_dono : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo', 'Qual o tipo de evento.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'evento_tipo', 'style="width:400px;" size="1" class="texto"', $obj->evento_tipo).'</td></tr>';


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


if ($evento_tarefa) $tipo='tarefa';
elseif ($evento_projeto) $tipo='projeto';
elseif ($evento_perspectiva) $tipo='perspectiva';
elseif ($evento_tema) $tipo='tema';
elseif ($evento_objetivo) $tipo='objetivo';
elseif ($evento_fator) $tipo='fator';
elseif ($evento_estrategia) $tipo='estrategia';
elseif ($evento_meta) $tipo='meta';
elseif ($evento_pratica) $tipo='pratica';
elseif ($evento_acao) $tipo='acao';
elseif ($evento_canvas) $tipo='canvas';
elseif ($evento_risco) $tipo='risco';
elseif ($evento_risco_resposta) $tipo='risco_resposta';
elseif ($evento_indicador) $tipo='evento_indicador';
elseif ($evento_calendario) $tipo='calendario';
elseif ($evento_monitoramento) $tipo='monitoramento';
elseif ($evento_ata) $tipo='ata';
elseif ($evento_mswot) $tipo='mswot';
elseif ($evento_swot) $tipo='swot';
elseif ($evento_operativo) $tipo='operativo';
elseif ($evento_instrumento) $tipo='instrumento';
elseif ($evento_recurso) $tipo='recurso';
elseif ($evento_problema) $tipo='problema';
elseif ($evento_demanda) $tipo='demanda';
elseif ($evento_programa) $tipo='programa';
elseif ($evento_licao) $tipo='licao';
elseif ($evento_evento) $tipo='evento';
elseif ($evento_link) $tipo='link';
elseif ($evento_avaliacao) $tipo='avaliacao';
elseif ($evento_tgn) $tipo='tgn';
elseif ($evento_brainstorm) $tipo='brainstorm';
elseif ($evento_gut) $tipo='gut';
elseif ($evento_causa_efeito) $tipo='causa_efeito';
elseif ($evento_arquivo) $tipo='arquivo';
elseif ($evento_forum) $tipo='forum';
elseif ($evento_checklist) $tipo='checklist';
elseif ($evento_agenda) $tipo='agenda';
elseif ($evento_agrupamento) $tipo='agrupamento';
elseif ($evento_patrocinador) $tipo='patrocinador';
elseif ($evento_template) $tipo='template';
elseif ($evento_painel) $tipo='painel';
elseif ($evento_painel_odometro) $tipo='painel_odometro';
elseif ($evento_painel_composicao) $tipo='painel_composicao';
elseif ($evento_tr) $tipo='tr';
elseif ($evento_me) $tipo='me';
elseif ($evento_acao_item) $tipo='acao_item';
elseif ($evento_beneficio) $tipo='beneficio';
elseif ($evento_painel_slideshow) $tipo='painel_slideshow';
elseif ($evento_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($evento_projeto_abertura) $tipo='projeto_abertura';
elseif ($evento_plano_gestao) $tipo='plano_gestao';
elseif ($evento_ssti) $tipo='ssti';
elseif ($evento_laudo) $tipo='laudo';
elseif ($evento_trelo) $tipo='trelo';
elseif ($evento_trelo_cartao) $tipo='trelo_cartao';
elseif ($evento_pdcl) $tipo='pdcl';
elseif ($evento_pdcl_item) $tipo='pdcl_item';	
elseif ($evento_os) $tipo='os';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado', 'A que �rea o evento est� relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($evento_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja espec�fico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever� constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_projeto" value="'.$evento_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($evento_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja espec�fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever� constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tarefa" value="'.$evento_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($evento_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' escolher � qual '.$config['tarefa'].' o arquivo ir� pertencer.<br><br>Caso n�o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser� d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja espec�fico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever� constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_perspectiva" value="'.$evento_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($evento_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste �cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja espec�fico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever� constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tema" value="'.$evento_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($evento_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste �cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja espec�fico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever� constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_objetivo" value="'.$evento_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($evento_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste �cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja espec�fico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever� constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_fator" value="'.$evento_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($evento_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste �cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja espec�fico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever� constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_estrategia" value="'.$evento_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($evento_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste �cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja espec�fico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever� constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_meta" value="'.$evento_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($evento_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja espec�fico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever� constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_pratica" value="'.$evento_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($evento_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste �cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja espec�fico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo dever� constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_acao" value="'.$evento_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($evento_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar A��o','Clique neste �cone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de a��o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja espec�fico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever� constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_canvas" value="'.$evento_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($evento_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja espec�fico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever� constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_risco" value="'.$evento_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($evento_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste �cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja espec�fico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever� constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_risco_resposta" value="'.$evento_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($evento_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste �cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja espec�fico de um indicador, neste campo dever� constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_indicador" value="'.$evento_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($evento_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja espec�fico de uma agenda, neste campo dever� constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_calendario" value="'.$evento_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($evento_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste �cone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja espec�fico de um monitoramento, neste campo dever� constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_monitoramento" value="'.$evento_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($evento_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste �cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reuni�o', 'Caso seja espec�fico de uma ata de reuni�o neste campo dever� constar o nome da ata').'Ata de Reuni�o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_ata" value="'.(isset($evento_ata) ? $evento_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($evento_ata) ? $evento_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reuni�o','Clique neste �cone '.imagem('icones/ata_p.png').' para selecionar uma ata de reuni�o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja espec�fico de uma matriz SWOT neste campo dever� constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_mswot" value="'.(isset($evento_mswot) ? $evento_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($evento_mswot) ? $evento_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste �cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja espec�fico de um campo de matriz SWOT neste campo dever� constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_swot" value="'.(isset($evento_swot) ? $evento_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($evento_swot) ? $evento_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste �cone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja espec�fico de um plano operativo, neste campo dever� constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_operativo" value="'.$evento_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($evento_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste �cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja espec�fico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever� constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_instrumento" value="'.$evento_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($evento_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste �cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja espec�fico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever� constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_recurso" value="'.$evento_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($evento_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste �cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja espec�fico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever� constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_problema" value="'.$evento_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($evento_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste �cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja espec�fico de uma demanda, neste campo dever� constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_demanda" value="'.$evento_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($evento_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste �cone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja espec�fico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever� constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_programa" value="'.$evento_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($evento_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja espec�fico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo dever� constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_licao" value="'.$evento_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($evento_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste �cone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja espec�fico de um evento, neste campo dever� constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_evento" value="'.$evento_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($evento_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja espec�fico de um link, neste campo dever� constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_link" value="'.$evento_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($evento_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste �cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avalia��o', 'Caso seja espec�fico de uma avalia��o, neste campo dever� constar o nome da avalia��o.').'Avalia��o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_avaliacao" value="'.$evento_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($evento_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia��o','Clique neste �cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia��o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja espec�fico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever� constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tgn" value="'.$evento_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($evento_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste �cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja espec�fico de um brainstorm, neste campo dever� constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_brainstorm" value="'.$evento_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($evento_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste �cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja espec�fico de uma matriz GUT, neste campo dever� constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_gut" value="'.$evento_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($evento_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste �cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja espec�fico de um diagrama de causa-efeito, neste campo dever� constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_causa_efeito" value="'.$evento_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($evento_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste �cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja espec�fico de um arquivo, neste campo dever� constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_arquivo" value="'.$evento_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($evento_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste �cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('F�rum', 'Caso seja espec�fico de um f�rum, neste campo dever� constar o nome do f�rum.').'F�rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_forum" value="'.$evento_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($evento_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F�rum','Clique neste �cone '.imagem('icones/forum_p.gif').' para selecionar um f�rum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja espec�fico de um checklist, neste campo dever� constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_checklist" value="'.$evento_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($evento_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste �cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja espec�fico de um compromisso, neste campo dever� constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_agenda" value="'.$evento_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($evento_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja espec�fico de um agrupamento, neste campo dever� constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_agrupamento" value="'.$evento_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($evento_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste �cone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja espec�fico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo dever� constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_patrocinador" value="'.$evento_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($evento_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste �cone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja espec�fico de um modelo, neste campo dever� constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_template" value="'.$evento_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($evento_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste �cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja espec�fico de um painel de indicador, neste campo dever� constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel" value="'.$evento_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($evento_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste �cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Od�metro de Indicador', 'Caso seja espec�fico de um od�metro de indicador, neste campo dever� constar o nome do od�metro.').'Od�metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel_odometro" value="'.$evento_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($evento_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od�metro','Clique neste �cone '.imagem('icones/odometro_p.png').' para selecionar um od�mtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composi��o de Pain�is', 'Caso seja espec�fico de uma composi��o de pain�is, neste campo dever� constar o nome da composi��o.').'Composi��o de Pain�is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel_composicao" value="'.$evento_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($evento_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composi��o de Pain�is','Clique neste �cone '.imagem('icones/composicao_p.gif').' para selecionar uma composi��o de pain�is.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec�fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever� constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tr" value="'.$evento_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($evento_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec�fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever� constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_me" value="'.$evento_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($evento_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja espec�fico de um item de '.$config['acao'].', neste campo dever� constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_acao_item" value="'.$evento_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($evento_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste �cone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja espec�fico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo dever� constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_beneficio" value="'.$evento_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($evento_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composi��es', 'Caso seja espec�fico de um slideshow de composi��es, neste campo dever� constar o nome do slideshow de composi��es.').'Slideshow de composi��es:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel_slideshow" value="'.$evento_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($evento_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composi��es','Clique neste �cone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composi��es.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja espec�fico de um estudo de viabilidade, neste campo dever� constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_projeto_viabilidade" value="'.$evento_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($evento_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste �cone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja espec�fico de um termo de abertura, neste campo dever� constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_projeto_abertura" value="'.$evento_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($evento_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste �cone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estrat�gico', 'Caso seja espec�fico de um planejamento estrat�gico, neste campo dever� constar o nome do planejamento estrat�gico.').'Planejamento estrat�gico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_plano_gestao" value="'.$evento_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($evento_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estrat�gico','Clique neste �cone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estrat�gico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja espec�fico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo dever� constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_ssti" value="'.$evento_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($evento_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste �cone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja espec�fico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo dever� constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_laudo" value="'.$evento_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($evento_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste �cone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja espec�fico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo dever� constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_trelo" value="'.$evento_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($evento_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste �cone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja espec�fico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo dever� constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_trelo_cartao" value="'.$evento_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($evento_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste �cone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja espec�fico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo dever� constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_pdcl" value="'.$evento_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($evento_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste �cone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja espec�fico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo dever� constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_pdcl_item" value="'.$evento_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($evento_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_pdcl_item();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste �cone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_os ? '' : 'style="display:none"').' id="os" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['os']), 'Caso seja espec�fico de '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].', neste campo dever� constar o nome d'.$config['genero_os'].' '.$config['os'].'.').ucfirst($config['os']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_os" value="'.$evento_os.'" /><input type="text" id="os_nome" name="os_nome" value="'.nome_os($evento_os).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_os();">'.imagem('icones/os_p.png','Selecionar '.ucfirst($config['os']),'Clique neste �cone '.imagem('icones/os_p.png').' para selecionar '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'.').'</a></td></tr></table></td></tr>';




$sql->adTabela('evento_gestao');
$sql->adCampo('evento_gestao.*');
if ($uuid) $sql->adOnde('evento_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
$sql->adOrdem('evento_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['evento_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['evento_gestao_tarefa']).'</td>';
	elseif ($gestao_data['evento_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['evento_gestao_projeto']).'</td>';
	elseif ($gestao_data['evento_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['evento_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['evento_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['evento_gestao_tema']).'</td>';
	elseif ($gestao_data['evento_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['evento_gestao_objetivo']).'</td>';
	elseif ($gestao_data['evento_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['evento_gestao_fator']).'</td>';
	elseif ($gestao_data['evento_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['evento_gestao_estrategia']).'</td>';
	elseif ($gestao_data['evento_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['evento_gestao_meta']).'</td>';
	elseif ($gestao_data['evento_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['evento_gestao_pratica']).'</td>';
	elseif ($gestao_data['evento_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['evento_gestao_acao']).'</td>';
	elseif ($gestao_data['evento_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['evento_gestao_canvas']).'</td>';
	elseif ($gestao_data['evento_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['evento_gestao_risco']).'</td>';
	elseif ($gestao_data['evento_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['evento_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['evento_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['evento_gestao_indicador']).'</td>';
	elseif ($gestao_data['evento_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['evento_gestao_calendario']).'</td>';
	elseif ($gestao_data['evento_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['evento_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['evento_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['evento_gestao_ata']).'</td>';
	elseif ($gestao_data['evento_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['evento_gestao_mswot']).'</td>';
	elseif ($gestao_data['evento_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['evento_gestao_swot']).'</td>';
	elseif ($gestao_data['evento_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['evento_gestao_operativo']).'</td>';
	elseif ($gestao_data['evento_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['evento_gestao_instrumento']).'</td>';
	elseif ($gestao_data['evento_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['evento_gestao_recurso']).'</td>';
	elseif ($gestao_data['evento_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['evento_gestao_problema']).'</td>';
	elseif ($gestao_data['evento_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['evento_gestao_demanda']).'</td>';
	elseif ($gestao_data['evento_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['evento_gestao_programa']).'</td>';
	elseif ($gestao_data['evento_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['evento_gestao_licao']).'</td>';
	
	elseif ($gestao_data['evento_gestao_semelhante']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['evento_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['evento_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['evento_gestao_link']).'</td>';
	elseif ($gestao_data['evento_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['evento_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['evento_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['evento_gestao_tgn']).'</td>';
	elseif ($gestao_data['evento_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['evento_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['evento_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['evento_gestao_gut']).'</td>';
	elseif ($gestao_data['evento_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['evento_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['evento_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['evento_gestao_arquivo']).'</td>';
	elseif ($gestao_data['evento_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['evento_gestao_forum']).'</td>';
	elseif ($gestao_data['evento_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['evento_gestao_checklist']).'</td>';
	elseif ($gestao_data['evento_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['evento_gestao_agenda']).'</td>';
	elseif ($gestao_data['evento_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['evento_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['evento_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['evento_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['evento_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['evento_gestao_template']).'</td>';
	elseif ($gestao_data['evento_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['evento_gestao_painel']).'</td>';
	elseif ($gestao_data['evento_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['evento_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['evento_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['evento_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['evento_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['evento_gestao_tr']).'</td>';	
	elseif ($gestao_data['evento_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['evento_gestao_me']).'</td>';	
	elseif ($gestao_data['evento_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['evento_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['evento_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['evento_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['evento_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['evento_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['evento_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['evento_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['evento_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['evento_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['evento_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['evento_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['evento_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['evento_gestao_ssti']).'</td>';
	elseif ($gestao_data['evento_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['evento_gestao_laudo']).'</td>';
	elseif ($gestao_data['evento_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['evento_gestao_trelo']).'</td>';
	elseif ($gestao_data['evento_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['evento_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['evento_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['evento_gestao_pdcl']).'</td>';
	elseif ($gestao_data['evento_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['evento_gestao_pdcl_item']).'</td>';
	elseif ($gestao_data['evento_gestao_os']) echo '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['evento_gestao_os']).'</td>';
	
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['evento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';


if ($exibir['evento_descricao']) echo '<tr><td align="right" valign="middle" style="white-space: nowrap">'.dica('Descri��o', 'Um resumo sobre o evento.').'Descri��o:'.dicaF().'</td><td><textarea class="textarea" name="evento_descricao" data-gpweb-cmp="ckeditor" rows="5" cols="45">'.$obj->evento_descricao.'</textarea></td></tr>';




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


$data_inicio = intval($obj->evento_inicio) ? new CData($obj->evento_inicio) : false;
$data_fim = intval($obj->evento_fim) ? new CData( $obj->evento_fim ) : false;
if((!$data_inicio || !$data_fim) && $Aplic->profissional){
	require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
	$cache = CTarefaCache::getInstance();
		$exped = $cache->getExpedienteParaHoje((int)$cia_id, null, null, ($data ? substr($data, 0, 4).'-'.substr($data, 4, 2).'-'.substr($data, 6, 2): null));
	if(!$data_inicio){
		$data_inicio = $exped['inicio'];
		}
	if(!$data_fim){
		$desloc = $config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8;
		$data_fim = $cache->deslocaDataPraFrente($data_inicio, $desloc, (int)$cia_id);
		$data_fim = $cache->ajustaInicioPeriodo($data_fim, (int)$cia_id);
		}
	$obj->evento_duracao = $cache->horasPeriodo($data_inicio, $data_fim, (int)$cia_id);
	if(is_string($data_inicio)) $data_inicio = new CData($data_inicio);
	if(is_string($data_fim)) $data_fim = new CData($data_fim);
	}
	
elseif((!$data_inicio || !$data_fim) && !$Aplic->profissional){
	$data_inicio = new CData(date("Y-m-d H:i:s"));
	$data_fim = new CData(date("Y-m-d H:i:s"));
	}
		


echo '<input name="evento_inicio" id="evento_inicio" type="hidden" value="'.$data_inicio->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<input name="evento_fim" id="evento_fim" type="hidden" value="'.$data_fim->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<tr><td align=right width="100" >'.dica('Data de In�cio', 'A data de in�cio do evento.').'In�cio:'.dicaF().'</td><td><input type="hidden" id="oculto_data_inicio" name="oculto_data_inicio"  value="'.$data_inicio->format('%Y-%m-%d').'" /><input type="text" onchange="setData(\'env\', \'data_inicio\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.$data_inicio->format('%d/%m/%Y').'" />'.dica('Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').' para abrir um calend�rio onde poder� selecionar a data de in�cio.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio"" border=0 /></a>'.dicaF().dica('Hora do In�cio', 'Selecione na caixa de sele��o a hora do �nicio do evento.'). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="CompararDatas(); data_ajax();" class="texto"', $data_inicio->getHour()).' : '.dica('Minutos do In�cio', 'Selecione na caixa de sele��o os minutos do �nicio do evento.').selecionaVetor($minutos, 'inicio_minuto', 'size="1" class="texto" onchange="CompararDatas(); data_ajax();"', $data_inicio->getMinute()).'</td></tr>';
echo '<tr><td align=right>'.dica('Data de T�rmino', 'A data de t�rmino do evento.').'T�rmino:'.dicaF().'</td><td><input type="hidden" id="oculto_data_fim" name="oculto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_fim\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" />'.dica('Data de T�rmino', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data de t�rmino.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio"" border=0 /></a>'.dicaF().dica('Hora do T�rmino', 'Selecione na caixa de sele��o a hora do t�rmino.</p>Caso n�o saiba a hora prov�vel de t�rmino, deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($horas, 'fim_hora', 'size="1" onchange="CompararDatas(); horas_ajax();" class="texto"', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do T�rmino', 'Selecione na caixa de sele��o os minutos do t�rmino. </p>Caso n�o saiba os minutos prov�veis de t�rmino, deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($minutos, 'fim_minuto', 'size="1" class="texto" onchange="CompararDatas(); horas_ajax();"', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('A dura��o do evento em dias.').'Dura��o:'.dicaF().'</td><td nowrap="nowrap"><input type="text" onchange="data_ajax();" onkeypress="return somenteFloat(event)" class="texto" name="evento_duracao" id="evento_duracao" maxlength="30" size="2" value="'.float_brasileiro((float)$obj->evento_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)).'" />&nbsp;dias</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Recorr�ncia', 'De quanto em quanto tempo este evento se repete.').'Recorr�ncia:'.dicaF().'</td><td>'.selecionaVetor($recorrencia, 'evento_recorrencias', 'size="1" class="texto"', $obj->evento_recorrencias).dica('N�mero de Recorrencias', 'Escolha o n�mero de vezes que a faixa de tempo escolhida repetir�.').'x'.dicaF().'<input type="text" class="texto" name="evento_nr_recorrencias" value="'.((isset($obj->evento_nr_recorrencias)) ? ($obj->evento_nr_recorrencias) : '1').'" maxlength="2" size="3" />'.dica('N�mero de Recorrencias', 'Escolha o n�mero de vezes que a faixa de tempo escolhida repetir�.').'vezes'.dicaF().'</td></tr>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Lembrar', 'Envio de mensagem para lembrar do evento.').'Lembrar:'.dicaF().'</td><td>'.selecionaVetor($lembrar, 'evento_lembrar', 'size="1" class="texto"', $obj->evento_lembrar).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('De 2� a 6� feira', 'Marque esta caixa para que a faixa de tempo do evento n�o inclua os fins de semana.').'<label for="evento_diautil">De 2� a 6� feira:</label>'.dicaF().'</td><td><input type="checkbox" value="1" name="evento_diautil" id="evento_diautil" '.($obj->evento_diautil ? 'checked="checked"' : '').' /></td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_evento = '.(int)$evento_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados mais representativo da situa��o geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'evento_principal_indicador', 'class="texto" style="width:284px;"', $obj->evento_principal_indicador).'</td></tr>';
	}
	
echo '<tr><td align="right" style="white-space: nowrap">'.dica('N�vel de Acesso', 'O evento pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons�vel e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante I</b> - Somente o respons�vel, participantes e designados podem ver e respons�vel e designados editar</li><li><b>Participantes II</b> - Somente o respons�vel, participantes e os designados podem ver e apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons�vel, participantes e os designados podem ver e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($niveis_acesso, 'evento_acesso', 'class="texto"', ($evento_id ? $obj->evento_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milh�es poss�veis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inser��o do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="evento_cor" value="'.($obj->evento_cor ? $obj->evento_cor : 'DDDDDD').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o evento ainda esteja ativo dever� estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="evento_ativo" '.($obj->evento_ativo || !$evento_id ? 'checked="checked"' : '').' /></td></tr>';



require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados('evento', $obj->evento_id, 'editar');
$campos_customizados->imprimirHTML();

$cincow2h=($exibir['evento_oque'] && $exibir['evento_quem'] && $exibir['evento_quando'] && $exibir['evento_onde'] && $exibir['evento_porque'] && $exibir['evento_como'] && $exibir['evento_quanto']);

if ($cincow2h){
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['evento_oque']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('O Que Fazer', 'Sum�rio sobre o que se trata este evento.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="evento_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_oque.'</textarea></td></tr>';
if ($exibir['evento_quem']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quem', 'Quem executar o evento.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="evento_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_quem.'</textarea></td></tr>';
if ($exibir['evento_quando']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quando Fazer', 'Quando o evento � executado.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="evento_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_quando.'</textarea></td></tr>';
if ($exibir['evento_onde']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Onde Fazer', 'Onde o evento � executado.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="evento_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_onde.'</textarea></td></tr>';
if ($exibir['evento_porque']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Por Que Fazer', 'Por que o evento ser� executado.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="evento_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_porque.'</textarea></td></tr>';
if ($exibir['evento_como']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Como Fazer', 'Como o evento � executado.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="evento_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_como.'</textarea></td></tr>';
if ($exibir['evento_quanto']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quanto Custa', 'Custo para executar o evento.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="evento_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_quanto.'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:1px;"></td></tr>';
	}




echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'participantes\').style.display) document.getElementById(\'participantes\').style.display=\'\'; else document.getElementById(\'participantes\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Convidados</b></a></td></tr>';

echo '<tr id="participantes" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width=100%>';


echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td align=right style="white-space: nowrap;width:150px">'.dica('Exibi��o', 'Forma de apresentar os ').'Exibi��o:'.dicaF().'</td><td><input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="mudar_usuarios_designados()" checked>'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" value="simples" id="simples" onChange="mudar_usuarios_designados();">Lista simples</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp1 WHERE gp1.grupo_permissao_grupo=grupo.grupo_id) AS protegido, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp2 WHERE gp2.grupo_permissao_grupo=grupo.grupo_id AND gp2.grupo_permissao_usuario='.(int)$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();
$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se h� grupo privado da cia, se houver n�o haver� op��o de ver todos o usu�rios da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right width=100>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td></tr>';

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo','Escolha '.$config['usuarios'].' inclu�d'.$config['genero_usuario'].'s em um dos grupos.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:400px" class="texto" onchange="env.grupo_b.value=0; mudar_usuarios_designados()"',$grupo_id).'</td></tr>';
else echo '<input type="hidden" name="grupo_a" id="grupo_a" value="" />';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'')+$grupos;
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo Particular','Escolha '.$config['usuarios'].' inclu�d'.$config['genero_usuario'].'s em um dos seus grupos particulares.').'Grupo Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:400px" size="1" class="texto" onchange="env.grupo_a.value=0; mudar_usuarios_designados()"',$grupo_id2).'</td></tr>';
else echo '<input type="hidden" name="grupo_b" id="grupo_b" value="" />';
echo '<tr><td align=right width=100>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuarios_designados();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\'; mudar_usuarios_designados()">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
echo '</table></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste �cone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';

echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0>';
echo '<tr><td style="text-align:left" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Sele��o de '.ucfirst($config['usuarios']),'D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para adiciona-lo � lista de destinat�rio.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o INCLUIR.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;'.ucfirst($config['usuarios']).'&nbsp</legend>';
echo '<div id="combo_de">';
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';

	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('grupo_usuario','grupo_usuario','grupo_usuario_usuario=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		if ($grupo_id2) $sql->adOnde('grupo_usuario_grupo='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('grupo_usuario_grupo='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');
		$usuarios = $sql->Lista();
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'</option>';
    }
	echo '</select>';
	}
echo '</div></fieldset>';
echo '</td>';











echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Chamar','D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para remove-lo dos convidados.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'Chamar&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';

if (!$evento_id){
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'con','usuario_contato=con.contato_id');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome, 100 AS evento_participante_percentual');
	$sql->adOnde('usuario_id = '.(int)$Aplic->usuario_id);
	$ListaPARA=$sql->Lista();
	$sql->limpar();
	}
else{
	$sql->adTabela('evento_participante');
	$sql->esqUnir('usuarios', 'u', 'u.usuario_id=evento_participante_usuario');
	$sql->esqUnir('contatos', 'con','u.usuario_contato=con.contato_id');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo('u.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome, evento_participante_percentual');
	$sql->adOnde('evento_participante_evento = '.(int)$evento_id);
	$ListaPARA=$sql->Lista();
	$sql->limpar();
	}

foreach($ListaPARA as $rs) echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').' - '.$rs['evento_participante_percentual'].'%</option>';
echo '</select></fieldset></td></tr>';


echo '<select name="ListaPARAnome[]" multiple id="ListaPARAnome" size=4 style="width:100%;display:none;">';
foreach($ListaPARA as $rs) echo '<option value="'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'">*</option>';
echo '</select>';

echo '<select name="ListaPARAporcentagem[]" multiple id="ListaPARAporcentagem" size=4 style="width:100%;display:none;">';
foreach($ListaPARA as $rs) echo '<option value="'.$rs['evento_participante_percentual'].'">'.$rs['evento_participante_percentual'].'</option>';
echo '</select>';



echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=2 cellspacing=0><tr>
<td>'.botao('incluir >>', 'Incluir','Clique neste bot�o para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinat�rios.','','Mover(env.ListaDE, env.ListaPARA)','','',0).'</td>
<td>'.botao('incluir todos', 'Incluir Todos','Clique neste bot�o para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.','','btSelecionarTodos_onclick()','','',0).'</td>

<td>'.dica('N�vel de Engajamento', 'Fazer um controle sobre '.$config['usuarios'].' sobrecarregados. As porcentagens de todos  os eventos e '.$config['tarefas'].' que os mesmos est�o designados � somada, dia a dia, e poderemos verificar os ociosos ou aqueles exageradamente sobrecarregados e fazer as redistribui��es de miss�es apropriadas.').'<select name="percentagem_designar" id="percentagem_designar" class="texto">';
	for ($i = 5; $i <= 100; $i += 5) echo '<option '.($i == 100 ? 'selected="true"' : '').' value="'.$i.'">'.$i.'%</option>';
echo '</select>'.dicaF().'</td>


<td>'.botao('respons�vel', 'Respons�vel','Clique neste bot�o para incluir o respons�vel na lista de '.$config['usuarios'].' a escolher.','','Responsavel()','','',0).'</td>
<td>'.botao('designados', 'Designados','Clique neste bot�o para incluir os designados  na lista de '.$config['usuarios'].' a escolher.','','Designados()','','',0).'</td>
<td>'.botao('comprometimento', 'Comprometimento','Visualizar o grau de comprometimento, por dia, d'.$config['genero_usuario'].' '.$config['usuario'].'.','','comprometimento()','','',0).'</td>
</tr></table></td><td style="text-align:center"><table><tr>
<td>'.botao('<< remover', 'Remover','Clique neste bot�o para remover os destinat�rios selecionados da caixa de destinat�rios.','','Mover2(env.ListaPARA, env.ListaDE)','','',0).'</td>
</tr></table></td></tr>';

echo '</table></td></tr>';


echo '</table></fieldset></td></tr>';








echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'notificar\').style.display) document.getElementById(\'notificar\').style.display=\'\'; else document.getElementById(\'notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';
echo '<tr><td valign="middle" align="right" style="white-space: nowrap;width:150px">'.dica('Notificar', 'Marque esta caixa para avisar da '.($evento_id > 0 ? 'modifica��o' : 'cria��o').' do evento.').'Notificar:'.dicaF().'</td><td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'": '').' />'.dica('Respons�vel', 'Ao selecionar esta op��o, o respons�vel pelo evento ser� informado '.($evento_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Respons�vel'.dicaF().'<br>';
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? "checked='checked'": '').' />'.dica('Designados', 'Ao selecionar esta op��o, os designados para o evento ser�o informados '.($evento_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Designados'.dicaF().'<br>';
echo '<input type="checkbox" name="email_designados_novos" id="email_designados_novos" />'.dica('Designados Novos', 'Ao selecionar esta op��o, os novos designados para o evento ser�o informados '.($evento_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Designados novos'.dicaF().'<br>';

echo '<input type="checkbox" name="email_convidados" id="email_convidados" '.($Aplic->getPref('informa_designados') ? "checked='checked'": '').' />'.dica('Convidados', 'Ao selecionar esta op��o, os convidados do evento ser�o informado '.($evento_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Convidados'.dicaF().'<br>';
echo '<input type="checkbox" name="email_convidados_novos" id="email_convidados_novos" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica('Convidados Novos', 'Ao selecionar esta op��o, os novos convidados do evento ser�o informado '.($evento_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Convidados novos'.dicaF().'&nbsp;&nbsp;';
echo '</td></tr>';
echo '<input type="hidden" name="email_contatos_extra" id="email_contatos_extra" value="" />';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Outros '.ucfirst($config['contatos']), ucfirst($config['contatos']).' extras para receberem notifica��o.').'Outros '.$config['contatos'].':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_contatos2"><table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table></div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos2()').'</td></tr></table></td></tr>';
echo ($config['email_ativo'] ? ''.($config['email_ativo'] ? '<tr><td align="right">'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados.').'Destinat�rios extra:'.dicaF().'</td><td><input type="text" class="texto" name="email_extras" maxlength="255" style="width:400px;" /></td></tr>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />');
echo '<tr><td align="right">'.dica('Texto', 'Texto a ser enviado junto com a notifica��o.').'Texto:'.dicaF().'</td><td><textarea name="email_texto" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea"></textarea></td></tr>';
echo '</table></td></tr>';













echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retorna � tela anterior.','','if(confirm(\'Tem certeza quanto � cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr>';

echo '</table></td></tr></table>';
echo '</form>';
echo estiloFundoCaixa();




?>
<script type="text/javascript">
	
var contatos_id_selecionados2 = '';

function popContatos2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('evento_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados2, window.setContatos2, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos2&cia_id='+document.getElementById('evento_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados2, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos2(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.getElementById('email_contatos_extra').value = contato_id_string;
	contatos_id_selecionados2 = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados2, 'combo_contatos2');
	__buildTooltip();
	}


function mudar_usuarios_designados(){
	var tipo=document.env.modo_exibicao.value;
	grupo=document.getElementById('grupo_b').value;
	if (!grupo|| grupo==0) grupo=document.getElementById('grupo_a').value;
	if (grupo==-1) grupo=null;
	if (tipo=='dept')	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE', 'combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"', document.getElementById('busca').value, grupo);
	else xajax_mudar_usuario_grupo_ajax(grupo, document.getElementById('busca').value);
	}
	
function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1);
	}









var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('evento_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('evento_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.evento_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('evento_cia').value+'&cias_id_selecionadas='+document.getElementById('evento_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.evento_cias.value = organizacao_id_string;
	document.getElementById('evento_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('evento_cias').value);
	__buildTooltip();
	}

function excluir_arquivo(evento_arquivo_id){
	xajax_excluir_arquivo(document.getElementById('evento_id').value, evento_arquivo_id);
	__buildTooltip();
	}

function mudar_posicao_arquivo(ordem, evento_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(ordem, evento_arquivo_id, direcao, document.getElementById('evento_id').value);
	__buildTooltip();
	}



function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');
	var ta = document.createTextNode(' Arquivo:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'arquivo[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=80;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('evento_dept').value+'&cia_id='+document.getElementById('evento_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('evento_dept').value+'&cia_id='+document.getElementById('evento_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('evento_cia').value=cia_id;
	document.getElementById('evento_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('evento_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('evento_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.evento_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}


function mudar_om(){
	var cia_id=document.getElementById('evento_cia').value;
	xajax_selecionar_om_ajax(cia_id,'evento_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('evento_cia').value+'&usuario_id='+document.getElementById('evento_dono').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('evento_cia').value+'&usuario_id='+document.getElementById('evento_dono').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('evento_dono').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?> ? ' - '+nome_cia : '');
		}

function comprometimento(){
	if (document.getElementById('ListaDE').selectedIndex >-1){
		var usuario_id=document.getElementById('ListaDE').options[document.getElementById('ListaDE').selectedIndex].value;
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Comprometimento', 820, 620, 'm=calendario&a=sobrecarga&dialogo=1&cia_id=<?php echo $cia_id ?>&usuario_id='+usuario_id+'&editar=1', null, window);
		else window.open('./index.php?m=calendario&a=sobrecarga&dialogo=1&cia_id=<?php echo $cia_id ?>&usuario_id='+usuario_id+'&editar=1', 'Comprometimento', 'height=620,width=820,resizable,scrollbars=yes');
		}
	else alert('Precisa selecionar um <?php echo $config["usuario"]?>.');
	}


function Responsavel(){
	var f=document.env;
	var chave=0;
	var tipo='';
	if(f.evento_tarefa.value >0){chave=f.evento_tarefa.value; tipo='tarefa';}
	else if(f.evento_projeto.value >0){chave=f.evento_projeto.value; tipo='projeto';}
	else if(f.evento_pratica.value >0){chave=f.evento_pratica.value; tipo='pratica';}
	else if(f.evento_indicador.value >0){chave=f.evento_indicador.value; tipo='indicador';}
	else if(f.evento_estrategia.value >0){chave=f.evento_estrategia.value; tipo='estrategia';}
	else if(f.evento_objetivo.value >0){chave=f.evento_objetivo.value; tipo='objetivo';}
	else if(f.evento_tema.value >0){chave=f.evento_tema.value; tipo='tema';}
	else if(f.evento_acao.value >0){chave=f.evento_acao.value; tipo='acao';}
	if (tipo || <?php echo ($Aplic->profissional ? 1 : 0)?>) {
		xajax_responsavel_ajax(tipo, chave, document.getElementById('evento_id').value, document.getElementById('uuid').value);
		env.grupo_b.value=0;
		env.grupo_a.value=0;
		}
	else alert('Precisa selecionar primeiro!');
	}


function Designados(){
	var f=document.env;
	var chave=0;
	var tipo='';
	if(f.evento_tarefa.value >0){chave=f.evento_tarefa.value; tipo='tarefa';}
	else if(f.evento_projeto.value >0){chave=f.evento_projeto.value; tipo='projeto';}
	else if(f.evento_pratica.value >0){chave=f.evento_pratica.value; tipo='pratica';}
	else if(f.evento_indicador.value >0){chave=f.evento_indicador.value; tipo='indicador';}
	else if(f.evento_estrategia.value >0){chave=f.evento_estrategia.value; tipo='estrategia';}
	else if(f.evento_objetivo.value >0){chave=f.evento_objetivo.value; tipo='objetivo';}
	else if(f.evento_tema.value >0){chave=f.evento_tema.value; tipo='tema';}
	else if(f.evento_acao.value >0){chave=f.evento_acao.value; tipo='acao';}
	else if(f.evento_calendario.value >0){chave=f.evento_calendario.value; tipo='calendario';}
	if (tipo || <?php echo ($Aplic->profissional ? 1 : 0)?>) {
		xajax_designados_ajax(tipo, chave, document.getElementById('evento_id').value, document.getElementById('uuid').value);
		env.grupo_b.value=0;
		env.grupo_a.value=0;
		}
	else alert('Precisa selecionar primeiro!');
	}




function enviarDados(){
	var form = document.env;
	if (form.evento_titulo.value.length < 3) {
		alert('Insira o nome do evento');
		form.evento_titulo.focus();
		return;
		}
	if (form.evento_inicio.value.length < 3){
		alert('Insira a data de �nicio');
		form.evento_inicio.focus();
		return;
		}
	if (form.evento_fim.value.length < 3){
		alert('Insira a data de t�rmino');
		form.evento_fim.focus();
		return;
		}
	if ( (!(form.evento_nr_recorrencias.value>0))
		&& (form.evento_recorrencias[0].selected!=true) ) {
		alert('Insira o n�mero de recorr�ncias');
		form.evento_nr_recorrencias.value=1;
		form.evento_nr_recorrencias.focus();
		return;
		}



	if (!document.getElementById('profissional').value && document.getElementById('calendario_ver').style.display=='' && form.evento_calendario.value<1)	{
		alert('Escolha uma agenda');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('projeto').style.display=='' && form.evento_projeto.value<1)	{
		alert('Escolha <?php echo ($config["genero_projeto"]=="a" ? "uma ": "um ").$config["projeto"] ?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('pratica').style.display=='' && form.evento_pratica.value<1)	{
		alert('Escolha <?php echo ($config["genero_pratica"]=="a" ? "uma ": "um ").$config["pratica"] ?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('acao').style.display=='' && form.evento_acao.value<1)	{
		alert('Escolha <?php echo ($config["genero_acao"]=="o" ? "um" : "uma")." ".$config["acao"]?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('indicador').style.display=='' && form.evento_indicador.value<1)	{
		alert('Escolha um indicador');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('objetivo').style.display=='' && form.evento_objetivo.value<1)	{
		alert("Escolha <?php echo ($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('tema').style.display=='' && form.evento_tema.value<1)	{
		alert("Escolha <?php echo ($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('estrategia').style.display=='' && form.evento_estrategia.value<1)	{
		alert('Escolha <?php echo ($config["genero_iniciativa"]=="o" ? "um" : "uma")." ".$config["iniciativa"]?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('fator').style.display=='' && form.evento_fator.value<1)	{
		alert('Escolha <?php echo ($config["genero_fator"]=="o" ? "um" : "uma")." ".$config["fator"]?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('meta').style.display=='' && form.evento_meta.value<1)	{
		alert('Escolha uma meta');
		return;
		}


	var designadoporcentagem = form.ListaPARAporcentagem;
	var designado = form.ListaPARA;
	var len = designado.length;
	var usuarios = form.evento_designado;
	var porcentagem=form.evento_designado_porcentagem;
	porcentagem.value = '';
	usuarios.value = '';
	for (var i = 0; i < len; i++) {
		if (i) usuarios.value += ',';
		if (i) porcentagem.value += ',';
		porcentagem.value +=designadoporcentagem.options[i].value;
		usuarios.value += designado.options[i].value;
		}
		
	document.getElementById('evento_inicio').value=document.getElementById('oculto_data_inicio').value+' '+document.getElementById('inicio_hora').value+':'+document.getElementById('inicio_minuto').value+':00';
	document.getElementById('evento_fim').value=document.getElementById('oculto_data_fim').value+' '+document.getElementById('fim_hora').value+':'+document.getElementById('fim_minuto').value+':00';	
		
	form.submit();
	}



var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "oculto_data_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    data_ajax();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "oculto_data_fim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    horas_ajax();
	    }
		cal2.hide();
		}
	});


function CompararDatas(){
  var str1 = document.getElementById("oculto_data_inicio").value
  var dia1  = parseInt(str1.substring(8,10),10);
  var mes1 = parseInt(str1.substring(5,7),10);
  var ano1  = parseInt(str1.substring(0,4),10);
  var hora1  = parseInt(document.getElementById("inicio_hora").value,10);
	var minuto1  = parseInt(document.getElementById("inicio_minuto").value,10);
	var data1 = new Date(ano1, mes1, dia1, hora1, minuto1);

	var str2 = document.getElementById("oculto_data_fim").value
  var dia2  = parseInt(str2.substring(8,10),10);
  var mes2 = parseInt(str2.substring(5,7),10);
  var ano2  = parseInt(str2.substring(0,4),10);
  var hora2  = parseInt(document.getElementById("fim_hora").value,10);
	var minuto2  = parseInt(document.getElementById("fim_minuto").value,10);
	var data2 = new Date(ano2, mes2, dia2, hora2, minuto2);
  if(data2 < data1){
    document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
    document.getElementById("oculto_data_fim").value=document.getElementById("oculto_data_inicio").value;
    document.getElementById("fim_minuto").value=document.getElementById("inicio_minuto").value;
    document.getElementById("fim_hora").value=document.getElementById("inicio_hora").value;
  	}
	}

function setData(frm_nome, f_data) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+'oculto_'+f_data );
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

function horas_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minuto.value+':00';
	var fim=f.oculto_data_fim.value+' '+f.fim_hora.value+':'+f.fim_minuto.value+':00';
	xajax_calcular_duracao(inicio, fim, document.getElementById('evento_cia').value);
	}

function data_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minuto.value+':00';
	var horas=f.evento_duracao.value;
	xajax_data_final_periodo(inicio, horas, document.getElementById('evento_cia').value);
	}

function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}



function setCor(cor) {
	var f = document.env;
	if (cor) f.evento_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.evento_cor.value;
	}

function excluir() {
	if (confirm("Tem certeza que deseja excluir o evento?")) document.frmExcluir.submit();
	}






function Mover(ListaDE,ListaPARA) {
	//checar se j� existe

	var perc=document.getElementById('percentagem_designar').options[document.getElementById('percentagem_designar').selectedIndex].value;

	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value  > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '')+' - '+perc+'%';


			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) {
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {

				var no1 = new Option();
				no1.value = ListaDE.options[i].text;
				no1.text = ListaDE.options[i].text;
				ListaPARAnome.options[ListaPARAnome.options.length]=no1;

				ListaPARA.options[ListaPARA.options.length] = no;
				//ListaDE.options[i].value = "";
				//ListaDE.options[i].text = "";

				var no2 = new Option();
				no2.value = perc;
				no2.text = perc;
				ListaPARAporcentagem.options[ListaPARAporcentagem.options.length]=no2;


				}
			}
		}
	//LimpaVazios(ListaDE, ListaDE.options.length);
	}

function Mover2(ListaPARA,ListaDE) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""
			ListaPARAporcentagem.options[i].value = ""
			ListaPARAporcentagem.options[i].text = ""
			ListaPARAnome.options[i].value = ""
			ListaPARAnome.options[i].text = ""
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	LimpaVazios(ListaPARAporcentagem, ListaPARAporcentagem.options.length);
	LimpaVazios(ListaPARAnome, ListaPARAnome.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}


// Seleciona todos os campos da lista de usu�rios
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
	}
	Mover(env.ListaDE, env.ListaPARA);
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('evento_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('evento_cia').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.evento_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('evento_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.evento_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('evento_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('evento_cia').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.evento_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('evento_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.evento_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('evento_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.evento_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('evento_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.evento_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('evento_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.evento_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('evento_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.evento_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('evento_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.evento_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('evento_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.evento_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('evento_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.evento_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('evento_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.evento_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('evento_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('evento_cia').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.evento_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('evento_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.evento_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('evento_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.evento_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('evento_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.evento_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('evento_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('evento_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.evento_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('evento_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('evento_cia').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.evento_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('evento_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('evento_cia').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.evento_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni�o', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('evento_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.evento_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('evento_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.evento_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Cam�po SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('evento_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.evento_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('evento_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('evento_cia').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.evento_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur�dico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('evento_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('evento_cia').value, 'Instrumento Jur�dico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.evento_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('evento_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('evento_cia').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.evento_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('evento_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.evento_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('evento_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('evento_cia').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.evento_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('evento_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.evento_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('evento_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.evento_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('evento_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('evento_cia').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.evento_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('evento_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('evento_cia').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.evento_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia��o', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('evento_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('evento_cia').value, 'Avalia��o','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.evento_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('evento_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.evento_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('evento_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('evento_cia').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.evento_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('evento_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('evento_cia').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.evento_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('evento_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('evento_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.evento_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('evento_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('evento_cia').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.evento_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F�rum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('evento_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('evento_cia').value, 'F�rum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.evento_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('evento_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('evento_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.evento_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('evento_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('evento_cia').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.evento_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('evento_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('evento_cia').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.evento_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od�metro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('evento_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('evento_cia').value, 'Od�metro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.evento_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi��o de Pain�is', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('evento_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('evento_cia').value, 'Composi��o de Pain�is','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.evento_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('evento_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.evento_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('evento_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.evento_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('evento_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('evento_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.evento_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('evento_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.evento_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composi��es', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('evento_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('evento_cia').value, 'Slideshow de Composi��es','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.evento_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('evento_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('evento_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.evento_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('evento_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('evento_cia').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.evento_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estrat�gico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('evento_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('evento_cia').value, 'Planejamento Estrat�gico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.evento_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('evento_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.evento_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('evento_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.evento_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('evento_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.evento_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('evento_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.evento_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('evento_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.evento_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function pop_pdcl_item() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('evento_cia').value, window.set_pdcl_item, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_pdcl_item(chave, valor){
	limpar_tudo();
	document.env.evento_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	

function pop_os() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["os"])?>', 1000, 700, 'm=os&a=os_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_os&tabela=os&cia_id='+document.getElementById('evento_cia').value, window.set_os, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_os&tabela=os&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["os"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_os(chave, valor){
	limpar_tudo();
	document.env.evento_os.value = chave;
	document.env.os_nome.value = valor;
	incluir_relacionado();
	}	


function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.evento_projeto.value = null;
	document.env.evento_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.evento_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.evento_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.evento_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.evento_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.evento_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.evento_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.evento_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.evento_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.evento_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.evento_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.evento_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.evento_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.evento_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.evento_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.evento_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.evento_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.evento_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.evento_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.evento_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.evento_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.evento_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.evento_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.evento_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.evento_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.evento_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.evento_link.value = null;
	document.env.link_nome.value = '';
	document.env.evento_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.evento_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.evento_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.evento_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.evento_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.evento_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.evento_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.evento_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.evento_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.evento_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.evento_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.evento_template.value = null;
	document.env.template_nome.value = '';
	document.env.evento_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.evento_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.evento_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.evento_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.evento_me.value = null;
	document.env.me_nome.value = '';
	document.env.evento_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.evento_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.evento_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.evento_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.evento_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.evento_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.evento_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.evento_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.evento_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.evento_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.evento_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.evento_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';	
	document.env.evento_os.value = null;
	document.env.os_nome.value = '';			
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('evento_id').value,
	document.getElementById('uuid').value,
	f.evento_projeto.value,
	f.evento_tarefa.value,
	f.evento_perspectiva.value,
	f.evento_tema.value,
	f.evento_objetivo.value,
	f.evento_fator.value,
	f.evento_estrategia.value,
	f.evento_meta.value,
	f.evento_pratica.value,
	f.evento_acao.value,
	f.evento_canvas.value,
	f.evento_risco.value,
	f.evento_risco_resposta.value,
	f.evento_indicador.value,
	f.evento_calendario.value,
	f.evento_monitoramento.value,
	f.evento_ata.value,
	f.evento_mswot.value,
	f.evento_swot.value,
	f.evento_operativo.value,
	f.evento_instrumento.value,
	f.evento_recurso.value,
	f.evento_problema.value,
	f.evento_demanda.value,
	f.evento_programa.value,
	f.evento_licao.value,
	f.evento_evento.value,
	f.evento_link.value,
	f.evento_avaliacao.value,
	f.evento_tgn.value,
	f.evento_brainstorm.value,
	f.evento_gut.value,
	f.evento_causa_efeito.value,
	f.evento_arquivo.value,
	f.evento_forum.value,
	f.evento_checklist.value,
	f.evento_agenda.value,
	f.evento_agrupamento.value,
	f.evento_patrocinador.value,
	f.evento_template.value,
	f.evento_painel.value,
	f.evento_painel_odometro.value,
	f.evento_painel_composicao.value,
	f.evento_tr.value,
	f.evento_me.value,
	f.evento_acao_item.value,
	f.evento_beneficio.value,
	f.evento_painel_slideshow.value,
	f.evento_projeto_viabilidade.value,
	f.evento_projeto_abertura.value,
	f.evento_plano_gestao.value,
	f.evento_ssti.value,
	f.evento_laudo.value,
	f.evento_trelo.value,
	f.evento_trelo_cartao.value,
	f.evento_pdcl.value,
	f.evento_pdcl_item.value,
	f.evento_os.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(evento_gestao_id){
	xajax_excluir_gestao(document.getElementById('evento_id').value, document.getElementById('uuid').value, evento_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, evento_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, evento_gestao_id, direcao, document.getElementById('evento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$evento_id && (
	$evento_tarefa || 
	$evento_projeto || 
	$evento_perspectiva || 
	$evento_tema || 
	$evento_objetivo || 
	$evento_fator || 
	$evento_estrategia || 
	$evento_meta || 
	$evento_pratica || 
	$evento_acao || 
	$evento_canvas || 
	$evento_risco || 
	$evento_risco_resposta || 
	$evento_indicador || 
	$evento_calendario || 
	$evento_monitoramento || 
	$evento_ata || 
	$evento_mswot || 
	$evento_swot || 
	$evento_operativo || 
	$evento_instrumento || 
	$evento_recurso || 
	$evento_problema || 
	$evento_demanda || 
	$evento_programa || 
	$evento_licao || 
	$evento_evento || 
	$evento_link || 
	$evento_avaliacao || 
	$evento_tgn || 
	$evento_brainstorm || 
	$evento_gut || 
	$evento_causa_efeito || 
	$evento_arquivo || 
	$evento_forum || 
	$evento_checklist || 
	$evento_agenda || 
	$evento_agrupamento || 
	$evento_patrocinador || 
	$evento_template || 
	$evento_painel || 
	$evento_painel_odometro || 
	$evento_painel_composicao || 
	$evento_tr || 
	$evento_me || 
	$evento_acao_item || 
	$evento_beneficio || 
	$evento_painel_slideshow || 
	$evento_projeto_viabilidade || 
	$evento_projeto_abertura || 
	$evento_plano_gestao|| 
	$evento_ssti || 
	$evento_laudo || 
	$evento_trelo || 
	$evento_trelo_cartao || 
	$evento_pdcl || 
	$evento_pdcl_item ||
	$evento_os
	)) echo 'incluir_relacionado();';
	?>	
</script>
