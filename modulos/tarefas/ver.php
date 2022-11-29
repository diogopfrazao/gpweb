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


if (isset($_REQUEST['tarefa_id'])) $Aplic->setEstado('tarefa_id', getParam($_REQUEST, 'tarefa_id', null), $m, $a, $u);
$tarefa_id = $Aplic->getEstado('tarefa_id', null, $m, $a, $u);

if (isset($_REQUEST['baseline_id'])) $Aplic->setEstado('baseline_id', getParam($_REQUEST, 'baseline_id', null));
$baseline_id = ($Aplic->getEstado('baseline_id') !== null ? $Aplic->getEstado('baseline_id') : null);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('tab', getParam($_REQUEST, 'tab', null), $m, $a, $u);
$tab = $Aplic->getEstado('tab', 0, $m, $a, $u);



$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = "tarefa"');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();







$sql->adTabela('demanda_config');
$sql->adCampo('demanda_config.*');
$linha = $sql->linha();
$sql->limpar();
if ($baseline_id){
	$sql->adTabela('baseline');
	$sql->adCampo('baseline_data');
	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$hoje=$sql->resultado();
	$sql->limpar();
	}
else $hoje=date('Y-m-d H:i:s');


$log_id = intval(getParam($_REQUEST, 'log_id', 0));
$lembrar = intval(getParam($_REQUEST, 'lembrar', 0));
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$tarefaStatus = getSisValor('StatusTarefa');
$social=$Aplic->modulo_ativo('social');
$paises = getPais('Paises');



$sql->adTabela('municipios_coordenadas');
$sql->adCampo('count(municipio_id)');
$tem_coordenadas=$sql->resultado();
$sql->limpar();

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();


$tarefa_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$msg = '';
$obj = new CTarefa(($baseline_id ? true : false), true);
$obj->load($tarefa_id);

//tarefa n�o existe mais
if(!$obj->tarefa_id){
	$Aplic->redirecionar('m=publico&a=nao_existe&campo='.$config['tarefa'].'&masculino='.$config['genero_tarefa']);
	}

$projeto_id=$obj->tarefa_projeto;

$sql->adTabela('baseline');
$sql->adCampo('baseline_id, concatenar_tres(formatar_data(baseline_data, "%d/%m/%Y %H:%i"), \' - \', baseline_nome) AS nome');
$sql->adOnde('baseline_projeto_id = '.(int)$projeto_id);
$baselines=array(0=>'')+$sql->listaVetorChave('baseline_id','nome');
$sql->limpar();

$codigoPermissaoTarefa=$obj->podeEditar();
$editar = $codigoPermissaoTarefa ? true : false;
$podeEditarTudo = permiteEditarTudoTarefa(true, $obj->tarefa_acesso, $codigoPermissaoTarefa);

if (!$obj) {
	$Aplic->setMsg('Tarefa');
	$Aplic->setMsg('informa��es erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=tarefas');
	}
else $Aplic->salvarPosicao();

if (!$obj->podeAcessar($Aplic->usuario_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if ($lembrar) $obj->limparLembrete();


$data_inicio = intval($obj->tarefa_inicio) ? new CData($obj->tarefa_inicio) : null;
$data_fim = intval($obj->tarefa_fim) ? new CData($obj->tarefa_fim) : null;

$sql->adTabela('projetos');
$sql->adCampo('projeto_acesso, projeto_portfolio, projeto_aprova_registro');
$sql->adOnde('projeto_id='.(int)$projeto_id);
$projeto=$sql->linha();
$sql->limpar();

$podeAcessarProjeto = permiteAcessar($projeto['projeto_acesso'], $projeto_id);
//$podeEditarProjeto = permiteEditar($projeto['projeto_acesso'], $projeto_id);

//$podeEditarTudo=($obj->tarefa_acesso>=5 ? $editar && (in_array($obj->tarefa_dono, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

$sql->limpar();
$tipoDuracao = getSisValor('TipoDuracaoTarefa');


if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'tarefa.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");

	if ($podeAcessarProjeto) $km->Add("ver","ver_projeto",dica('Ver '.ucfirst($config['projeto']), 'Visualizar os detalhes '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");


	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de op��es').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Criar uma nov'.$config['genero_tarefa'].' '.$config['tarefa'].' '.($config['genero_projeto']=='o' ? 'neste' : 'nesta').' '.$config['projeto'].'.').ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_projeto=".$projeto_id."\");");
	
	if ($editar && ($podeEditar || $Aplic->checarModulo('log', 'adicionar'))) $km->Add("inserir","inserir_registro",dica('Registro de Ocorr�ncia','Inserir um novo registro de ocorr�ncia.').'Registro de ocorr�ncia'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=ver_log_atualizar&tarefa_id=".$tarefa_id.'&projeto_id='.$obj->tarefa_projeto."\");");
	
	
	if ($podeEditar && $editar) {
			
			if ($Aplic->profissional) {
				$km->Add("inserir","inserir_planilha_custo",dica('Planilha de Custos', 'Visualizar e editar a planilha de previs�o de custos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=estimado_pro&tarefa_id=".$obj->tarefa_id."\");");
				$km->Add("inserir","inserir_planilha_gasto",dica('Planilha de Gastos', 'Visualizar e editar a planilha de gastos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Gastos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=gasto_pro&tarefa_id=".$obj->tarefa_id."\");");
				$km->Add("inserir","inserir_gasto_mo",dica('Gasto de M�o de Obra','Acesse interface onde ser� poss�vel inserir per�odos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos designados.').'Gasto de M�o de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
				$km->Add("inserir","inserir_recurso",dica('Alocar Recurso', 'Alocar recurso '.($config['genero_tarefa']=='o' ? 'neste' : 'nesta').' '.$config['tarefa'].'.').'Alocar Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=recurso_alocar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
				$km->Add("inserir","inserir_gasto_recurso",dica('Gasto com Recurso','Acesse interface onde ser� poss�vel inserir per�odos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos recursos.').'Gasto com Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=recurso_ponto_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
				}
			else {
				$km->Add("inserir","inserir_planilha_custo",dica('Planilha de Custos', 'Visualizar e editar a planilha de previs�o de custos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=estimado&tarefa_id=".$obj->tarefa_id."\");");
				$km->Add("inserir","inserir_planilha_gasto",dica('Planilha de Gastos', 'Visualizar e editar a planilha de gastos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Gastos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=gasto&tarefa_id=".$obj->tarefa_id."\");");
				$km->Add("inserir","inserir_recurso",dica('Alocar Recurso', 'Alocar recurso '.($config['genero_tarefa']=='o' ? 'neste' : 'nesta').' '.$config['tarefa'].'.').'Alocar Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=recurso_alocar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
				}
			
			//if ($custo_total) $km->Add("inserir","inserir_pagamento",dica('Pagamento', 'inseri um pagamento relacionado com custos '.($config['genero_tarefa']=='o' ? 'deste' : 'desta').' '.$config['tarefa'].'.').'Pagamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=pagamento_editar_pro&pagamento_projeto=".$projeto_id."&pagamento_tarefa=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_expediente",dica('Editar Expediente', 'Editar expediente relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Expediente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=jornada_editar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_tarefa=".$tarefa_id."\");");	
			if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reuni�o', 'Inserir uma nova ata de reuni�o relacionada.').'Ata de reuni�o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_tarefa=".$tarefa_id."\");");
			
			if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_tarefa=".$tarefa_id."\");");
			if ($config['doc_interno'] && $Aplic->checarModulo('email', 'adicionar', $Aplic->usuario_id, 'criar_modelo')){
				$sql->adTabela('modelos_tipo');
				$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
				$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, imagem');
				$sql->adOnde('organizacao='.(int)$config['militar']);
				$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
				$modelos = $sql->Lista();
				$sql->limpar();
				if (count($modelos)){
					$km->Add("inserir","criar_documentos","Documento");
					foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_tarefa=".$tarefa_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
					}
				}

			$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
			if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo F�rum', 'Inserir um novo f�rum relacionado.').'F�rum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_tarefa=".$tarefa_id."\");");
				$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_tarefa=".$tarefa_id."\");");
				}
			if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_tarefa=".$tarefa_id."\");");

			//if ($Aplic->checarModulo('agenda', 'adicionar', null, null)) $km->Add("inserir","inserir_calendario",dica('Novo Agenda', 'Inserir uma nova agenda relacionada.').'Agenda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=calendario_editar&calendario_tarefa=".$tarefa_id."\");");

			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avalia��o', 'Inserir uma nova avalia��o relacionada.').'Avalia��o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_tarefa=".$tarefa_id."\");");
			$km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_tarefa=".$tarefa_id."\");");
			if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Od�metro de Indicador', 'Inserir um novo od�metro de indicador relacionado.').'Od�metro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composi��o de Pain�is', 'Inserir uma nova composi��o de pain�is relacionada.').'Composi��o de pain�is'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composi��es', 'Inserir um novo slideshow de composi��es relacionado.').'Slideshow de composi��es'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_tarefa=".$tarefa_id."\");");
			
			$km->Add("inserir","gestao",dica('Gestao','Menu de objetos de gest�o').'Gestao'.dicaF(), "javascript: void(0);'");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_tarefa=".$tarefa_id."\");");
			if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('gestao', 'adicionar', null, 'me')) $km->Add("gestao","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_tarefa=".$tarefa_id."\");");
			if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_tarefa=".$tarefa_id."\");"); 
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_tarefa=".$tarefa_id."\");");
			if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_tarefa=".$tarefa_id."\");");
			}	

		if ($config['anexo_eb']){
			$km->Add("ver","negapeb",dica(ucfirst($config['anexo_eb_nome']),'Visualizar '.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['anexo_eb_nome']).dicaF(), "javascript: void(0);");
			if ($linha['demanda_config_ativo_diretriz_iniciacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_diretriz_iniciacao']),ucfirst($linha['demanda_config_diretriz_iniciacao']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_diretriz_iniciacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=iniciacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_estudo_viabilidade']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_estudo_viabilidade']),ucfirst($linha['demanda_config_estudo_viabilidade']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_estudo_viabilidade']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=viabilidade_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_diretriz_implantacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_diretriz_implantacao']),ucfirst($linha['demanda_config_diretriz_implantacao']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_diretriz_implantacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=implantacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_declaracao_escopo']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_declaracao_escopo']),ucfirst($linha['demanda_config_declaracao_escopo']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_declaracao_escopo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=escopo_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_estrutura_analitica']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_estrutura_analitica']),ucfirst($linha['demanda_config_estrutura_analitica']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_estrutura_analitica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=estrutura_analitica_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_dicionario_eap']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_dicionario_eap']),ucfirst($linha['demanda_config_dicionario_eap']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_dicionario_eap']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=dicionario_eap_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_cronograma_fisico']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_cronograma_fisico']),ucfirst($linha['demanda_config_cronograma_fisico']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_cronograma_fisico']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=cronograma_financeiro_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_plano_projeto']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_plano_projeto']),ucfirst($linha['demanda_config_plano_projeto']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_plano_projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=plano_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_cronograma']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_cronograma']),ucfirst($linha['demanda_config_cronograma']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_cronograma']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=cronograma_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_planejamento_custo']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_planejamento_custo']),ucfirst($linha['demanda_config_planejamento_custo']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_planejamento_custo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=custo_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_humanos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_humanos']),ucfirst($linha['demanda_config_gerenciamento_humanos']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_humanos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=humano_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_comunicacoes']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_comunicacoes']),ucfirst($linha['demanda_config_gerenciamento_comunicacoes']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_comunicacoes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=comunicacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_partes']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_partes']),ucfirst($linha['demanda_config_gerenciamento_partes']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_partes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=interessado_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_riscos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_riscos']),ucfirst($linha['demanda_config_gerenciamento_riscos']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_riscos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=risco_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_qualidade']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_qualidade']),ucfirst($linha['demanda_config_gerenciamento_qualidade']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_qualidade']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=qualidade_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_mudanca']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_mudanca']),ucfirst($linha['demanda_config_gerenciamento_mudanca']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_mudanca']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=mudanca_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_controle_mudanca']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_controle_mudanca']),ucfirst($linha['demanda_config_controle_mudanca']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_controle_mudanca']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=mudanca_controle_lista&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_aceite_produtos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_aceite_produtos']),ucfirst($linha['demanda_config_aceite_produtos']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_aceite_produtos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=aceite_lista&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_relatorio_situacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_relatorio_situacao']),ucfirst($linha['demanda_config_relatorio_situacao']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_relatorio_situacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=situacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_termo_encerramento']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_termo_encerramento']),ucfirst($linha['demanda_config_termo_encerramento']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_termo_encerramento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=encerramento_ver&projeto_id=".$projeto_id."\");");
	
			$km->Add("negapeb","eb_status",dica('Status dos Documentos','Visualizar o status dos documento d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Status dos documentos'.dicaF(), "javascript: void(0);' onclick='status_pro()");
			}
		if ($config['anexo_civil']) $km->Add("ver","artefatos", dica(ucfirst($config['artefatos']),'Visualizar '.$config['genero_artefato'].'s '.$config['artefatos'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['artefatos']).dicaF(), "javascript: void(0);' onclick='menu_anexos()");
		if ($Aplic->checarModulo('tarefas', 'adicionar')){
			$km->Add("ver","ver_eap",dica('Estrutura Anal�tica do Projeto - Work Breakdown Structure','Visualizar a estrutura anal�ticas do projeto.<br>� uma ferramenta de decomposi��o do trabalho d'.$config['genero_projeto'].' '.$config['projeto'].' em partes manej�veis. � estruturada em �rvore exaustiva, hier�rquica (de mais geral para mais espec�fica) orientada �s entregas que precisam ser feitas para completar '.($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'].'.').'EAP (WBS)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical&jquery=1&projeto_id=".$projeto_id."\");");
			$km->Add("ver","ver_rapido",dica('Gantt Interativo','Exibir interface de cria��o e edi��o de '.$config['projetos'],' utilizando gr�fico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo&projeto_id=".$projeto_id."\");");
			}
		if ($Aplic->checarModulo('relatorios', 'acesso')) $km->Add("ver","ver_relatorios",dica('Relat�rios','Visualizar a lista de relat�rios.<br><br>Os relat�rios s�o modos convenientes de se ter uma vis�o panor�mica de como as divers'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].' est�o se desenvolvendo.').'Relat�rios'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index&projeto_id=".$projeto_id."\");");
		$km->Add("ver","ver_grafico",dica('Gr�ficos','Visualizar a ferramenta de gr�ficos customizados.').'Gr�ficos'.dicaF(), "javascript: void(0);' onclick='parent.gpwebApp.graficosProjeto(".$projeto_id.",".(isset($baseline_id) ? $baseline_id: 0).",\"".nome_projeto($projeto_id)."\");");
		$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
		
		if ($podeEditarTudo && $podeEditar)$km->Add("acao","acao_editar",dica('Editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.ucfirst($config['tarefa']),'Editar os detalhes '.($config['genero_tarefa']=='o' ? 'deste' : 'desta').' '.$config['tarefa'].'.').'Editar '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_id=".$obj->tarefa_id."\");");
		if ($podeEditarTudo && $podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']),'Excluir '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' do sistema.<br><br>Todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' pertencentes a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' tamb�m ser�o exclu�das.').'Excluir '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='excluir()");
		if ($Aplic->profissional && $podeEditarTudo && $podeEditar) {	
			$km->Add("acao","acao_aprovar_custo_recurso",dica('Aprovar Aloca��o de Recurso','Acesse interface onde ser� poss�vel aprovar per�odos alocados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos recursos.').'Aprovar Aloca��o de Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_recurso_custo_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("acao","acao_aprovar_mo",dica('Aprovar Gasto de M�o de Obra','Acesse interface onde ser� poss�vel aprovar per�odos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrados.').'Aprovar Gasto com M�o de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_gasto_mo_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("acao","acao_aprovar_recurso",dica('Aprovar Gasto com Recurso','Acesse interface onde ser� poss�vel aprovar per�odos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrados.').'Aprovar Gasto com Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_recurso_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("acao","acao_aprovar_custo",dica('Aprovar Planilha de Custo','Acesse interface onde ser� poss�vel aprovar a planilha de custo d'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrada.').'Aprovar Planilha de Custo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_custos_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("acao","acao_aprovar_gasto",dica('Aprovar Planilha de Gasto','Acesse interface onde ser� poss�vel aprovar a planilha de gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrada.').'Aprovar Planilha de Gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_gastos_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			if ($projeto['projeto_aprova_registro']) $km->Add("acao","acao_aprovar_registro",dica('Aprovar Registro de Ocorr�ncia','Acesse interface onde ser� poss�vel aprovar os registros de ocorr�ncias d'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente cadastrados.').'Aprovar Registro de Ocorr�ncia'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_registros_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("acao","financeiro",dica('Definir Est�gios da Despesa','Defina empenhado, liquidado e pago nos gastos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Definir Est�gios da Despesa'.dicaF(), "javascript: void(0);");
			$km->Add("financeiro","financeiro_planilha",dica('Planilha de Gasto','Acesse interface onde ser� poss�vel colocar as planilhas de gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].' como empenhado, liquidado ou pago.').'Planilha de Gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_planilha_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("financeiro","financeiro_recurso",dica('Recursos','Acesse interface onde ser� poss�vel colocar os gastos com recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].' como empenhado, liquidado ou pago.').' Gasto com Recursos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_recurso_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("financeiro","financeiro_mo",dica('M�o de Obra','Acesse interface onde ser� poss�vel colocar os gastos com m�o de obra d'.$config['genero_tarefa'].' '.$config['tarefa'].' como empenhado, liquidado ou pago.').'Gasto com M�o de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_mo_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			}
			
			
		$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['projetos']),'Visualizar a lista de '.($config['genero_projeto']=='o' ? 'todos os' : 'todas as').' '.$config['projetos'].'.').'Lista de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos\");");
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir decumentos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir();");
		
		
		if ($Aplic->profissional) {
			if ($podeEditarTudo && $podeEditar) {
				$km->Add("acao","exportar_link",dica('Exportar Link','Endere�o web para visualiza��o em ambiente externo dados d'.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Exportar Link'.dicaF(), "javascript: void(0);");
				$km->Add("exportar_link","exportar_gantt",dica('Gantt','Endere�o web para visualiza��o em ambiente externo do gr�fico Gantt d'.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Gantt'.dicaF(), "javascript: void(0);' onclick='exportar_link(\"tarefa_gantt\");");
				$km->Add("exportar_link","exportar_dashboard",dica('Dashboard Geral','Endere�o web para visualiza��o em ambiente externo o dashboard geral d'.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Dashboard geral'.dicaF(), "javascript: void(0);' onclick='exportar_link(\"tarefa_dashboard\");");
				$km->Add("exportar_link","exportar_detalhes",dica('Detalhes d'.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.ucfirst($config['tarefa']),'Endere�o web para visualiza��o em ambiente externo o detalhamento d'.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Detalhes d'.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='exportar_link(\"tarefa_ver\");");
				}
			$km->Add("acao","dashboard",dica('Dashboard','Dash Board d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Dashboard'.dicaF(), "javascript: void(0);");
			$km->Add("dashboard","dashboard_geral",dica('Dashboard Geral','Dashboard Geral com as informa��es mais pertinentes d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Geral'.dicaF(), "javascript: void(0);' onclick='url_passar(\"dashboard_geral_".$projeto_id."\", \"m=projetos&a=deshboard_geral_pro&jquery=1&dialogo=1&tarefa_id=".$tarefa_id."\");");
			$km->Add("acao","acao_excel",dica('Exportar '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' em Formato Excel', 'Clique neste �cone '.imagem('icones/excel.png').' para exportar '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' no formato de planilha Excel.').imagem('icones/excel.png').'Exportar para Excel'.dicaF(), "javascript: void(0);' onclick='exportarProjetoExcel(".(isset($baseline_id) ? $baseline_id : '0').','.$tarefa_id.");");
			}
			
		
		
		
		
		$selecionar_baseline=(count($baselines)> 1 && $Aplic->profissional ? '<td align="right" style="white-space: nowrap;background-color: #e6e6e6">'.dica('Baseline', 'Escolha na caixa de op��o � direita a baseline que deseja visualizar.').'Baseline:'.dicaF().'</td><td style="white-space: nowrap;background-color: #e6e6e6">'.selecionaVetor($baselines, 'baseline_id', 'class="texto" style="width:200px;" size="1" onchange="mudar_baseline();"', $baseline_id).'</td>' : '');
		echo $km->Render();
		echo '</td>'.$selecionar_baseline.'</tr>';
		echo '</table>';
		}



echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_tarefa_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '</form>';


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
if (!$Aplic->profissional) echo '<input type="hidden" name="baseline_id" id="baseline_id" value="" />';
echo '</form>';


$cor_indicador=cor_indicador('tarefa', $tarefa_id);

$sql->adTabela('projetos');
$sql->adCampo('projeto_cor');
$sql->adOnde('projeto_id='.$obj->tarefa_projeto);
$projeto_cor=$sql->resultado();
$sql->limpar();



echo '<table cellpadding=0 cellspacing=0 width="100%"><tr><td style="border: outset #d1d1cd 1px;background-color:#'.$projeto_cor.'" colspan="2" onclick="if (document.getElementById(\'tblTarefa\').style.display){document.getElementById(\'tblTarefa\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'mostrar\').style.display=\'none\';} else {document.getElementById(\'tblTarefa\').style.display=\'none\'; document.getElementById(\'contrair\').style.display=\'none\'; document.getElementById(\'mostrar\').style.display=\'\';} if(window.resizeGrid) window.resizeGrid();"><a href="javascript: void(0);"><span id="mostrar" style="display:none">'.imagem('icones/mostrar.gif', 'Mostrar Detalhes', 'Clique neste �cone '.imagem('icones/mostrar.gif').' para mostrar os detalhes d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</span><span id="contrair">'.imagem('icones/contrair.gif', 'Ocultar Detalhes', 'Clique neste �cone '.imagem('icones/contrair.gif').' para ocultar os detalhes d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</span><font color="'.melhorCor($projeto_cor).'"><b>'.$obj->tarefa_nome.' </b>'.$cor_indicador.'</td></tr></table>';
echo '<table cellpadding=0 cellspacing=1 width="100%" class="std" id="tblTarefa">';
echo '<tr><td align="right" style="width:110px;">'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Tod'.$config['genero_tarefa'].' '.$config['tarefa'].' deve pertencer a um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td width="100%" class="realce">'.link_projeto($obj->tarefa_projeto).'</td></tr>';



if ($obj->tarefa_codigo) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('C�digo', 'O c�digo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'C�digo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_codigo.'</td></tr>';
if ($Aplic->profissional){
	if (isset($obj->tarefa_setor) && $obj->tarefa_setor) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
	if (isset($obj->tarefa_segmento) && $obj->tarefa_segmento) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
	if (isset($obj->tarefa_intervencao) && $obj->tarefa_intervencao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
	if (isset($obj->tarefa_tipo_intervencao) && $obj->tarefa_tipo_intervencao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';
	}

if ($obj->tarefa_superior != $obj->tarefa_id)	echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica(ucfirst($config['tarefa']).' Superior', ucfirst($config['tarefa']).' de quem � sub'.$config['tarefa'].'.').ucfirst($config['tarefa']).' superior:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_tarefa($obj->tarefa_superior).'</td></tr>';

if ($obj->tarefa_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'Mesmo que '.$config['genero_projeto'].' '.$config['projeto'].' seja em proveito de outr'.$config['genero_organizacao'].' '.$config['organizacao'].', deve-se selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que ser� encarregada de liderar '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td class="realce" style="text-align: justify;"> '.link_cia($obj->tarefa_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('tarefa_cia');
	$sql->adCampo('tarefa_cia_cia');
	$sql->adOnde('tarefa_cia_tarefa = '.(int)$tarefa_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].' com '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}


if ($obj->tarefa_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', ucfirst($config['genero_dept']).' '.$config['departamento'].' respons�vel.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_dept($obj->tarefa_dept).'</td></tr>';
$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_depts','tarefa_depts', ($baseline_id ? 'tarefa_depts.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('departamento_id');
$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
$depts = $sql->carregarColuna();
$sql->limpar();
$saida_depts='';
if (isset($depts) && count($depts)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_dept($depts[0]);
		$qnt_depts=count($depts);
		if ($qnt_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts[$i]).'<br>';
				$saida_depts.= dica('Outros '.ucfirst($config['departamentos']), 'Clique para visualizar os demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_depts\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';

		$plural=(count($depts)>1 ? 's' : '');
		}
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamento'.$plural]).' Envolvid'.$config['genero_dept'].$plural, ucfirst($config['departamento'.$plural]).' envolvid'.$config['genero_dept'].$plural.'  n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['departamento'.$plural]).' envolvid'.$config['genero_dept'].$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->tarefa_dono) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'], 'Tod'.$config['genero_tarefa'].' '.$config['tarefa'].' deve ter um respons�vel. O '.$config['usuario'].' respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].' dever�, preferencialmente, ser o encarregado de atualizar os dados no '.$config['gpweb'].', relativos a '.($config['genero_tarefa']=='a' ?  'sua' : 'seu').' '.$config['tarefa'].'.').'Respons�vel:'.dicaF().'</td><td class="realce" style="text-align: justify;"> '.link_usuario($obj->tarefa_dono,'','','esquerda').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.prioridade($obj->tarefa_prioridade, false, true).'</td></tr>';

if (isset($tarefaStatus[$obj->tarefa_status])) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Status', 'O status d'.$config['genero_tarefa'].' '.$config['tarefa'].' define a situa��o atual da mesma').'Status:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$tarefaStatus[$obj->tarefa_status].'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Marco', '<ul><li>O marco pode ser vislumbrados como data chave de t�rmino de um grupo de  '.$config['tarefas'].'.</li><li>No gr�fico Gantt ser� visualizado como um los�ngulo <font color="#FF0000">&loz;</font> vermelho.</li></ul>').'Marco:'.dicaF().'</td><td class="realce" width="300">'.($obj->tarefa_marco  ? 'Sim' : 'N�o').'</td></tr>';

if ($obj->tarefa_principal_indicador && $Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'O indicador mais representativo da situa��o geral d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->tarefa_principal_indicador).'</td></tr>';


//estranha a linha de baixo
//if (!$obj->tarefa_marco) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Tempo Trabalhado', 'O sistema calcula o n�mero de horas trabalhadas, baseado na percentagem concluida multiplicada pela carga hor�ria total.').'Tempo trabalhado:'.dicaF().'</td><td class="realce" width="300">'.($obj->tarefa_horas_trabalhadas + @rtrim($obj->log_horas_trabalhadas, '0')).'</td></tr>';
if (isset($tarefa_acesso[$obj->tarefa_acesso])) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Acesso', ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' podem ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar '.$config['genero_tarefa'].' '.$config['tarefa'].'.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons�vel e os participantes d'.$config['genero_tarefa'].' '.$config['tarefa'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante I</b> - Somente o respons�vel e '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' podem ver e editar a mesma.</li><li><b>Participantes II</b> - Somente o respons�vel e os designados podem ver e apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons�vel e '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' podem ver, e o respons�vel editar.</li></ul>',TRUE).'N�vel de acesso'.dicaF().'</td><td class="realce" width="300">'.$tarefa_acesso[$obj->tarefa_acesso].'</td></tr>';

if ($data_inicio) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Data de In�cio', 'Data prov�vel de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'In�cio:'.dicaF().'</td><td class="realce" width="300">'.$data_inicio->format('%d/%m/%Y %H:%M').'</td></tr>';
if ($data_fim) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Data de t�rmino', 'Data estimada de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'T�rmino:'.dicaF().'</td><td class="realce" width="300">'.$data_fim->format('%d/%m/%Y %H:%M').'</td></tr>';

if ($data_inicio && $data_fim && !$obj->tarefa_marco && $obj->tarefa_percentagem > 0 && $obj->tarefa_percentagem < 100){
	//Quantas horas desde  a data de in�cio da tarefa
	$horas_faltando=((100-$obj->tarefa_percentagem)/100)*$obj->tarefa_duracao;
	$data=calculo_data_final_periodo($hoje, $horas_faltando, $obj->tarefa_cia, null, $projeto_id, null, $tarefa_id);
	echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Previs�o de t�rmino calculada', 'Data estimada de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].' baseado na percentagem realizada at� o momento.').'Previs�o:'.dicaF().'</td><td class="realce" width="300">'.retorna_data($data).'</td></tr>';
	}

if ($obj->tarefa_duracao) echo '<tr><td align="right" valign="top" style="width:110px; white-space: nowrap;">'.dica('Dura��o', 'A dura��o de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].'.').'Dura��o:'.dicaF().'</td><td class="realce" width="300">'.number_format((float)$obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 2, ',', '.').' dia'.((float)$obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)  >= 2 ? 's' : '').'</td></tr>';
if ($obj->tarefa_tipo) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Categoria', 'A categoria d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Categoria:'.dicaF().'</td><td class="realce" width="300">'.getSisValorCampo('TipoTarefa',$obj->tarefa_tipo).'</td></tr>';
if ($obj->tarefa_emprego_obra) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Empregos Gerados Durante a Execu��o', 'O n�mero de empregos gerados durante a execu��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos durante a execu��o:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_emprego_obra.'</td></tr>';
if ($obj->tarefa_emprego_direto) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Empregos Diretos Ap�s a Conclus�o', 'On�mero de empregos diretos gerados ap�s a conclus�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos diretos ap�s conclus�o:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_emprego_direto.'</td></tr>';
if ($obj->tarefa_emprego_indireto) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Empregos Indiretos Ap�s a Conclus�o', 'On�mero de empregos indiretos gerados ap�s a conclus�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos indiretos ap�s conclus�o:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_emprego_indireto.'</td></tr>';
if ($obj->tarefa_forma_implantacao) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Forma de Implanta��o', 'A forma de implanta��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Forma de implanta��o:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_forma_implantacao.'</td></tr>';
if ($obj->tarefa_populacao_atendida) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Popula��o atendida', 'O tipo de popula��o atendida quando da conclus�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Popula��o atendida:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_populacao_atendida.'</td></tr>';
$unidade= getSisValor('TipoUnidade');
if ($obj->tarefa_adquirido!=0) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Quantidade Adquirida', 'A quantidade adquirida do item base para a execu��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade adquirida:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_adquirido, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
if ($obj->tarefa_previsto!=0) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Quantidade Prevista', 'A quantidade prevista a ser realizada baseado no tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade prevista:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_previsto, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
if ($obj->tarefa_realizado!=0) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Quantidade Realizada', 'A quantidade realizada baseado no tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade realizada:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_realizado, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
if ($obj->tarefa_url_relacionada) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Endere�o URL', 'O endere�o URL dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'. O endere�o URL normalmente estar� contido na Intranet para consulta pelo p�blico interno.').'Endere�o URL:'.dicaF().'</td><td class="realce" width="300"><a href="'.$obj->tarefa_url_relacionada.'" target="tarefa'.$tarefa_id.'">'.$obj->tarefa_url_relacionada.'</a></td></tr>';





if ($obj->tarefa_descricao)	echo '<tr><td align="right" style="white-space: nowrap" align="left" width="80">'.dica('O Que', 'Muito importante haver um breve resumo d'.$config['genero_tarefa'].' '.$config['tarefa'].', para servir de guia �s atividades sucessoras e auxiliar na compreens�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'O Que:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_descricao.'</td></tr>';
if ($obj->tarefa_porque)	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Por Que', 'Por que '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� desenvolvid'.$config['genero_tarefa'].'.').'Por Que:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_porque.'</td></tr>';
if ($obj->tarefa_como)	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Como', 'Como '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� desenvolvid'.$config['genero_tarefa'].'.').'Como:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_como.'</td></tr>';
if ($obj->tarefa_onde)	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Onde', 'Onde '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� desenvolvid'.$config['genero_tarefa'].'.').'Onde:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_onde.'</td></tr>';
if ($obj->tarefa_situacao_atual)	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Situa��o Atual', 'Situa��o atual d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Situa��o Atual:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_situacao_atual.'</td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_designados', 'tarefa_designados', ($baseline_id ? 'tarefa_designados.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('usuario_id, perc_designado');
$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
$lista_designados = $sql->listaVetorChave('usuario_id', 'perc_designado');


$saida_designados='';
if (isset($lista_designados) && count($lista_designados)) {
	$designados=array();
	foreach($lista_designados as $chave => $valor) $designados[]=array('usuario_id'=> $chave, 'perc_designado'=> $valor);
		$saida_designados.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_designados.= '<tr><td>'.link_usuario($designados[0]['usuario_id'],'','','esquerda').' - '.number_format($designados[0]['perc_designado'], 2, ',', '.').'%';
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'],'','','esquerda').' - '.number_format($designados[$i]['perc_designado'], 2, ',', '.').'%<br>';
				$saida_designados.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_designados"><br>'.$lista.'</span>';
				}
		$saida_designados.= '</td></tr></table>';

		$plural=(count($designados)>1 ? 's' : '');
		}

if ($saida_designados) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designado'.$plural, 'Designado'.$plural.' para '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Designado'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_designados.'</td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_dependencias','tarefa_dependencias', ($baseline_id ? 'tarefa_dependencias.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('dependencias_req_tarefa_id');
$sql->adOnde('dependencias_tarefa_id = '.(int)$tarefa_id);
$dependencias_tarefas = $sql->listaVetorChave('dependencias_req_tarefa_id','dependencias_req_tarefa_id');
$sql->limpar();


if (count($dependencias_tarefas)>1) echo '<tr><td align="right">'.dica('Predecessoras', ucfirst($config['tarefa']).' que necessitam serem cumpridas para que est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' seja executad'.$config['genero_tarefa'].'.').'Predecessoras:'.dicaF().'</td>';
else echo '<tr><td align="right">'.dica('Predecessora', ucfirst($config['tarefa']).' que necessita ser cumprida para que est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' seja executad'.$config['genero_tarefa'].'.').'Predecessora:'.dicaF().'</td>';
if (count($dependencias_tarefas)){
	$contar=0;
	echo '<td class="realce" style="text-align: justify;">';
	foreach ($dependencias_tarefas as $chave => $valor) echo ($contar++ ? '<br>' : '').link_tarefa($valor);
	}
else 	echo '<td class="realce" style="text-align: justify;">nenhuma';
echo '</td></tr>';


$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_dependencias','tarefa_dependencias', ($baseline_id ? 'tarefa_dependencias.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('dependencias_tarefa_id');
$sql->adOnde('dependencias_req_tarefa_id = '.(int)$tarefa_id);
$tarefas_dependentes = $sql->listaVetorChave('dependencias_tarefa_id','dependencias_tarefa_id');
$sql->limpar();

if (count($tarefas_dependentes)>1) echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Sucessoras ', 'Lista de todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' que tenham est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' como predecessor'.$config['genero_tarefa'].'.').'Sucessoras:'.dicaF().'</td>';
else echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Sucessora ', ucfirst($config['tarefa']).'  que tenha est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' como predecessor'.$config['genero_tarefa'].'.').'Sucessora:'.dicaF().'</td>';
if (count($tarefas_dependentes)){
	$contar=0;
	echo '<td class="realce" style="text-align: justify;">';
	foreach ($tarefas_dependentes as $chave => $valor) echo ($contar++ ? '<br>' : '').link_tarefa($valor);
	}
else 	echo '<td class="realce" style="text-align: justify;">nenhuma';
echo '</td></tr>';


if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_contatos','tarefa_contatos', ($baseline_id ? 'tarefa_contatos.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('contato_id');
	$sql->adOnde('tarefa_id = '.(int)$obj->tarefa_id);
	$contatos = $sql->carregarColuna();
	$sql->limpar();

	$saida_contatos='';
	if (isset($contatos) && count($contatos)) {
			$saida_contatos.= '<table cellspacing=0 cellpadding=0 width="100%">';
			$saida_contatos.= '<tr><td>'.link_contato($contatos[0],'','','esquerda');
			$qnt_contatos=count($contatos);
			if ($qnt_contatos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos[$i],'','','esquerda').'<br>';
					$saida_contatos.= dica('Outros Contatos', 'Clique para visualizar os demais contatos.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_contatos\');">(+'.($qnt_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';

			$plural=(count($contatos)>1 ? 's' : '');
			}

	if ($saida_contatos) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Contato'.$plural, 'Contato'.$plural.' d'.$config['genero_tarefa'].' '.$config['tarefa'].'. No caso de inser��o de dados n'.$config['genero_tarefa'].' '.$config['tarefa'].' o'.$plural.' contato'.$plural.' '.($plural ? 'poder�o' : 'poder�').' ser informado'.$plural.' automaticamente por e-mail.').'Contato'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_contatos.'</td></tr>';
	}

if ($social){
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas', ($baseline_id ? 'tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->esqUnir('social_comunidade', 'social_comunidade', 'tarefa_comunidade=social_comunidade_id');
	$sql->esqUnir('social', 'social', 'tarefa_social=social_id');
	$sql->esqUnir('social_acao', 'social_acao', 'tarefa_acao=social_acao_id');
	$sql->adOnde('tarefas.tarefa_id = '.(int)$obj->tarefa_id);
	$sql->adCampo('social_acao_nome, social_nome, social_comunidade_nome');
	$linha = $sql->Linha();
	$sql->limpar();

	if ($linha['social_nome'])	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Programa Social', 'A qual programa social pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Programa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['social_nome'].'</td></tr>';
	if ($linha['social_acao_nome'])	echo '<tr><td align="right" style="white-space: nowrap">'.dica('A��o Social', 'Escolha a a��o social d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'A��o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['social_acao_nome'].'</td></tr>';
	if ($linha['social_comunidade_nome'])	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Comunidade', 'A comunidade onde se aplica '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Comunidade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['social_comunidade_nome'].'</td></tr>';
	}

if ($obj->tarefa_endereco1) echo '<tr valign="top"><td align="right" style="white-space: nowrap">'.dica('Endere�o', 'O ender�o d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora n�o tenha impacto no funcionamento do Sistema, facilita a organiza��o e na eventual necessidade de entrar em contato.').'Endere�o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique esta imagem para visualizar no Google Maps, aberto em uma nova janela, o endere�o d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'<a href="'.get_protocol().'maps.google.com/maps?key=AIzaSyAsFbkGMNJdcsHBSQySo8jpA7zqBhlg1Pg&q='.utf8_encode($obj->tarefa_endereco1).'+'.utf8_encode($obj->tarefa_endereco2).'+'.utf8_encode(municipio_nome($obj->tarefa_cidade)).'+'.utf8_encode($obj->tarefa_estado).'+'.$obj->tarefa_cep.'+'.utf8_encode($obj->tarefa_pais).'" target="_blank"><img align="right" src="'.acharImagem('google_map.png').'" alt="Achar no Google Maps" /></a>'.dicaF().$obj->tarefa_endereco1.(($obj->tarefa_endereco2) ? '<br />'.$obj->tarefa_endereco2 : '') .($obj->tarefa_cidade || $obj->tarefa_estado || $obj->tarefa_pais ? '<br>' : '').municipio_nome($obj->tarefa_cidade).($obj->tarefa_estado ? ' - ' : '').$obj->tarefa_estado.($obj->tarefa_pais ? ' - '.$paises[$obj->tarefa_pais] : '').(($obj->tarefa_cep) ? '<br />'.$obj->tarefa_cep : '').'</td></tr>';
if ($obj->tarefa_latitude && $obj->tarefa_longitude) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Coordenadas Geogr�ficas', 'As coordenadas geogr�ficas da localiza��o d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Coordenadas:'.dicaF().'</td><td class="realce" width="100%">'.$obj->tarefa_latitude.'� '.$obj->tarefa_longitude.'�&nbsp;<a href="javascript: void(0);" onclick="popCoordenadas('.$obj->tarefa_latitude.','.$obj->tarefa_longitude.',0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Coordenadas', 'Clique neste �cone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa as coordenadas geogr�ficas.').'</a></td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'municipio_lista','municipio_lista', ($baseline_id ? 'municipio_lista.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=municipio_lista.municipio_lista_municipio');
$sql->adCampo('DISTINCT municipios.municipio_id, municipio_nome, estado_sigla');
$sql->adOnde('municipio_lista_tarefa = '.(int)$obj->tarefa_id);
$sql->adOrdem('estado_sigla, municipio_nome');
$lista_municipios = $sql->Lista();
$sql->limpar();

$plural_municipio=(count($lista_municipios)>1 ? 's' : '');

$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_area','projeto_area', ($baseline_id ? 'projeto_area.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('projeto_area_id, projeto_area_nome, projeto_area_obs');
$sql->adOnde('projeto_area_tarefa IN ('.($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id).')');
$sql->adOrdem('projeto_area_nome ASC');
$lista_areas = $sql->Lista();
$sql->limpar();

$saida_areas='';
$todas_areas='';
if (isset($lista_areas) && count($lista_areas)) {
	$plural=(count($lista_areas)>1 ? 's' : '');
	$saida_areas.= '<table cellspacing=0 cellpadding=0 width="100%">';
	$saida_areas.= '<tr><td><a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$lista_areas[0]['projeto_area_id'].');">'.dica('Visualizar �rea ou Ponto', 'Clique neste �cone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a �rea ou ponto.'.($lista_areas[0]['projeto_area_obs'] ? '<br>'.$lista_areas[0]['projeto_area_obs'] : '')).imagem('icones/coordenadas_p.png').$lista_areas[0]['projeto_area_nome'].dicaF().'</a>';
	$qnt_lista_areas=count($lista_areas);
	if ($qnt_lista_areas > 1) {
		$lista='';
		for ($i = 1, $i_cmp = $qnt_lista_areas; $i < $i_cmp; $i++) $lista.=dica('Visualizar �rea ou Ponto', 'Clique neste �cone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a �rea ou ponto.'.($lista_areas[0]['projeto_area_obs'] ? '<br>'.$lista_areas[$i]['projeto_area_obs'] : '')).'<a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$lista_areas[$i]['projeto_area_id'].');">'.imagem('icones/coordenadas_p.png').$lista_areas[$i]['projeto_area_nome'].'</a>'.dicaF().'<br>';
		$saida_areas.= dica('Outras �reas', 'Clique para visualizar as demais �reas.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_areas\');">(+'.($qnt_lista_areas - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_areas"><br>'.$lista.'</span>';
		$todas_areas=dica('Visualizar Todas as �reas', 'Clique neste �cone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa todas as �reas.').'<a href="javascript: void(0);" onclick="popCoordenadas(0,0,0,'.$projeto_id.','.$tarefa_id.');">'.imagem('icones/coordenadas_p.png').'Todas as �reas</a>'.dicaF();
		}
	$saida_areas.= '</td></tr></table>';
	}
$plural=(count($lista_areas)>1 ? 's' : '');
if ($saida_areas || (count($lista_municipios) && $tem_coordenadas)) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('�rea'.$plural, '�rea'.$plural.' relacionada'.$plural.' com '.$config['genero_tarefa'].' '.$config['tarefa']).'�rea'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce"><table cellspacing=0 cellpadding=0><tr><td>'.$saida_areas.$todas_areas.(count($lista_municipios) && $tem_coordenadas ? '&nbsp;&nbsp;&nbsp;'.dica('�rea'.$plural_municipio.' do'.$plural_municipio.' Munic�pio'.$plural_municipio, 'Visualizar a �rea do'.$plural_municipio.' munic�pio'.$plural_municipio.'.').'Munic�pio'.$plural_municipio.'<a href="javascript: void(0);" onclick="popAreaMunicipio(0,0,'.$tarefa_id.',0);">'.imagem('icones/coordenadas_p.png', '�rea'.$plural_municipio.' do'.$plural_municipio.' Munic�pio'.$plural_municipio, 'Clique neste �cone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a'.$plural_municipio.' �rea'.$plural_municipio.' do'.$plural_municipio.' munic�pio'.$plural_municipio.' inclu�do'.$plural_municipio.' neste '.($config['genero_projeto']=='a' ? 'nesta': 'neste').' '.$config['projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';


$saida_municipios='';
if (isset($lista_municipios) && count($lista_municipios)) {
	$saida_municipios.= '<table cellspacing=0 cellpadding=0 width="100%">';
	$saida_municipios.= '<tr><td>'.$lista_municipios[0]['municipio_nome'].'-'.$lista_municipios[0]['estado_sigla'].($tem_coordenadas ? '<a href="javascript: void(0);" onclick="popAreaMunicipio('.$lista_municipios[0]['municipio_id'].',0,0);">'.imagem('icones/coordenadas_p.png', 'Visualizar �rea do Munic�pio', 'Clique neste �cone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a �rea do munic�pio.').'</a>' : '');
	$qnt_lista_municipios=count($lista_municipios);
	if ($qnt_lista_municipios > 1) {
		$lista='';
		for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=$lista_municipios[$i]['municipio_nome'].'-'.$lista_municipios[$i]['estado_sigla'].'<br>';
		$saida_municipios.= dica('Outros Munic�pios', 'Clique para visualizar os demais munic�pios.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
		}
	$saida_municipios.= '</td></tr></table>';
	}

if ($saida_municipios) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Munic�pio'.$plural_municipio, 'Munic�pio'.$plural_municipio.' relacionado'.$plural_municipio.' com '.$config['genero_tarefa'].' '.$config['tarefa']).'Munic�pio'.$plural_municipio.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_municipios.'</td></tr>';











if ($Aplic->profissional && $exibir['bioma']){
	$sql->adTabela('tarefa_bioma');
	$sql->esqUnir('sisvalores', 'sisvalores', 'sisvalor_valor_id=tarefa_bioma_bioma');
	$sql->adOnde('tarefa_bioma_tarefa = '.(int)$tarefa_id);
	$sql->adOnde('sisvalor_titulo = \'tarefa_bioma\'');
	$sql->adCampo('DISTINCT sisvalor_valor');
	$sql->adOrdem('tarefa_bioma_ordem');
	$sql->adGrupo('tarefa_bioma_bioma');
	$biomas=$sql->carregarColuna();
	$sql->limpar();

	if (count($biomas)) echo '<tr><td align="right">'.dica(ucfirst($config['tarefa_bioma']), ucfirst($config['genero_tarefa_bioma']).'s '.$config['tarefa_biomas'].' relacionad'.$config['genero_tarefa_bioma'].'s com '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa_bioma']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $biomas).'</td></tr>';
	}


if ($Aplic->profissional && $exibir['comunidade']){
	$sql->adTabela('tarefa_comunidade');
	$sql->esqUnir('sisvalores', 'sisvalores', 'sisvalor_valor_id=tarefa_comunidade_comunidade');
	$sql->adOnde('tarefa_comunidade_tarefa = '.(int)$tarefa_id);
	$sql->adOnde('sisvalor_titulo = \'tarefa_comunidade\'');
	$sql->adCampo('DISTINCT sisvalor_valor');
	$sql->adOrdem('tarefa_comunidade_ordem');
	$sql->adGrupo('tarefa_comunidade_comunidade');
	$comunidades=$sql->carregarColuna();
	$sql->limpar();

	if (count($comunidades)) echo '<tr><td align="right">'.dica(ucfirst($config['tarefa_comunidade']), ucfirst($config['genero_tarefa_comunidade']).'s '.$config['tarefa_comunidades'].' relacionad'.$config['genero_tarefa_comunidade'].'s com '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa_comunidade']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $comunidades).'</td></tr>';
	}














if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alerta Ativo', 'Caso esteja marcado '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� inclu�d'.$config['genero_tarefa'].' no sistema de alertas autom�ticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.($obj->tarefa_alerta ? 'Sim' : 'N�o').'</td></tr>';

require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados($m, $obj->tarefa_id, 'ver');

if ($campos_customizados->count()) {
	echo '<tr><td colspan="2">'.$campos_customizados->imprimirHTML().'</td></tr>';
	}

if (!$obj->tarefa_marco) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('F�sico executado', ucfirst($config['genero_tarefa']).' '.$config['tarefa'].' pode ir de 0% (n�o iniciadas) at� 100% (completadas).</p> No gr�fico Gantt o progresso ser� visualizado como uma linha escura dentro do bloco horizontal d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'F�sico executado:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_percentagem, 2, ',', '.').'%</td></tr>';
if ($Aplic->profissional && !$obj->tarefa_marco){
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('F�sico Planejado', 'O percentual d'.$config['genero_tarefa'].' '.$config['tarefa'].' previsto para a data atual.').'F�sico planejado:'.dicaF().'</td><td class="realce" width="100%">'.number_format($obj->fisico_previsto($hoje, true, $baseline_id), 2, ',', '.').'%</td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Velocidade do F�sico', 'O raz�o entre o progresso e f�sico previsto d'.$config['genero_projeto'].' '.$config['projeto'].' para a data atual.').'Velocidade do f�sico:'.dicaF().'</td><td class="realce" width="100%">'.number_format($obj->fisico_velocidade($hoje, true, $baseline_id), 2, ',', '.').'</td></tr>';
	}

echo '<tr><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="exibir_financeiro();"><a href="javascript: void(0);" class="aba"><b>Financeiro</b></a></td></tr>';
echo '<tr><td colspan="3"><table width="100%" cellspacing=0 cellpadding=0 id="ver_financeiro" style="display:none"><tr><td><div id="combo_financeiro">';
echo '</td></tr></div></table></td></tr></table></td></tr>';

echo '</table></td></tr></table>';


if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';


$caixaTab = new CTabBox('m=tarefas&a=ver&tarefa_id='.$tarefa_id, '', $tab);

$qnt_aba=0;


if ($Aplic->checarModulo('log', 'acesso')) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'log','log', ($baseline_id ? 'log.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('count(log_id)');
	$sql->adOnde('log_tarefa = '.(int)$tarefa_id);
	$existe=$sql->resultado();
	$sql->limpar();
	if ($existe) {
		$qnt_aba++;
		$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_logs', 'Registro',null,null,'Registro das Ocorr�ncia','Visualizar o registro de ocorr�ncia relacionado.');
		}
	}

	
if (count($obj->getSubordinada()) > 0) {
	$qnt_aba++;
	$f = 'subordinada';
	$ver_min = true;
	$_REQUEST['tarefa_status'] = $obj->tarefa_status;
	if($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas_projeto_pro', ucfirst($config['tarefa']).' Subordinadas',null,null,ucfirst($config['tarefa']).' Subordinadas','Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinadas (tarefas filho).');
	else $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas', ucfirst($config['tarefa']).' Subordinadas',null,null,ucfirst($config['tarefa']).' Subordinadas','Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinadas (tarefas filho).');
	}
if (count($caixaTab->tabs)) $caixaTab_mostrar = 1;


if($Aplic->profissional && $qnt_aba) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_gantt_pro', 'Gantt',null,null,'Gr�fico Gantt','Visualizar o gr�fico Gantt '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');
elseif (!$Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_gantt', 'Gr�fico Gantt',null,null,'Gr�fico Gantt','Visualizar o gr�fico Gantt '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');




if ($Aplic->ModuloAtivo('recursos') && $Aplic->checarModulo('recursos', 'acesso')) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefa', 'recurso_tarefa', ($baseline_id ? 'recurso_tarefa.baseline_id='.(int)$baseline_id : ''));
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = recurso_tarefa_recurso');
	$sql->adCampo('count(recursos.recurso_id)');
	$sql->adOnde('recurso_tarefa_tarefa = '.(int)$tarefa_id);
	$existe=$sql->resultado();
	$sql->limpar();
	
	if ($existe) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/recurso_lista', 'Recursos',null,null,'Recursos','Visualizar os recursos relacionados a '.($config['genero_tarefa']=='a' ? 'esta ': 'este ').$config['tarefa'].'.');
		$qnt_aba++;
		}
		
	}


if ($Aplic->profissional) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_entrega','tarefa_entrega', ($baseline_id ? 'tarefa_entrega.baseline_id='.(int)$baseline_id : ''));
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas','tarefas','tarefa_id=tarefa_entrega_tarefa'.($baseline_id ? ' AND tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$sql->adCampo('count(tarefa_entrega_id)');
	$existe=$sql->resultado();
	$sql->limpar();
	
	if ($existe) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/entrega_lista_pro', 'Entregas',null,null,'Entregas','Visualizar as entregas a '.($config['genero_tarefa']=='a' ? 'esta ': 'este ').$config['tarefa'].'.');
		$qnt_aba++;
		}
	}


	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'evento_gestao','evento_gestao', ($baseline_id ? 'evento_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir(($baseline_id ? 'baseline_' : '').'eventos','eventos', 'evento_id=evento_gestao_evento'.($baseline_id ? ' AND eventos.baseline_id='.(int)$baseline_id : ''));
			$sql->adOnde('evento_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('evento_gestao_evento IS NOT NULL');
			$sql->adCampo('count(evento_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$data_inicio=null;
			$data_fim=null;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Evento',null,null,'Evento','Visualizar o evento relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'arquivo_gestao','arquivo_gestao', ($baseline_id ? 'arquivo_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir('arquivo','arquivo', 'arquivo_id=arquivo_gestao_arquivo');
			$sql->adOnde('arquivo_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('arquivo_gestao_arquivo IS NOT NULL');
			$sql->adCampo('count(arquivo_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
		
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/arquivos/index_tabela', 'Arquivo',null,null,'Arquivo','Visualizar o arquivo relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'pratica_indicador_gestao','pratica_indicador_gestao', ($baseline_id ? 'pratica_indicador_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_gestao_indicador');
			$sql->adOnde('pratica_indicador_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('pratica_indicador_gestao_indicador IS NOT NULL');
			$sql->adCampo('count(pratica_indicador_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicador',null,null,'Indicador','Visualizar o indicador relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_gestao','plano_acao_gestao', ($baseline_id ? 'plano_acao_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir('plano_acao','plano_acao', 'plano_acao_id=plano_acao_gestao_acao');
			$sql->adOnde('plano_acao_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('plano_acao_gestao_acao IS NOT NULL');
			$sql->adCampo('count(plano_acao_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acao']),null,null,ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao_item')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_item_gestao','plano_acao_item_gestao', ($baseline_id ? 'plano_acao_item_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('plano_acao_item','plano_acao_item', 'plano_acao_item_id=plano_acao_item_gestao_plano_acao_item');
		$sql->adOnde('plano_acao_item_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('plano_acao_item_gestao_plano_acao_item IS NOT NULL');
		$sql->adCampo('count(plano_acao_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_itens_idx','Item de '.$config['acao'],null,null,'Item de '.$config['acao'],'Visualizar o item de '.$config['acao'].' relacionado.');
			}
		}	
		
	if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) {
		
			$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_gestao','projeto_gestao', ($baseline_id ? 'projeto_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir('projetos','projetos', 'projeto_id=projeto_gestao_projeto');
			$sql->adOnde('projeto_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
			$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
			$sql->adOnde('projeto_portfolio=0');
			$sql->adCampo('count(projeto_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projeto']),null,null,ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.');
			}
		
		
		
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_gestao','projeto_gestao', ($baseline_id ? 'projeto_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('projetos','projetos', 'projeto_id=projeto_gestao_projeto');
		$sql->adOnde('projeto_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
		$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
		$sql->adOnde('projeto_portfolio=1');
		$sql->adCampo('count(projeto_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_portifolio_pro', ucfirst($config['portfolio']),null,null,ucfirst($config['portfolio']),'Visualizar '.$config['genero_portfolio'].' '.$config['portfolio'].' relacionad'.$config['genero_portfolio'].'.');
			}	
		
		}		
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'ata_gestao','ata_gestao', ($baseline_id ? 'ata_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('ata','ata', 'ata_id=ata_gestao_ata');
		$sql->adOnde('ata_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('ata_gestao_ata IS NOT NULL');
		$sql->adCampo('count(ata_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Ata',null,null,'Ata','Visualizar a ata de reuni�o relacionada.');
			}
		}
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'demanda_gestao','demanda_gestao', ($baseline_id ? 'demanda_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('demandas','demandas', 'demanda_id=demanda_gestao_demanda');
		$sql->adOnde('demanda_ativa=1');
		$sql->adOnde('demanda_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('demanda_gestao_demanda IS NOT NULL');
		$sql->adCampo('count(demanda_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/demanda_tabela','Demanda',null,null,'Demanda','Visualizar a demanda relacionada.');
			}
		}				
			
	if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'msg_gestao','msg_gestao', ($baseline_id ? 'msg_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->adOnde('msg_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('msg_gestao_msg IS NOT NULL');
		$sql->adCampo('count(msg_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg', ucfirst($config['mensagem']),null,null,ucfirst($config['mensagem']),'Visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.');
				}
		if ($config['doc_interno']) {
			$sql->adTabela(($baseline_id ? 'baseline_' : '').'modelo_gestao','modelo_gestao', ($baseline_id ? 'modelo_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->adOnde('modelo_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('modelo_gestao_modelo IS NOT NULL');
			$sql->adCampo('count(modelo_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo', 'Documento',null,null,'Documento','Visualizar o documento relacionado.');
				}
			}
		}	
		
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'link_gestao','link_gestao', ($baseline_id ? 'link_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir('links','links', 'link_id=link_gestao_link');
			$sql->adOnde('link_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('link_gestao_link IS NOT NULL');
			$sql->adCampo('count(link_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Link',null,null,'Link','Visualizar o link relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'forum_gestao','forum_gestao', ($baseline_id ? 'forum_gestao.baseline_id='.(int)$baseline_id : ''));
			$sql->esqUnir('foruns','foruns', 'forum_id=forum_gestao_forum');
			$sql->adOnde('forum_gestao_tarefa = '.(int)$tarefa_id);
			$sql->adOnde('forum_gestao_forum IS NOT NULL');
			$sql->adCampo('count(forum_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'F�rum',null,null,'F�rum','Visualizar o f�rum relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'problema_gestao','problema_gestao', ($baseline_id ? 'problema_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('problema','problema', 'problema_id=problema_gestao_problema');
		$sql->adOnde('problema_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('problema_gestao_problema IS NOT NULL');
		$sql->adCampo('count(problema_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problema']),null,null,ucfirst($config['problema']),'Visualizar '.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'risco_gestao','risco_gestao', ($baseline_id ? 'risco_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('risco','risco', 'risco_id=risco_gestao_risco');
		$sql->adOnde('risco_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('risco_gestao_risco IS NOT NULL');
		$sql->adCampo('count(risco_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['risco']),null,null,ucfirst($config['risco']),'Visualizar '.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco_resposta')) {		
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'risco_resposta_gestao', 'risco_resposta_gestao');
		$sql->esqUnir('risco_resposta','risco_resposta', 'risco_resposta_id=risco_resposta_gestao_risco_resposta');
		$sql->adOnde('risco_resposta_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('risco_resposta_gestao_risco_resposta IS NOT NULL');
		$sql->adCampo('count(risco_resposta_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_resposta_pro_ver_idx', ucfirst($config['risco_resposta']),null,null,ucfirst($config['risco_resposta']),'Visualizar '.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.');
			}
		}

	if ($Aplic->modulo_ativo('instrumento')  && $Aplic->checarModulo('instrumento', 'acesso')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'instrumento_gestao','instrumento_gestao', ($baseline_id ? 'instrumento_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('instrumento','instrumento', 'instrumento_id=instrumento_gestao_instrumento');
		$sql->adOnde('instrumento_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('instrumento_gestao_instrumento IS NOT NULL');
		$sql->adCampo('count(instrumento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/instrumento/instrumento_lista_idx', ucfirst($config['instrumento']),null,null,ucfirst($config['instrumento']),'Visualizar '.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.');
			}
		}
	
	if ($Aplic->checarModulo('recursos', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'recurso_gestao','recurso_gestao', ($baseline_id ? 'recurso_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('recursos','recursos', 'recurso_id=recurso_gestao_recurso');
		$sql->adOnde('recurso_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('recurso_gestao_recurso IS NOT NULL');
		$sql->adCampo('count(recurso_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_recursos', ucfirst($config['recurso']),null,null,ucfirst($config['recurso']),'Visualizar '.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.');
			}
		}
		
	if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'patrocinador_gestao','patrocinador_gestao', ($baseline_id ? 'patrocinador_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('patrocinadores','patrocinadores', 'patrocinador_id=patrocinador_gestao_patrocinador');
		$sql->adOnde('patrocinador_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('patrocinador_gestao_patrocinador IS NOT NULL');
		$sql->adCampo('count(patrocinador_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/patrocinadores/patrocinador_ver_idx',ucfirst($config['patrocinador']),null,null,ucfirst($config['patrocinador']),'Visualizar '.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.');
			}
		}
			
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'programa')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'programa_gestao','programa_gestao', ($baseline_id ? 'programa_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('programa','programa', 'programa_id=programa_gestao_programa');
		$sql->adOnde('programa_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('programa_gestao_programa IS NOT NULL');
		$sql->adCampo('count(programa_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/programa_pro_ver_idx', ucfirst($config['programa']),null,null,ucfirst($config['programa']),'Visualizar '.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'beneficio')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'beneficio_gestao','beneficio_gestao', ($baseline_id ? 'beneficio_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('beneficio','beneficio', 'beneficio_id=beneficio_gestao_beneficio');
		$sql->adOnde('beneficio_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('beneficio_gestao_beneficio IS NOT NULL');
		$sql->adCampo('count(beneficio_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/beneficio_pro_ver_idx',ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.$config['programa'],null,null,ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.$config['programa'],'Visualizar '.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_beneficio'].'.');
			}
		}		
	
	if ($Aplic->checarModulo('projeto', 'acesso', 'licao')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'licao_gestao','licao_gestao', ($baseline_id ? 'licao_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('licao','licao', 'licao_id=licao_gestao_licao');
		$sql->adOnde('licao_ativa=1');
		$sql->adOnde('licao_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('licao_gestao_licao IS NOT NULL');
		$sql->adCampo('count(licao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/licao_tabela', ucfirst($config['licao']),null,null,ucfirst($config['licao']),'Visualizar '.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.');
			}
		}	
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'pratica_gestao','pratica_gestao', ($baseline_id ? 'pratica_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('praticas','praticas', 'pratica_id=pratica_gestao_pratica');
		$sql->adOnde('pratica_ativa=1');
		$sql->adOnde('pratica_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('pratica_gestao_pratica IS NOT NULL');
		$sql->adCampo('count(pratica_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/praticas_ver_idx', ucfirst($config['pratica']),null,null,ucfirst($config['pratica']),'Visualizar '.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tr_gestao','tr_gestao', ($baseline_id ? 'tr_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('tr','tr', 'tr_id=tr_gestao_tr');
		$sql->adOnde('tr_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('tr_gestao_tr IS NOT NULL');
		$sql->adCampo('count(tr_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/tr/tr_tabela','Termo de Refer�ncia',null,null,'Termo de Refer�ncia','Visualizar o termos de refer�ncia relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'brainstorm')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'brainstorm_gestao','brainstorm_gestao', ($baseline_id ? 'brainstorm_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('brainstorm','brainstorm', 'brainstorm_id=brainstorm_gestao_brainstorm');
		$sql->adOnde('brainstorm_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('brainstorm_gestao_brainstorm IS NOT NULL');
		$sql->adCampo('count(brainstorm_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/brainstorm_ver_idx','Brainstorm',null,null,'Brainstorm','Visualizar o brainstorm relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'gut')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'gut_gestao','gut_gestao', ($baseline_id ? 'gut_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('gut','gut', 'gut_id=gut_gestao_gut');
		$sql->adOnde('gut_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('gut_gestao_gut IS NOT NULL');
		$sql->adCampo('count(gut_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gut_ver_idx','GUT',null,null,'GUT','Visualizar a matriz G.U.T. relacionada.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'causa_efeito')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'causa_efeito_gestao','causa_efeito_gestao', ($baseline_id ? 'causa_efeito_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('causa_efeito','causa_efeito', 'causa_efeito_id=causa_efeito_gestao_causa_efeito');
		$sql->adOnde('causa_efeito_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('causa_efeito_gestao_causa_efeito IS NOT NULL');
		$sql->adCampo('count(causa_efeito_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/causa_efeito_ver_idx','Causa-Efeito',null,null,'Causa-Efeito','Visualizar o diagrama de causa-efeito relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'tgn')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tgn_gestao','tgn_gestao', ($baseline_id ? 'tgn_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('tgn','tgn', 'tgn_id=tgn_gestao_tgn');
		$sql->adOnde('tgn_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('tgn_gestao_tgn IS NOT NULL');
		$sql->adCampo('count(tgn_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tgn_pro_ver_idx', ucfirst($config['tgn']),null,null,ucfirst($config['tgn']),'Visualizar '.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'canvas')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'canvas_gestao','canvas_gestao', ($baseline_id ? 'canvas_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('canvas','canvas', 'canvas_id=canvas_gestao_canvas');
		$sql->adOnde('canvas_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('canvas_gestao_canvas IS NOT NULL');
		$sql->adCampo('count(canvas_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/canvas_pro_ver_idx', ucfirst($config['canvas']),null,null,ucfirst($config['canvas']),'Visualizar '.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'mswot_gestao','mswot_gestao', ($baseline_id ? 'mswot_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('mswot','mswot', 'mswot_id=mswot_gestao_mswot');
		$sql->adOnde('mswot_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('mswot_gestao_mswot IS NOT NULL');
		$sql->adCampo('count(mswot_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/swot/mswot_tabela','Matriz SWOT',null,null,'Matriz SWOT','Visualizar a matriz SWOT relacionada.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'swot_gestao','swot_gestao', ($baseline_id ? 'swot_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('swot','swot', 'swot_id=swot_gestao_swot');
		$sql->adOnde('swot_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('swot_gestao_swot IS NOT NULL');
		$sql->adCampo('count(swot_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/swot/swot_tabela','Campo SWOT',null,null,'Campo SWOT','Visualizar o campos SWOT relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'operativo_gestao','operativo_gestao', ($baseline_id ? 'operativo_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('operativo','operativo', 'operativo_id=operativo_gestao_operativo');
		$sql->adOnde('operativo_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('operativo_gestao_operativo IS NOT NULL');
		$sql->adCampo('count(operativo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/operativo/operativo_tabela','Plano Operativo',null,null,'Plano Operativo','Visualizar o plano operativo relacionado.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'monitoramento')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'monitoramento_gestao','monitoramento_gestao', ($baseline_id ? 'monitoramento_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('monitoramento','monitoramento', 'monitoramento_id=monitoramento_gestao_monitoramento');
		$sql->adOnde('monitoramento_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('monitoramento_gestao_monitoramento IS NOT NULL');
		$sql->adCampo('count(monitoramento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/monitoramento_ver_idx_pro','Monitoramento',null,null,'Monitoramento','Visualizar o monitoramento relacionado.');
			}
		}
	
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'avaliacao_indicador')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'avaliacao_gestao','avaliacao_gestao', ($baseline_id ? 'avaliacao_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('avaliacao','avaliacao', 'avaliacao_id=avaliacao_gestao_avaliacao');
		$sql->adOnde('avaliacao_ativa=1');
		$sql->adOnde('avaliacao_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('avaliacao_gestao_avaliacao IS NOT NULL');
		$sql->adCampo('count(avaliacao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/avaliacao_ver_idx','Avalia��o',null,null,'Avalia��o','Visualizar a avalia��o de indicadores relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'checklist')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'checklist_gestao','checklist_gestao', ($baseline_id ? 'checklist_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('checklist','checklist', 'checklist_id=checklist_gestao_checklist');
		$sql->adOnde('checklist_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('checklist_gestao_checklist IS NOT NULL');
		$sql->adCampo('count(checklist_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/checklist_ver_idx','Checklist',null,null,'Checklist','Visualizar o checklist relacionado.');
			}
		}	
	
	if ($Aplic->profissional) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'agenda_gestao','agenda_gestao', ($baseline_id ? 'agenda_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->adOnde('agenda_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('agenda_gestao_agenda IS NOT NULL');
		$sql->adCampo('count(agenda_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/email/compromisso_ver_idx_pro','Compromisso',null,null,'Compromisso','Visualizar o compromisso relacionado.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'agrupamento_gestao','agrupamento_gestao', ($baseline_id ? 'agrupamento_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('agrupamento','agrupamento', 'agrupamento_id=agrupamento_gestao_agrupamento');
		$sql->adOnde('agrupamento_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('agrupamento_gestao_agrupamento IS NOT NULL');
		$sql->adCampo('count(agrupamento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/agrupamento/agrupamento_tabela','Agrupamento',null,null,'Agrupamento','Visualizar o agrupamento relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'modelo')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'template_gestao','template_gestao', ($baseline_id ? 'template_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('template','template', 'template_id=template_gestao_template');
		$sql->adOnde('template_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('template_gestao_template IS NOT NULL');
		$sql->adCampo('count(template_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/template_pro_ver_idx','Modelo',null,null,'Modelo','Visualizar o modelo de '.$config['projeto'].' relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'painel_indicador')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_gestao','painel_gestao', ($baseline_id ? 'painel_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('painel','painel', 'painel_id=painel_gestao_painel');
		$sql->adOnde('painel_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('painel_gestao_painel IS NOT NULL');
		$sql->adCampo('count(painel_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_pro_lista_idx','Painel',null,null,'Painel','Visualizar o painel de indicador relacionado.');
			}
		}		
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'odometro_indicador')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_odometro_gestao','painel_odometro_gestao', ($baseline_id ? 'painel_odometro_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('painel_odometro','painel_odometro', 'painel_odometro_id=painel_odometro_gestao_painel_odometro');
		$sql->adOnde('painel_odometro_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('painel_odometro_gestao_painel_odometro IS NOT NULL');
		$sql->adCampo('count(painel_odometro_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/odometro_pro_lista_idx','Od�metro',null,null,'Od�metro','Visualizar o od�metro de indicador relacionado.');
			}
		}				
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'composicao_painel')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_composicao_gestao','painel_composicao_gestao', ($baseline_id ? 'painel_composicao_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('painel_composicao','painel_composicao', 'painel_composicao_id=painel_composicao_gestao_painel_composicao');
		$sql->adOnde('painel_composicao_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('painel_composicao_gestao_painel_composicao IS NOT NULL');
		$sql->adCampo('count(painel_composicao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_composicao_pro_lista_idx','Composi��o de pain�is',null,null,'Composi��o de pain�is','Visualizar a composi��o de pain�is relacionada.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'slideshow_painel')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_slideshow_gestao','painel_slideshow_gestao', ($baseline_id ? 'painel_slideshow_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('painel_slideshow','painel_slideshow', 'painel_slideshow_id=painel_slideshow_gestao_painel_slideshow');
		$sql->adOnde('painel_slideshow_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('painel_slideshow_gestao_painel_slideshow IS NOT NULL');
		$sql->adCampo('count(painel_slideshow_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_slideshow_pro_lista_idx','Slideshow',null,null,'Slideshow','Visualizar o slideshow de pain�is relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('agenda', 'acesso', null, null)) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'calendario_gestao','calendario_gestao', ($baseline_id ? 'calendario_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('calendario','calendario', 'calendario_id=calendario_gestao_calendario');
		$sql->adOnde('calendario_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('calendario_gestao_calendario IS NOT NULL');
		$sql->adCampo('count(calendario_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/sistema/calendario/calendario_ver_idx','Agenda Coletiva',null,null,'Agenda Coletiva','Visualizar a agendas coletiva relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'perspectiva')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'perspectiva_gestao','perspectiva_gestao', ($baseline_id ? 'perspectiva_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('perspectivas','perspectivas', 'pg_perspectiva_id=perspectiva_gestao_perspectiva');
		$sql->adOnde('perspectiva_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('perspectiva_gestao_perspectiva IS NOT NULL');
		$sql->adCampo('count(perspectiva_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/perspectivas_ver_idx', ucfirst($config['perspectiva']),null,null,ucfirst($config['perspectiva']),'Visualizar '.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.');
			}
		}		
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'tema')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tema_gestao','tema_gestao', ($baseline_id ? 'tema_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('tema','tema', 'tema_id=tema_gestao_tema');
		$sql->adOnde('tema_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('tema_gestao_tema IS NOT NULL');
		$sql->adCampo('count(tema_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tema_ver_idx', ucfirst($config['tema']),null,null,ucfirst($config['tema']),'Visualizar '.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.');
			}
		}				
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'objetivo')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'objetivo_gestao','objetivo_gestao', ($baseline_id ? 'objetivo_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('objetivo','objetivo', 'objetivo_id=objetivo_gestao_objetivo');
		$sql->adOnde('objetivo_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('objetivo_gestao_objetivo IS NOT NULL');
		$sql->adCampo('count(objetivo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/obj_estrategicos_ver_idx', ucfirst($config['objetivo']),null,null,ucfirst($config['objetivo']),'Visualizar '.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.');
			}
		}
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'me')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'me_gestao','me_gestao', ($baseline_id ? 'me_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('me','me', 'me_id=me_gestao_me');
		$sql->adOnde('me_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('me_gestao_me IS NOT NULL');
		$sql->adCampo('count(me_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/me_ver_idx_pro', ucfirst($config['me']),null,null,ucfirst($config['me']),'Visualizar '.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.');
			}
		}	
		
	if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'fator_gestao','fator_gestao', ($baseline_id ? 'fator_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('fator','fator', 'fator_id=fator_gestao_fator');
		$sql->adOnde('fator_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('fator_gestao_fator IS NOT NULL');
		$sql->adCampo('count(fator_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/fatores_ver_idx', ucfirst($config['fator']),null,null,ucfirst($config['fator']),'Visualizar '.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'iniciativa')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'estrategia_gestao','estrategia_gestao', ($baseline_id ? 'estrategia_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('estrategias','estrategias', 'pg_estrategia_id=estrategia_gestao_estrategia');
		$sql->adOnde('estrategia_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('estrategia_gestao_estrategia IS NOT NULL');
		$sql->adCampo('count(estrategia_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx', ucfirst($config['iniciativa']),null,null,ucfirst($config['iniciativa']),'Visualizar '.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.');
			}
		}
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'meta')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'meta_gestao','meta_gestao', ($baseline_id ? 'meta_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('metas','metas', 'pg_meta_id=meta_gestao_meta');
		$sql->adOnde('meta_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('meta_gestao_meta IS NOT NULL');
		$sql->adCampo('count(meta_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', ucfirst($config['meta']),null,null,ucfirst($config['meta']),'Visualizar '.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_gestao_gestao','plano_gestao_gestao', ($baseline_id ? 'plano_gestao_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('plano_gestao','plano_gestao', 'pg_id=plano_gestao_gestao_plano_gestao');
		$sql->adOnde('plano_gestao_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('plano_gestao_gestao_plano_gestao IS NOT NULL');
		$sql->adCampo('count(plano_gestao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gestao/gestao_tabela','Planejamento estrat�gico',null,null,'Planejamento estrat�gico','Visualizar o planejamento estrat�gico relacionado.');
			}
		}				

	if ($Aplic->checarModulo('projetos', 'acesso', null, 'abertura')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_abertura_gestao','projeto_abertura_gestao', ($baseline_id ? 'projeto_abertura_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('projeto_abertura','projeto_abertura', 'projeto_abertura_id=projeto_abertura_gestao_projeto_abertura');
		$sql->adOnde('projeto_abertura_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('projeto_abertura_gestao_projeto_abertura IS NOT NULL');
		$sql->adCampo('count(projeto_abertura_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/termo_abertura_tabela','Termo de abertura',null,null,'Termo de abertura','Visualizar o YYY relacionado.');
			}
		}			
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'viabilidade')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_viabilidade_gestao','projeto_viabilidade_gestao', ($baseline_id ? 'projeto_viabilidade_gestao.baseline_id='.(int)$baseline_id : ''));
		$sql->esqUnir('projeto_viabilidade','projeto_viabilidade', 'projeto_viabilidade_id=projeto_viabilidade_gestao_projeto_viabilidade');
		$sql->adOnde('projeto_viabilidade_gestao_tarefa = '.(int)$tarefa_id);
		$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IS NOT NULL');
		$sql->adCampo('count(projeto_viabilidade_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/viabilidade_tabela','Estudo de viabilidade',null,null,'Estudo de viabilidade','Visualizar o estudo de viabilidade relacionado.');
			}
		}		








if ($qnt_aba || !$Aplic->profissional) {
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa();
	}


?>

<script language="JavaScript">

function exportar_link(tipo) {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo='+tipo+'&id='+document.getElementById('tarefa_id').value+'&projeto_id='+document.getElementById('projeto_id').value, null, window);
	}

function menu_anexos(){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Menu dos Artefatos", 500, 400, 'm=projetos&a=menu_anexos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value, window.url_passar, window);
	else window.open('./index.php?m=projetos&a=menu_anexos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value, 'Menu dos Artefatos','height=400,width=500px,resizable,scrollbars=yes');
	}

function planilha_gasto_recurso(financeiro){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Recursos", 1024, 600, 'm=projetos&a=planilha_recurso&dialogo=1&baseline_id='+baseline_id+'&financeiro='+financeiro+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, null, window);
	else window.open('./index.php?m=projetos&a=planilha_recurso&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}

function planilha_custo_recurso(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Recursos", 1024, 600, 'm=tarefas&a=lista_recursos&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, null, window);
	else window.open('./index.php?m=tarefas&a=lista_recursos&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}

function imprimir(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Imprimir', 1020, 500, 'm=tarefas&a=imprimir_selecionar&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, null, window);
	else window.open('index.php?m=tarefas&a=imprimir_selecionar&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, 'imprimir','width=1020, height=800, menubar=1, scrollbars=1');
	}

function mudar_baseline(){
	url_passar(0, 'm=tarefas&a=ver&tab=<?php echo $tab ?>&tarefa_id=<?php echo $tarefa_id ?>&baseline_id='+document.getElementById('baseline_id').value);
	}

function popAreaMunicipio(municipio_id, projeto_id, tarefa_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('�rea', 770, 467, 'm=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), 'Ver �rea','height=467,width=770px,resizable,scrollbars=no');
	}

function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}




function popCoordenadas(latitude, longitude, projeto_area_id, projeto_id, tarefa_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Ver Coordenada",  770, 467, 'm=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), 'Ver Coordenada','height=467,width=770px,resizable,scrollbars=no');
	}

function excluir() {
	if (confirm( 'Tem certeza que deseja excluir <?php echo $config["genero_tarefa"]." ".$config["tarefa"]?>?'))	document.frmExcluir.submit();
	}


var financeiro_carregado=0;

function exibir_financeiro(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
	if (!financeiro_carregado) {
		xajax_exibir_financeiro(document.getElementById('tarefa_id').value, baseline_id);
		__buildTooltip();
		}

	if (document.getElementById('ver_financeiro').style.display) document.getElementById('ver_financeiro').style.display='';
	else document.getElementById('ver_financeiro').style.display='none';

	financeiro_carregado=1;

	}

</script>
