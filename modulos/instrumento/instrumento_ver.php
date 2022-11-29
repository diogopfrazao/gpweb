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

if (isset($_REQUEST['instrumento_id'])) $Aplic->setEstado('instrumento_id', getParam($_REQUEST, 'instrumento_id', null), $m, $a, $u);
$instrumento_id = $Aplic->getEstado('instrumento_id', null, $m, $a, $u);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('tab', getParam($_REQUEST, 'tab', null), $m, $a, $u);
$tab = $Aplic->getEstado('tab', 0, $m, $a, $u);


$instrumento_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');
$cor = getSisValor('SituacaoInstrumentoCor');

//todo: sergio não usou em todos os lugares, então fica estranho uma parte grande outra pequena
//por enquanto ignora
//$estilo=($dialogo ? 'font-size:12pt;': '');
//$estilo_texto=($dialogo ? 'style="font-size:12pt;"': '');

$estilo='';
$estilo_texto='';

require_once BASE_DIR.'/modulos/instrumento/instrumento.class.php';

$sql = new BDConsulta();


if ($Aplic->profissional){
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$assinar = $sql->linha();
	$sql->limpar();
	
	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}


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

$msg = '';
$obj = new CInstrumento();
$obj->load($instrumento_id);

if (!$obj && $instrumento_id > 0) {
	$Aplic->setMsg('Instrumento');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=instrumento&a=instrumento_lista');
	}
if (!$dialogo) $Aplic->salvarPosicao();

$sql->adTabela('instrumento_campo');
$sql->adCampo('instrumento_campo.*');
$sql->adOnde('instrumento_campo_id ='.(int)$obj->instrumento_campo);
$exibir=$sql->linha();
$sql->limpar();


$editar=permiteEditarInstrumento($obj->instrumento_acesso, $obj->instrumento_id);

$podeEditarTudo=($obj->instrumento_acesso>=5 ? $editar && (in_array($obj->instrumento_responsavel, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);


if ($Aplic->profissional){	
	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}	
	
if (!$dialogo){	
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']).'', 'instrumento.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['instrumentos']),'Clique neste botão para visualizar a lista de '.$config['instrumentos'].'.').'Lista de '.ucfirst($config['instrumentos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_lista\");");
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$assinar = $sql->linha();
	$sql->limpar();
	
	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");

	if ($podeAdicionar)	{
		$km->Add("inserir","inserir_objeto",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Criar nov'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);");
		$km->Add("inserir_objeto","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Criar nov'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar\");");
		$km->Add("inserir_objeto","inserir_clone",dica('Clone d'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']).' Atual', 'Criar clone d'.$config['genero_instrumento'].' '.$config['instrumento'].' atual.').'Clone d'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']).' atual'.dicaF(), "javascript: void(0);' onclick='clonar();");
		}

	
	if ($podeEditar && $editar) {	
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&instrumento_id=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_instrumento=".$instrumento_id."\");");	
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_instrumento=".$instrumento_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_instrumento=".$instrumento_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_instrumento=".$instrumento_id."\");");
			$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_instrumento=".$instrumento_id."\");");
			}
		if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avaliação', 'Inserir uma nova avaliação relacionada.').'Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional) $km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Odômetro de Indicador', 'Inserir um novo odômetro de indicador relacionado.').'Odômetro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composição de Painéis', 'Inserir uma nova composição de painéis relacionada.').'Composição de painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composições', 'Inserir um novo slideshow de composições relacionado.').'Slideshow de composições'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'ssti')) $km->Add("diverso","inserir_ssti",dica('Nov'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Inserir um'.($config['genero_ssti']=='a' ? 'a' : '').' nov'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=ssti_editar&ssti_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'laudo')) $km->Add("diverso","inserir_laudo",dica('Nov'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Inserir um'.($config['genero_laudo']=='a' ? 'a' : '').' nov'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=laudo_editar&laudo_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_trelo",dica('Nov'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Inserir um'.($config['genero_trelo']=='a' ? 'a' : '').' nov'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_editar&trelo_instrumento=".$instrumento_id."\");");
			$km->Add("diverso","inserir_trelo_cartao",dica('Nov'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Inserir um'.($config['genero_trelo_cartao']=='a' ? 'a' : '').' nov'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_cartao_editar&trelo_cartao_instrumento=".$instrumento_id."\");");
			}
		if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_pdcl",dica('Nov'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Inserir um'.($config['genero_pdcl']=='a' ? 'a' : '').' nov'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_editar&pdcl_instrumento=".$instrumento_id."\");");
			$km->Add("diverso","inserir_pdcl_item",dica('Nov'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Inserir um'.($config['genero_pdcl_item']=='a' ? 'a' : '').' nov'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_item_editar&pdcl_item_instrumento=".$instrumento_id."\");");
			}
		if ($Aplic->modulo_ativo('os') && $Aplic->checarModulo('os', 'adicionar', null, null)) $km->Add("diverso","inserir_os",dica('Nov'.$config['genero_os'].' '.ucfirst($config['os']), 'Inserir um'.($config['genero_os']=='a' ? 'a' : '').' nov'.$config['genero_os'].' '.$config['os'].' relacionad'.$config['genero_os'].'.').ucfirst($config['os']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=os&a=os_editar&os_instrumento=".$instrumento_id."\");");
		
		$km->Add("inserir","gestao1",dica('Gestao','Menu de objetos de gestão').'Gestao'.dicaF(), "javascript: void(0);'");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao1","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao1","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao1","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_instrumento=".$instrumento_id."\");");
		if ($Aplic->profissional && isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $km->Add("gestao1","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_instrumento=".$instrumento_id."\");");
		if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao1","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_instrumento=".$instrumento_id."\");"); 
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao1","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao1","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_instrumento=".$instrumento_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'planejamento')) $km->Add("gestao1","inserir_plano_gestao",dica('Novo Planejamento estratégico', 'Inserir um novo planejamento estratégico relacionado.').'Planejamento estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&plano_gestao_instrumento=".$instrumento_id."\");");
		
		
		
		$km->Add("inserir","planilha_custo",dica('Planilha de Custos','Menu de inserção de planilha de custos').'Planilha de Custos'.dicaF(), "javascript: void(0);");
		$km->Add("planilha_custo","inserir_planilha_custo_tr",dica('Planilhas de Custos de '.ucfirst($config['trs']), 'Importar e editar a planilha de custos de '.$config['trs'].'.').ucfirst($config['trs']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_estimado_tr&instrumento_id=".$instrumento_id."\");");
		
		
		
		}
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	$bloquear=($obj->instrumento_aprovado && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin && !$Aplic->checarModulo('todos', 'editar', null, 'editar_aprovado'));
	if ($Aplic->profissional && isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&instrumento_id=".$instrumento_id."\");"); 
	
	if ($podeEditarTudo && $podeEditar && !$bloquear) $km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['instrumento']),'Editar os detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Editar '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_id=".$instrumento_id."\");");	
	if ($podeEditarTudo && $podeExcluir && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Excluir '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='excluir()");
	
	
	if ($Aplic->profissional &&$podeEditarTudo && $podeEditar && !$bloquear) $km->Add("acao","acao_editar_tipo",dica('Mudar Tipo de '.ucfirst($config['instrumento']),'Mudar tipo de '.$config['instrumento'].'.').'Mudar Tipo de '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='mudar_tipo();");	
	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso')) {
		$km->Add("acao","siafi",dica(ucfirst($config['siafi']),'Opções d'.$config['genero_siafi'].' '.$config['siafi'].' com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['siafi']).dicaF(), "javascript: void(0);");
		if ($configuracao_financeira['organizacao']!='sema_mt') $km->Add("siafi","siafi_nc",dica('Importar Nota de Crédito','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de crédito relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar notas de crédito para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_nc_pro&instrumento_id=".$instrumento_id."\");");
		
		$km->Add("siafi","siafi_ne",dica('Importar Nota de Empenho','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de empenho relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar nota de empenho para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ne_pro&instrumento_id=".$instrumento_id."\");");
		if ($configuracao_financeira['organizacao']=='sema_mt') $km->Add("siafi","siafi_ne_estorno",dica('Importar Estorno de Nota de Empenho','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' os estornos das notas de empenho relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar estorno de empenhos para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ne_estorno_pro&instrumento_id=".$instrumento_id."\");");
		
		if ($configuracao_financeira['organizacao']!='sema_mt') $km->Add("siafi","siafi_rel_ne",dica('Relacionar Itens do Empenho com Planilha de Gasto','Relacionar itens do empenho com itens de planilha de gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Relacionar itens do empenho com planilha de gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_rel_item_ne_pro&instrumento_id=".$instrumento_id."\");");
		
		if ($configuracao_financeira['organizacao']=='sema_mt') $km->Add("siafi","siafi_ns",dica('Importar Nota de Liquidação','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de liquidação relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar notas de liquidação para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ns_pro&instrumento_id=".$instrumento_id."\");");
		else $km->Add("siafi","siafi_ns",dica('Importar Nota de Sistema','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de sistema relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar NS para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ns_pro&instrumento_id=".$instrumento_id."\");");
		if ($configuracao_financeira['organizacao']=='sema_mt') $km->Add("siafi","siafi_ns_estorno",dica('Importar Estorno de Nota de liquidação','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' os estornos das notas de liquidação relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar estornos de liquidações para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ns_estorno_pro&instrumento_id=".$instrumento_id."\");");
				
		
		
		$km->Add("siafi","siafi_ob",dica('Importar Ordem Bancária','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as ordens bancárias relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar ordem bancária para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ob_pro&instrumento_id=".$instrumento_id."\");");
		if ($configuracao_financeira['organizacao']=='sema_mt') $km->Add("siafi","siafi_ob_estorno",dica('Importar Estorno de Ordem Bancária','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' os estornos das ordens bancárias relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar estornos de ordem bancária para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ob_estorno_pro&instrumento_id=".$instrumento_id."\");");
		if ($configuracao_financeira['organizacao']=='sema_mt') $km->Add("siafi","siafi_gcv",dica('Importar GVC','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' os GVC relacionadas com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar GVC para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_gcv_pro&instrumento_id=".$instrumento_id."\");");
		if ($configuracao_financeira['organizacao']=='sema_mt') $km->Add("siafi","siafi_automatico",dica('Importar Automáticamente d'.$config['genero_siafi'].' '.$config['siafi'],'Importar automáticamente d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de empenho e estornos relacionados com '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Importar automáticamente d'.$config['genero_siafi'].' '.$config['siafi'].' para '.$config['genero_instrumento'].' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_automatico_pro&instrumento_id=".$instrumento_id."\");");
		
		}

	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	
	$km->Add("acao_imprimir","acao_imprimir_instrumento",dica(ucfirst($config['instrumento']), 'Imprimir '.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=instrumento&a=instrumento_imprimir&dialogo=1&instrumento_id=".$instrumento_id."\");");
	$km->Add("acao_imprimir","acao_imprimir_assinatura",dica(ucfirst($config['instrumento']), 'Imprimir '.$config['genero_instrumento'].' '.$config['instrumento'].' com as assinaturas scanneadas(se houver).').ucfirst($config['instrumento']).' assinado'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=instrumento&a=instrumento_imprimir&dialogo=1&assinatura=1&instrumento_id=".$instrumento_id."\");");

	
	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'], 'Imprimir os detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').' Detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&instrumento_id=".$instrumento_id."\");");
	if ($Aplic->profissional && $podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ícone '.imagem('icones/exporta_p.png').' para criar um endereço web para visualização em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");	

	echo $km->Render();
	echo '</td></tr></table>';
	}






echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_instrumento_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';




echo '<input type="hidden" id="existe_instrumento" name="existe_instrumento" value="0" />';
echo '<input type="hidden" id="instrumento_criado" name="instrumento_criado" value="" />';
echo '<input type="hidden" id="cia_id" name="cia_id" value="'.$obj->instrumento_cia.'" />';


echo '</form>';

if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $obj->instrumento_cia;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>'.ucfirst($config['instrumento']).'</center></font>';
	}
elseif ($dialogo) echo '<table cellpadding=0 cellspacing=1 width="750"><tr><td align=center><h1>'.$exibir[''].'</h1></td></tr></table>';




echo '<table cellpadding=0 cellspacing=1 width="100%"'.($dialogo ? '' : ' class="std"').'>';

echo '<tr><td colspan=2 style="border: outset #d1d1cd 1px;background-color:#'.$obj->instrumento_cor.'"><font color="'.melhorCor($obj->instrumento_cor).'"><b>'.$obj->instrumento_nome.'<b></font></td></tr>';


$numeracao_titulo=0;

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

$numeracao=0;
if ($exibir['instrumento_identificacao']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_identificacao_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_nome_leg'].':</td><td class="realce">'.$obj->instrumento_nome.'</td></tr>';
if ($exibir['instrumento_entidade'] && $obj->instrumento_entidade) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_leg'].':</td><td class="realce">'.$obj->instrumento_entidade.'</td></tr>';
if ($exibir['instrumento_entidade_cnpj'] && $obj->instrumento_entidade_cnpj) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_cnpj_leg'].':</td><td class="realce">'.$obj->instrumento_entidade_cnpj.'</td></tr>';
if ($exibir['instrumento_entidade_codigo'] && $obj->instrumento_entidade_codigo) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_codigo_leg'].':</td><td class="realce">'.$obj->instrumento_entidade_codigo.'</td></tr>';
if ($exibir['instrumento_tipo']) {
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo_nome');
	$sql->adOnde('instrumento_campo_id='.(int)($instrumento_id ? $obj->instrumento_campo : $instrumento_campo));
	$instrumento_campo=$sql->resultado();
	$sql->limpar();
	echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_tipo_leg'].':</td><td class="realce">'.$instrumento_campo.'</td></tr>';
	}
if ($exibir['instrumento_numero'] && $obj->instrumento_numero) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_numero_leg'].':</td><td class="realce">'.$obj->instrumento_numero.'</td></tr>';
if ($exibir['instrumento_ano'] && $obj->instrumento_ano) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_ano_leg'].':</td><td class="realce">'.$obj->instrumento_ano.'</td></tr>';
if ($exibir['instrumento_prorrogavel'] && $obj->instrumento_prorrogavel) echo '<tr><td align="right" nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_prorrogavel_leg'].':</td><td class="realce">'.($obj->instrumento_prorrogavel ? 'Sim' : 'Não').'</td></tr>';
if ($exibir['instrumento_situacao'] && $obj->instrumento_situacao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_situacao_leg'].':</td><td class="realce">'.getSisValorCampo('SituacaoInstrumento', $obj->instrumento_situacao).'</td></tr>';
if ($exibir['instrumento_valor'] && ($obj->instrumento_valor > 0)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_leg'].':</td><td class="realce">'.number_format($obj->instrumento_valor, ($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
//if ($exibir['instrumento_valor_atual'] && ($obj->instrumento_valor_atual > 0) && ($obj->instrumento_campo!=6)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_atual_leg'].':</td><td class="realce">'.number_format($obj->instrumento_valor_atual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
if ($exibir['instrumento_valor_atual'] && ($obj->instrumento_valor_atual > 0)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_atual_leg'].':</td><td class="realce">'.number_format($obj->instrumento_valor_atual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';

if ($exibir['instrumento_valor_contrapartida'] && ($obj->instrumento_valor_contrapartida > 0)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_contrapartida_leg'].':</td><td class="realce">'.number_format($obj->instrumento_valor_contrapartida,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
if ($exibir['instrumento_valor_repasse'] && ($obj->instrumento_valor_repasse > 0)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_repasse_leg'].':</td><td class="realce">'.number_format($obj->instrumento_valor_repasse,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';


//CHECAR SE TEM os
$sql->adTabela('os_gestao','os_gestao');
$sql->esqUnir('os','os', 'os_gestao_os=os_id');
$sql->adOnde('os_gestao_instrumento = '.(int)$instrumento_id);
$sql->adOnde('os_gestao_os IS NOT NULL');
$sql->adCampo('SUM(os_valor)');
$soma_os=$sql->resultado();
$sql->limpar();

if ($soma_os!=0) {
	echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. Valor d'.$config['genero_os'].'s '.$config['os'].':</td><td class="realce">'.number_format($soma_os,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. Saldo de contrato:</td><td class="realce">'.number_format(($obj->instrumento_valor-$soma_os),($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	}

if ($exibir['instrumento_fim_contrato'] && $obj->instrumento_fim_contrato) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fim_contrato_leg'].':</td><td class="realce">'.retorna_data($obj->instrumento_fim_contrato, false).'</td></tr>';




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

	$sql->adOrdem('DATA_EMP DESC, NUMR_EMP ASC');
	
	$lista_ne=$sql->lista();
	$sql->limpar();
	
	if (count($lista_ne)){
		echo '<tr><td align=right width=50 style="white-space: nowrap;'.$estilo.'">Saldo(Empenho):</td><td class="realce" '.$estilo_texto.'><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr>
		<th '.$estilo_texto.'>Nº Empenho</th>
		<th '.$estilo_texto.'>Valor</th>
		<th '.$estilo_texto.'>Estorno</th>
		<th '.$estilo_texto.'>GVC</th>
		<th '.$estilo_texto.'>Valor Atual</th>
		<th '.$estilo_texto.'>Alocado Local</th>
		<th '.$estilo_texto.'>Alocado Geral</th>
		<th '.$estilo_texto.'>Saldo</th>
		</tr>';
		
		$total_VALR_EMP=0;
		$total_estorno=0;
		$total_gcv=0;
		$total_soma_ne=0;
		$total_soma_ne_local=0;
		
		foreach ($lista_ne as $ne) {
			$sql->adTabela('financeiro_rel_ne','financeiro_rel_ne');
			$sql->adOnde('financeiro_rel_ne_ne = '.(int)$ne['financeiro_ne_id']);
			$sql->adOnde('financeiro_rel_ne_instrumento IS NOT NULL');
			$sql->adCampo('SUM(financeiro_rel_ne_valor)');
			$soma_ne=$sql->resultado();
			$sql->limpar();
			echo '<tr>
			<td '.$estilo_texto.'><a href="javascript: void(0);" onclick="popNE('.$ne['financeiro_ne_id'].')" width=250>'.substr($ne['NUMR_EMP'], 0, 5).'.'.substr($ne['NUMR_EMP'], 5, 4).'.'.substr($ne['NUMR_EMP'], 9, 2).'.'.substr($ne['NUMR_EMP'], 11, 6).'-'.substr($ne['NUMR_EMP'], 17, 1).'</a></td>
			<td align=right '.$estilo_texto.'>'.number_format($ne['VALR_EMP'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ne['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ne['gcv'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ne['VALR_EMP']-$ne['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ne['financeiro_rel_ne_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right class="realce" '.$estilo_texto.'><a href="javascript: void(0);" onclick="popExtrato(\'ne\','.$ne['financeiro_ne_id'].')">'.number_format($ne['VALR_EMP']-$ne['estorno']+$ne['gcv']-$soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</a></td>';
			echo '</tr>';
			
			$total_VALR_EMP+=$ne['VALR_EMP'];
			$total_estorno+=$ne['estorno'];
			$total_gcv+=$ne['gcv'];
			$total_soma_ne+=$soma_ne;
			$total_soma_ne_local+=$ne['financeiro_rel_ne_valor'];
			}
		
		
		echo '<tr>
			<td '.$estilo_texto.' align=center><b>Total</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_VALR_EMP,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_gcv,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_VALR_EMP-$total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_soma_ne_local,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right class="realce" '.$estilo_texto.'><b>'.number_format($total_VALR_EMP-$total_estorno+$total_gcv-$total_soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>';
		echo '</tr>';
			
		echo '</table></td></tr>';
		
		echo '<tr><td align=right width=50 style="white-space: nowrap;'.$estilo.'">Empenhos alocados:</td><td class="realce" '.$estilo_texto.'>'.number_format($total_soma_ne_local,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		
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
	
	$sql->adOrdem('DATA_LIQ DESC, NUMR_LIQ ASC');
	
	$lista_ns=$sql->lista();
	$sql->limpar();
	if (count($lista_ns)){
		echo '<tr><td align=right width=50 style="white-space: nowrap;'.$estilo.'">Saldo(Liquidação):</td><td class="realce" '.$estilo_texto.'><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr>
		<th '.$estilo_texto.'>Nº Liquidação</th>
		<th '.$estilo_texto.'>Valor</th>
		<th '.$estilo_texto.'>Estorno</th>
		<th '.$estilo_texto.'>GVC</th>
		<th '.$estilo_texto.'>Valor Atual</th>
		<th '.$estilo_texto.'>Alocado Local</th>
		<th '.$estilo_texto.'>Alocado Geral</th>
		<th '.$estilo_texto.'>Saldo</th></tr>';
		
		$total_VALR_LIQ=0;
		$total_estorno=0;
		$total_gcv=0;
		$total_soma_ns=0;
		$total_soma_ns_local=0;
		
		foreach ($lista_ns as $ns) {
			$sql->adTabela('financeiro_rel_ns','financeiro_rel_ns');
			$sql->adOnde('financeiro_rel_ns_ns = '.(int)$ns['financeiro_ns_id']);
			$sql->adOnde('financeiro_rel_ns_instrumento IS NOT NULL');
			$sql->adCampo('SUM(financeiro_rel_ns_valor)');
			$soma_ns=$sql->resultado();
			$sql->limpar();
			echo '<tr><td '.$estilo_texto.'><a href="javascript: void(0);" onclick="popNS('.$ns['financeiro_ns_id'].')" width=250>'.substr($ns['NUMR_LIQ'], 0, 5).'.'.substr($ns['NUMR_LIQ'], 5, 4).'.'.substr($ns['NUMR_LIQ'], 9, 2).'.'.substr($ns['NUMR_LIQ'], 11, 6).'-'.substr($ns['NUMR_LIQ'], 17, 1).'</a></td>
			<td '.$estilo_texto.'>'.number_format($ns['VALR_LIQ'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ns['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ns['gcv'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ns['VALR_LIQ']-$ns['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ns['financeiro_rel_ns_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td class="realce" align=right '.$estilo_texto.'><a href="javascript: void(0);" onclick="popExtrato(\'ns\','.$ns['financeiro_ns_id'].')">'.number_format($ns['VALR_LIQ']-$ns['estorno']+$ns['gcv']-$soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</a></td></tr>';
			
			$total_VALR_LIQ+=$ns['VALR_LIQ'];
			$total_estorno+=$ns['estorno'];
			$total_gcv+=$ns['gcv'];
			$total_soma_ns+=$soma_ns;
			$total_soma_ns_local+=$ns['financeiro_rel_ns_valor'];
			}
			
		echo '<tr>
			<td '.$estilo_texto.' align=center><b>Total</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_VALR_LIQ,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_gcv,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_VALR_LIQ-$total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_soma_ns_local,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right class="realce" '.$estilo_texto.'><b>'.number_format($total_VALR_LIQ-$total_estorno+$total_gcv-$total_soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>';
		echo '</tr>';
			
		echo '</table></td></tr>';
		
		
		echo '<tr><td align=right width=50 style="white-space: nowrap;'.$estilo.'">Liquidações alocadas:</td><td class="realce" '.$estilo_texto.'>'.number_format($total_soma_ns_local,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
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
	
	$sql->adOrdem('DATA_EMISSAO DESC, NUMR_NOB ASC');
	
	$lista_ob=$sql->lista();
	$sql->limpar();

	if (count($lista_ob)){
		echo '<tr><td align=right width=50 style="white-space: nowrap;'.$estilo.'">Saldo(Pagamento):</td><td class="realce" '.$estilo_texto.'><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr>
		<th '.$estilo_texto.'>Nº Pagamento</th>
		<th '.$estilo_texto.'>Valor</th>
		<th '.$estilo_texto.'>Estorno</th>
		<th '.$estilo_texto.'>GVC</th>
		<th '.$estilo_texto.'>Valor Atual</th>
		<th '.$estilo_texto.'>Alocado Local</th>
		<th '.$estilo_texto.'>Alocado Geral</th>
		<th '.$estilo_texto.'>Saldo</th>
		</tr>';
		
		$total_VALR_NOB=0;
		$total_estorno=0;
		$total_gcv=0;
		$total_soma_ob=0;
		$total_soma_ob_local=0;
		foreach ($lista_ob as $ob) {
			$sql->adTabela('financeiro_rel_ob','financeiro_rel_ob');
			$sql->adOnde('financeiro_rel_ob_ob = '.(int)$ob['financeiro_ob_id']);
			$sql->adOnde('financeiro_rel_ob_instrumento IS NOT NULL');
			$sql->adCampo('SUM(financeiro_rel_ob_valor)');
			$soma_ob=$sql->resultado();
			$sql->limpar();
			echo '<tr><td '.$estilo_texto.'><a href="javascript: void(0);" onclick="popOB('.$ob['financeiro_ob_id'].')" width=250>'.substr($ob['NUMR_NOB'], 0, 5).'.'.substr($ob['NUMR_NOB'], 5, 4).'.'.substr($ob['NUMR_NOB'], 9, 2).'.'.substr($ob['NUMR_NOB'], 11, 6).'-'.substr($ob['NUMR_NOB'], 17, 1).'</td>
			<td '.$estilo_texto.'>'.number_format($ob['VALR_NOB'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ob['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ob['gcv'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ob['VALR_NOB']-$ob['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($ob['financeiro_rel_ob_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'>'.number_format($soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right '.$estilo_texto.'><a href="javascript: void(0);" onclick="popExtrato(\'ob\','.$ob['financeiro_ob_id'].')">'.number_format($ob['VALR_NOB']-$ob['estorno']+$ob['gcv']-$soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</a></td></tr>';
			$total_VALR_NOB+=$ob['VALR_NOB'];
			$total_estorno+=$ob['estorno'];
			$total_gcv+=$ob['gcv'];
			$total_soma_ob+=$soma_ob;
			$total_soma_ob_local+=$ob['financeiro_rel_ob_valor'];
			}
			
		echo '<tr>
			<td '.$estilo_texto.' align=center><b>Total</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_VALR_NOB,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_gcv,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_VALR_NOB-$total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_soma_ob_local,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right '.$estilo_texto.'><b>'.number_format($total_soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right class="realce" '.$estilo_texto.'><b>'.number_format($total_VALR_NOB-$total_estorno+$total_gcv-$total_soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>';
		echo '</tr>';
			
		echo '</table></td></tr>';
		
		
		echo '<tr><td align=right width=50 style="white-space: nowrap;'.$estilo.'">Ordens bancárias alocadas:</td><td class="realce" '.$estilo_texto.'>'.number_format($total_soma_ob_local,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		}
	}










if ($exibir['instrumento_identificacao']) echo '</table></fieldset></td></tr>';


$numeracao=0;
if ($exibir['instrumento_demandante']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_demandante_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
if ($exibir['instrumento_cia']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce">'.link_cia($obj->instrumento_cia).'</td></tr>';
if ($exibir['instrumento_cias'] && $Aplic->profissional) {
	$sql->adTabela('instrumento_cia');
	$sql->adCampo('instrumento_cia_cia');
	$sql->adOnde('instrumento_cia_instrumento = '.(int)$instrumento_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		}
	if ($saida_cias) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td class="realce">'.$saida_cias.'</td></tr>';
	}
if ($exibir['instrumento_dept'] && $obj->instrumento_dept && $Aplic->profissional) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce">'.link_dept($obj->instrumento_dept).'</td></tr>';

if ($exibir['instrumento_depts'] && $Aplic->profissional) {
	$sql->adTabela('instrumento_depts');
	$sql->adCampo('DISTINCT instrumento_depts.dept_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_depts = $sql->carregarColuna();
	$sql->limpar();
	$saida_depts='';
	if ($instrumento_depts && count($instrumento_depts)) {
		$saida_depts.= link_dept($instrumento_depts[0]);
		$qnt_lista_depts=count($instrumento_depts);
		if ($qnt_lista_depts > 1) {		
			$lista='';
			for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($instrumento_depts[$i]).'<br>';		
			$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
			}
		} 
	if ($saida_depts) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td class="realce">'.$saida_depts.'</td></tr>';
	}

if ($exibir['instrumento_responsavel'] && $obj->instrumento_responsavel) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'], 'Tod'.$config['genero_instrumento'].' '.$config['instrumento'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td class="realce">'.link_usuario($obj->instrumento_responsavel,'','','esquerda').'</td></tr>';

if ($exibir['instrumento_designados']){
	$sql->adTabela('instrumento_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_designados = $sql->carregarColuna();
	$sql->limpar();
	$saida_quem='';
	if ($instrumento_designados && count($instrumento_designados)) {
			$saida_quem.= link_usuario($instrumento_designados[0], '','','esquerda');
			$qnt_instrumento_designados=count($instrumento_designados);
			if ($qnt_instrumento_designados > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_instrumento_designados; $i < $i_cmp; $i++) $lista.=link_usuario($instrumento_designados[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'instrumento_designados\');">(+'.($qnt_instrumento_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="instrumento_designados"><br>'.$lista.'</span>';
					}
			} 
	if($saida_quem)echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Designado', 'Quais '.$config['usuarios'].' estão designados para '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Designado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';
	}

if ($exibir['instrumento_supervisor'] && $obj->instrumento_supervisor) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['supervisor']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_supervisor']=='o' ? 'um' : 'uma').' '.$config['supervisor'].' relacionad'.$config['genero_supervisor'].'.').ucfirst($config['supervisor']).':'.dicaF().'</td><td class="realce">'.link_usuario($obj->instrumento_supervisor,'','','esquerda').'</td></tr>';
if ($exibir['instrumento_autoridade'] && $obj->instrumento_autoridade) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['autoridade']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_autoridade']=='o' ? 'um' : 'uma').' '.$config['autoridade'].' relacionad'.$config['genero_autoridade'].'.').ucfirst($config['autoridade']).':'.dicaF().'</td><td class="realce">'.link_usuario($obj->instrumento_autoridade,'','','esquerda').'</td></tr>';
if ($exibir['instrumento_cliente'] && $obj->instrumento_cliente) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['cliente']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_cliente']=='o' ? 'um' : 'uma').' '.$config['cliente'].' relacionad'.$config['genero_cliente'].'.').ucfirst($config['cliente']).':'.dicaF().'</td><td class="realce">'.link_usuario($obj->instrumento_cliente,'','','esquerda').'</td></tr>';
if ($exibir['instrumento_fiscal'] && $obj->instrumento_fiscal) {
	echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_leg'].':</td><td class="realce">'.link_usuario($obj->instrumento_fiscal,'','','esquerda').'</td></tr>';
	
	
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('contato_email, contato_tel, contato_tel2, contato_cel');
	$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal);
	$linha_contato = $sql->linha();
	$sql->limpar();
		
	if ($linha_contato['contato_email']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.'E-mail '.$exibir['instrumento_fiscal_leg'].':</td><td class="realce">'.$linha_contato['contato_email'].'</td></tr>';
	if ($linha_contato['contato_tel']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_leg'].':</td><td class="realce">'.$linha_contato['contato_tel'].'</td></tr>';
	else if ($linha_contato['contato_tel2']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_leg'].':</td><td class="realce">'.$linha_contato['contato_tel2'].'</td></tr>';
	else if ($linha_contato['contato_cel']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Celular '.$exibir['instrumento_fiscal_leg'].':</td><td class="realce">'.$linha_contato['contato_cel'].'</td></tr>';
	else {
		
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
		$sql->adCampo('dept_tel');
		$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal);
		$dept_tel = $sql->resultado();
		$sql->limpar();
		if ($dept_tel) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_leg'].':</td><td class="realce">'.$dept_tel.'</td></tr>';
		}
	
	}


if ($exibir['instrumento_fiscal_substituto'] && $obj->instrumento_fiscal_substituto) {
	echo '<tr><td align=right width=50 style="white-space: nowrap;vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td class="realce">'.link_usuario($obj->instrumento_fiscal_substituto,'','','esquerda').'</td></tr>';
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('contato_email, contato_tel, contato_tel2, contato_cel');
	$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal_substituto);
	$linha_contato = $sql->linha();
	$sql->limpar();
		
	if ($linha_contato['contato_email']) echo '<tr><td align=right width=50 style="white-space: nowrap;vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'E-mail '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td class="realce">'.$linha_contato['contato_email'].'</td></tr>';
	if ($linha_contato['contato_tel']) echo '<tr><td align=right width=50 style="white-space: nowrap;vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td class="realce">'.$linha_contato['contato_tel'].'</td></tr>';
	else if ($linha_contato['contato_tel2']) echo '<tr><td align=right width=50 style="white-space: nowrap;vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td class="realce">'.$linha_contato['contato_tel2'].'</td></tr>';
	else if ($linha_contato['contato_cel']) echo '<tr><td align=right width=50 style="white-space: nowrap;vertical-align:top;p">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Celular '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td class="realce">'.$linha_contato['contato_cel'].'</td></tr>';
	else {
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
		$sql->adCampo('dept_tel');
		$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal_substituto);
		$dept_tel = $sql->resultado();
		$sql->limpar();
		if ($dept_tel) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td class="realce">'.$dept_tel.'</td></tr>';
		}
	}



if ($exibir['instrumento_demandante']) echo '</table></fieldset></td></tr>';



if ($obj->instrumento_prazo_prorrogacao || ($obj->instrumento_acrescimo > 0) || ($obj->instrumento_supressao > 0)){
	$numeracao=0;
	if ($exibir['instrumento_adtivo']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_adtivo_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	$prorrogacao_tipo=array(0=>'dias', 1=>'meses', 2=>'anos');
	if ($exibir['instrumento_prazo_prorrogacao'] && $obj->instrumento_prazo_prorrogacao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_prazo_prorrogacao_leg'].':</td><td class="realce">'.$obj->instrumento_prazo_prorrogacao.' '.(isset($prorrogacao_tipo[$obj->instrumento_prazo_prorrogacao_tipo]) ? $prorrogacao_tipo[$obj->instrumento_prazo_prorrogacao_tipo] :'').'</td></tr>';
	if ($exibir['instrumento_acrescimo'] && $obj->instrumento_acrescimo) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_acrescimo_leg'].':</td><td class="realce">'.number_format($obj->instrumento_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	if ($exibir['instrumento_supressao'] && $obj->instrumento_supressao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_supressao_leg'].':</td><td class="realce">'.number_format($obj->instrumento_supressao,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	if ($exibir['instrumento_adtivo']) echo '</table></fieldset></td></tr>';
	}



$data_texto = new CData();
$numeracao=0;
$linhas=null;

$total_acrescimo=0;

if ($exibir['instrumento_avulso_custo']){
	
	$saida=desenhaPlanilhaCustos((int)$instrumento_id, $exibir);
	
	if ($saida || ($exibir['instrumento_avulso_custo_acrescimo'] && $total_acrescimo) || ($exibir['instrumento_local_entrega'] && $obj->instrumento_local_entrega)) {
		echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_avulso_custo_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
		if ($saida) echo '<tr><td align="right" style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Itens', 'Itens do serviço/entrega de materiais d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Itens:'.dicaF().'</td><td>'.$saida.'</td></tr>';
		echo '</div></td></tr>';
		
		if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<tr><td align=right width=100 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Valor Total do Contrato com Acréscimo', 'Soma do valor do contrato ascrescentado da planilha de custo dos itens d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Total com acréscimo:'.dicaF().'</td><td class="realce">'.number_format($obj->instrumento_valor+$total_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		
		if ($exibir['instrumento_local_entrega'] && $obj->instrumento_local_entrega) echo '<tr><td align=right width=100 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Local de Prestação do Serviço/Entrega de Materiais', 'Preencha neste campo o local de prestação do serviço/entrega de materiais d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Local de entrega:'.dicaF().'</td><td class="realce">'.$obj->instrumento_local_entrega.'</td></tr>';
		echo '</table></fieldset></td></tr>';
		}
	}





$numeracao=0;
if ($obj->instrumento_objeto || $obj->instrumento_justificativa || $obj->instrumento_resultado_esperado || $obj->instrumento_situacao_atual || $obj->instrumento_vantagem_economica){
	if ($exibir['instrumento_detalhamento']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_detalhamento_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	if ($exibir['instrumento_objeto'] && $obj->instrumento_objeto) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_objeto_leg'].':</td><td class="realce">'.$obj->instrumento_objeto.'</td></tr>';
	if ($exibir['instrumento_justificativa'] && $obj->instrumento_justificativa) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_justificativa_leg'].':</td><td class="realce">'.$obj->instrumento_justificativa.'</td></tr>';
	if ($exibir['instrumento_resultado_esperado'] && $obj->instrumento_resultado_esperado) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_resultado_esperado_leg'].':</td><td class="realce">'.$obj->instrumento_resultado_esperado.'</td></tr>';
	if ($exibir['instrumento_situacao_atual'] && $obj->instrumento_situacao_atual) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_situacao_atual_leg'].':</td><td class="realce">'.$obj->instrumento_situacao_atual.'</td></tr>';
	if ($exibir['instrumento_vantagem_economica'] && $obj->instrumento_vantagem_economica) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_vantagem_economica_leg'].':</td><td class="realce">'.$obj->instrumento_vantagem_economica.'</td></tr>';
	if ($exibir['instrumento_detalhamento']) echo '</table></fieldset></td></tr>';
	
	}



$numeracao=0;
$financeiros=null;
if ($exibir['instrumento_financeiro']){ 
if ($obj->instrumento_id) {
		$sql->adTabela('instrumento_financeiro');
		$sql->adOnde('instrumento_financeiro_instrumento = '.(int)$obj->instrumento_id);
		$sql->adCampo('instrumento_financeiro.*');
		$sql->adOrdem('instrumento_financeiro_ordem');
		$financeiros=$sql->ListaChave('instrumento_financeiro_id');
		$sql->limpar();
		}
	}

if ($exibir['instrumento_financeiro'] && is_array($financeiros) && count($financeiros)){ 
	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_financeiro_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	
	
	if ($obj->instrumento_id) {
		$sql->adTabela('instrumento_financeiro');
		$sql->adOnde('instrumento_financeiro_instrumento = '.(int)$obj->instrumento_id);
		$sql->adCampo('instrumento_financeiro.*');
		$sql->adOrdem('instrumento_financeiro_ordem');
		$financeiros=$sql->ListaChave('instrumento_financeiro_id');
		$sql->limpar();
		}
	else $financeiros=null;
	
	echo '<tr><td colspan=2><div id="combo_financeiro">';
	if (is_array($financeiros) && count($financeiros)) {
		$instrumentoFonte = getSisValor('instrumento_fonte');
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr>';
		echo '<th>'.$exibir['instrumento_financeiro_projeto_leg'].'</th>';
		if ($exibir['instrumento_financeiro_tarefa']) echo '<th>'.$exibir['instrumento_financeiro_tarefa_leg'].'</th>';
		if ($exibir['instrumento_financeiro_fonte']) echo '<th>'.$exibir['instrumento_financeiro_fonte_leg'].'</th>';
		if ($exibir['instrumento_financeiro_regiao']) echo '<th>'.$exibir['instrumento_financeiro_regiao_leg'].'</th>';
		if ($exibir['instrumento_financeiro_classificacao']) echo '<th>'.$exibir['instrumento_financeiro_classificacao_leg'].'</th>';
		echo '<th>'.dica('Valor(R$)', 'Valor a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Valor(R$)'.dicaF().'</th>';
		echo '<th>'.dica('Ano', 'Ano a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Ano'.dicaF().'</th>';
		echo '</tr>';
		foreach ($financeiros as $instrumento_financeiro_id => $financeiro) {
			echo '<tr>';
			echo '<td align="left">'.$financeiro['instrumento_financeiro_projeto'].'</td>';
			if ($exibir['instrumento_financeiro_tarefa']) echo '<td align="left">'.$financeiro['instrumento_financeiro_tarefa'].'</td>';
			
			if ($exibir['instrumento_financeiro_fonte']) echo '<td align="left">'.$financeiro['instrumento_financeiro_fonte'].'</td>';
			
			if ($exibir['instrumento_financeiro_regiao']) echo '<td align="left">'.$financeiro['instrumento_financeiro_regiao'].'</td>';
			if ($exibir['instrumento_financeiro_classificacao']) echo '<td align="left">'.$financeiro['instrumento_financeiro_classificacao'].'</td>';
			echo '<td align="right">'.number_format($financeiro['instrumento_financeiro_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			echo '<td align="left">'.$financeiro['instrumento_financeiro_ano'].'</td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	
	echo '</div></td></tr>';
	
	
	echo '</table></fieldset></td></tr>';
	}



if ($obj->instrumento_data_celebracao || $obj->instrumento_data_inicio || $obj->instrumento_data_termino || $obj->instrumento_data_publicacao){
	$numeracao=0;
	if ($exibir['instrumento_datas']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_datas_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	if ($exibir['instrumento_data_celebracao'] && $obj->instrumento_data_celebracao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_celebracao_leg'].':</td><td class="realce">'.retorna_data($obj->instrumento_data_celebracao, false).'</td></tr>';
	if ($exibir['instrumento_data_inicio'] && $obj->instrumento_data_inicio) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_inicio_leg'].':</td><td class="realce">'.retorna_data($obj->instrumento_data_inicio, false).'</td></tr>';
	if ($exibir['instrumento_data_termino'] && $obj->instrumento_data_termino) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_termino_leg'].':</td><td class="realce">'.retorna_data($obj->instrumento_data_termino, false).'</td></tr>';
	if ($exibir['instrumento_data_publicacao'] && $obj->instrumento_data_publicacao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_publicacao_leg'].':</td><td class="realce">'.retorna_data($obj->instrumento_data_publicacao, false).'</td></tr>';
	if ($exibir['instrumento_datas']) echo '</table></fieldset></td></tr>';
	}


if ($obj->instrumento_garantia_contratual_modalidade || $obj->instrumento_garantia_contratual_percentual || $obj->instrumento_garantia_contratual_vencimento){
	$numeracao=0;
	if ($exibir['instrumento_garantia_contratual']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_garantia_contratual_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	if ($exibir['instrumento_garantia_contratual_modalidade'] && $obj->instrumento_garantia_contratual_modalidade) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. Modalidade escolhida:</td><td class="realce">'.$obj->instrumento_garantia_contratual_modalidade.'</td></tr>';
	if ($exibir['instrumento_garantia_contratual_percentual'] && $obj->instrumento_garantia_contratual_percentual) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. Percentual(%):</td><td class="realce">'.number_format($obj->instrumento_garantia_contratual_percentual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	if ($exibir['instrumento_garantia_contratual_vencimento'] && $obj->instrumento_garantia_contratual_vencimento) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. Vencimento:</td><td class="realce">'.retorna_data($obj->instrumento_garantia_contratual_vencimento, false).'</td></tr>';
	if ($exibir['instrumento_garantia_contratual']) echo '</table></fieldset></td></tr>';
	}


$sql->adTabela('instrumento_edital');
$sql->adOnde('instrumento_edital_instrumento = '.(int)$instrumento_id);
$sql->adCampo('instrumento_edital_edital');
$sql->adOrdem('instrumento_edital_ordem');
$editals=$sql->carregarColuna();
$sql->limpar();
	
$sql->adTabela('instrumento_processo');
$sql->adOnde('instrumento_processo_instrumento = '.(int)$instrumento_id);
$sql->adCampo('instrumento_processo_processo');
$sql->adOrdem('instrumento_processo_ordem');
$processos=$sql->carregarColuna();
$sql->limpar();	
	
	
if ($obj->instrumento_licitacao || count($editals) || count($processos)){
	$numeracao=0;
	if ($exibir['instrumento_protocolo']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_protocolo_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	if ($exibir['instrumento_licitacao'] && $obj->instrumento_licitacao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_licitacao_leg'].':</td><td class="realce">'.getSisValorCampo('ModalidadeLicitacao',$obj->instrumento_licitacao).'</td></tr>';
	if ($exibir['instrumento_edital_nr'] && count($editals)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_edital_nr_leg'].':</td><td class="realce">'.implode('<br>', $editals).'</td></tr>';
	if ($exibir['instrumento_processo'] && count($processos)) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_processo_leg'].':</td><td class="realce">'.implode('<br>', $processos).'</td></tr>';
	if ($exibir['instrumento_protocolo']) echo '</table></fieldset></td></tr>';
	}


$numeracao=0;
if ($exibir['instrumento_dados']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_dados_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
if ($exibir['instrumento_cor']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_cor_leg'].':</td><td class="realce">'.$obj->instrumento_cor.'</td></tr>';
if ($exibir['instrumento_acesso']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Nível de Acesso', ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável  e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().':</td><td class="realce">'.(isset($niveis_acesso[$obj->instrumento_acesso]) ? $niveis_acesso[$obj->instrumento_acesso] : '').'</td></tr>';
for($i=0; $i<=100; $i++) $percentual[$i]=$i;
if ($exibir['instrumento_porcentagem']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_porcentagem_leg'].':</td><td class="realce">'.(isset($percentual[$obj->instrumento_porcentagem]) ? $percentual[$obj->instrumento_porcentagem].'%' : '').'</td></tr>';
if ($exibir['instrumento_contatos']) {
	$sql->adTabela('instrumento_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_contatos = $sql->carregarColuna();
	$sql->limpar();
	$saida_quem='';
	if ($instrumento_contatos && count($instrumento_contatos)) {
			$saida_quem.= link_contato($instrumento_contatos[0], '','','esquerda');
			$qnt_instrumento_contatos=count($instrumento_contatos);
			if ($qnt_instrumento_contatos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_instrumento_contatos; $i < $i_cmp; $i++) $lista.=link_contato($instrumento_contatos[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'instrumento_contatos\');">(+'.($qnt_instrumento_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="instrumento_contatos"><br>'.$lista.'</span>';
					}
			} 
	if($saida_quem)echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Contatos', 'Quais são os contatos d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Contato:'.dicaF().'</td><td class="realce">'.$saida_quem.'</td></tr>';
	}
if ($exibir['instrumento_recursos']) {
	$sql->adTabela('instrumento_recursos');
	$sql->adCampo('DISTINCT recurso_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_recursos = $sql->carregarColuna();
	$sql->limpar();
	$saida_recurso='';
	if ($instrumento_recursos && count($instrumento_recursos)) {

			$saida_recurso.= link_recurso($instrumento_recursos[0]);
			$qnt_lista_recursos=count($instrumento_recursos);
			if ($qnt_lista_recursos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($instrumento_recursos[$i]).'<br>';		
					$saida_recurso.= dica('Outros Indicadores', 'Clique para visualizar os demais recursos.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
					}
			} 
	if ($saida_recurso) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Recurso', 'Qual recurso está relacionado à '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Recurso:'.dicaF().'</td><td class="realce">'.$saida_recurso.'</td></tr>';
	}
if ($exibir['instrumento_relacionados']){
	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao.*');
	$sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
	$sql->adOrdem('instrumento_gestao_ordem');
	$lista = $sql->Lista();
	$sql->limpar();
	$qnt_gestao=0;	
	if (count($lista)) {
		echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Relacionad'.$config['genero_instrumento'], 'A que área '.$config['genero_instrumento'].' '.$config['instrumento'].' está relacionad'.$config['genero_instrumento'].'.').'Relacionad'.$config['genero_instrumento'].':'.dicaF().'</td><td class="realce">';	
		foreach($lista as $gestao_data){
			if ($gestao_data['instrumento_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']);
			elseif ($gestao_data['instrumento_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']);
			elseif ($gestao_data['instrumento_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']);
			elseif ($gestao_data['instrumento_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']);
			elseif ($gestao_data['instrumento_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']);
			elseif ($gestao_data['instrumento_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']);
			elseif ($gestao_data['instrumento_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']);
			elseif ($gestao_data['instrumento_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']);
			elseif ($gestao_data['instrumento_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']);
			elseif ($gestao_data['instrumento_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']);
			elseif ($gestao_data['instrumento_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']);
			elseif ($gestao_data['instrumento_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']);
			elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']);
			elseif ($gestao_data['instrumento_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']);
			elseif ($gestao_data['instrumento_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']);
			elseif ($gestao_data['instrumento_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']);
			elseif ($gestao_data['instrumento_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']);
			elseif ($gestao_data['instrumento_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['instrumento_gestao_mswot']);
			elseif ($gestao_data['instrumento_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']);
			elseif ($gestao_data['instrumento_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']);
			
			elseif ($gestao_data['instrumento_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['instrumento_gestao_semelhante']);
			
			elseif ($gestao_data['instrumento_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']);
			elseif ($gestao_data['instrumento_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['instrumento_gestao_problema']);
			elseif ($gestao_data['instrumento_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']);	
			elseif ($gestao_data['instrumento_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']);
			elseif ($gestao_data['instrumento_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']);
			elseif ($gestao_data['instrumento_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']);
			elseif ($gestao_data['instrumento_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']);
			elseif ($gestao_data['instrumento_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']);
			elseif ($gestao_data['instrumento_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']);
			elseif ($gestao_data['instrumento_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['instrumento_gestao_brainstorm']);
			elseif ($gestao_data['instrumento_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['instrumento_gestao_gut']);
			elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['instrumento_gestao_causa_efeito']);
			elseif ($gestao_data['instrumento_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']);
			elseif ($gestao_data['instrumento_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']);
			elseif ($gestao_data['instrumento_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']);
			elseif ($gestao_data['instrumento_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']);
			elseif ($gestao_data['instrumento_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']);
			elseif ($gestao_data['instrumento_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']);
			elseif ($gestao_data['instrumento_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['instrumento_gestao_template']);
			elseif ($gestao_data['instrumento_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['instrumento_gestao_painel']);
			elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']);
			elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']);		
			elseif ($gestao_data['instrumento_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']);	
			elseif ($gestao_data['instrumento_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']);	
			elseif ($gestao_data['instrumento_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['instrumento_gestao_acao_item']);	
			elseif ($gestao_data['instrumento_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['instrumento_gestao_beneficio']);	
			elseif ($gestao_data['instrumento_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['instrumento_gestao_painel_slideshow']);	
			elseif ($gestao_data['instrumento_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['instrumento_gestao_projeto_viabilidade']);	
			elseif ($gestao_data['instrumento_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['instrumento_gestao_projeto_abertura']);	
			elseif ($gestao_data['instrumento_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['instrumento_gestao_plano_gestao']);	
			elseif ($gestao_data['instrumento_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['instrumento_gestao_ssti']);
			elseif ($gestao_data['instrumento_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['instrumento_gestao_laudo']);
			elseif ($gestao_data['instrumento_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['instrumento_gestao_trelo']);
			elseif ($gestao_data['instrumento_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['instrumento_gestao_trelo_cartao']);
			elseif ($gestao_data['instrumento_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['instrumento_gestao_pdcl']);
			elseif ($gestao_data['instrumento_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['instrumento_gestao_pdcl_item']);	
			elseif ($gestao_data['instrumento_gestao_os']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/os_p.png').link_os($gestao_data['instrumento_gestao_os']);	
			}
		echo '</td></tr>';
		}	
	}
	
if ($exibir['instrumento_principal_indicador'] && $obj->instrumento_principal_indicador) echo '<tr><td align="right" style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_principal_indicador_leg'].':</td><td class="realce">'.link_indicador($obj->instrumento_principal_indicador).'</td></tr>';

if ($obj->instrumento_casa_significativa) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Casas Signfiicativas', 'Insira o número de casas significativas dos números d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Casas significativas:'.dicaF().'</td><td class="realce">'.$obj->instrumento_casa_significativa.'</td></tr>';

if ($Aplic->profissional && isset($moedas[$obj->instrumento_moeda])) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Moeda', 'A moeda padrão utilizada na meta.').'Moeda:'.dicaF().'</td><td class="realce">'.$moedas[$obj->instrumento_moeda].'</td></tr>';	

if ($exibir['instrumento_aprovado'] && $Aplic->profissional && $tem_aprovacao) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Aprovado', 'Se '.$config['genero_instrumento'].' '.$config['instrumento'].' se encontra aprovad'.$config['genero_instrumento'].'.').'Aprovad'.$config['genero_instrumento'].':'.dicaF().'</td><td class="realce">'.($obj->instrumento_aprovado ? 'Sim' : '<span style="color:#ff0000; font-weight:bold">Não</span>' ) . '</td></tr>';
if ($exibir['instrumento_ativo']) echo '<tr><td align=right width=50 style="white-space: nowrap">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_ativo_leg'].':</td><td class="realce">'.($obj->instrumento_ativo ? 'Sim' : 'Não').'</td></tr>';
if ($exibir['instrumento_dados']) echo '</table></fieldset></td></tr>';


require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('instrumento', $instrumento_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}	

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/instrumento/instrumento_ver_pro.php';


if ($Aplic->profissional) {
	require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
	exibir_alteracao('instrumento', $instrumento_id);
	}



echo '</form></table>';
if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';





function desenhaPlanilhaCustos($instrumento_id, $exibir){
  global $estilo_interface, $dialogo, $config, $estilo_texto, $estilo, $obj;
  
  $custosAVulso = loadCustoAVulso($instrumento_id);
  $custosTR = loadCustosOSTR($instrumento_id);
  
  //tem aditivo?
  $sql = new BDConsulta();
  $sql->adTabela('instrumento_avulso_custo');
  $sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
  $sql->esqUnir('instrumento_gestao', 'instrumento_gestao', 'instrumento_custo_instrumento=instrumento_gestao_instrumento');
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');
	$sql->adOnde('instrumento_gestao_semelhante ='.(int)$instrumento_id);
	$tem_aditivo=$sql->resultado();
	$sql->limpar();

  
  if(empty($custosTR) && empty($custosAVulso) && !($tem_aditivo > 0)) return;
  
  $desenho= '<table width="100%" cellpadding=2 cellspacing=0 class="tbl1" style="margin-top: 6px;">';
  $desenho.= '<thead><tr>';
  
  $desenho.= '<th '.$estilo_texto.'>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th '.$estilo_texto.'>' . dica('Descrição','Descrição do item.') . 'Descrição' . dicaF() . '</th>';
  $desenho.= '<th '.$estilo_texto.'>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF() . '</th>';
  $desenho.= '<th '.$estilo_texto.'>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>';
  $desenho.= '<th '.$estilo_texto.'>'.dica('Valor Unitário Atualizado', 'O valor de uma unidade do item atualizado.').'Unit. Atual'.dicaF().'</th>';
  $desenho.= '<th '.$estilo_texto.'>'.dica('Quantidade', 'A quantidade do ítem' ).'Qnt.'.dicaF().'</th>';
  $desenho.= '<th '.$estilo_texto.'>'.dica('Quantidade de meses', 'A quantidade de meses de serviço do ítem' ). 'Meses'.dicaF().'</th>';
  if( $config['bdi'] ) $desenho.= '<th '.$estilo_texto.'>' . dica('BDI','Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.') . 'BDI (%)' . dicaF() . '</th>';
  if($exibir['instrumento_avulso_custo_acrescimo']){
    $desenho.= '<th '.$estilo_texto.'>'.($exibir['instrumento_avulso_custo_percentual'] ? $exibir['instrumento_avulso_custo_acrescimo_leg2'] : $exibir['instrumento_avulso_custo_acrescimo_leg']). '</th>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Valor Total com Acréscimo', 'O valor total é o preço unitário multiplicado pela quantidade e pelo acréscimo.').'Total com Acréscimo'.dicaF(). '</th>';
  	}
  else $desenho.= '<th '.$estilo_texto.'>'. dica( 'Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.' ). 'Total'. dicaF(). '</th>';
  $desenho.= '<th '.$estilo_texto.'>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>';	

 
 
 	$desenho.= '</thead></tr>';
 
 

  $desenho.= '<tbody id="instrumento_lista_itens">';
  $tem_total = false;
  $custo = array();
  $total = array();
  $desenho.=desenhaCustosInstrumentoTR($custosTR, $custo, $total, $tem_total, $exibir);
  $desenho.=desenhaCustosAVulso($custosAVulso, $custo, $total, $tem_total, $exibir);
  $desenho.=desenhaAditivo($instrumento_id, $custo, $total, $tem_total, $exibir);
  
  if ($tem_total) $desenho.=desenhaTotaisPlanilhaCusto($custo, $total, 'Total Final', $exibir);
  
 	$desenho.='</tbody></table>';
 	return $desenho;
	}

function desenhaCustosInstrumentoTR($lista, &$custo, &$total, &$tem_total, $exibir) {
  global $config, $moedas, $estilo_texto, $estilo, $instrumento_id, $obj;
  
  $totalLocal=array();
	$custoLocal=array();
  
  if (($lista)) $saida= '<tr><td colspan=20 align="center" '.$estilo_texto.'><b>'.ucfirst($config['tr']).'</b></td></tr>';
  else $saida=null;
  $numeroLinha = 0;
  $trAtual = null;
  foreach( $lista as $linha ) {
    $quantidade = (float) $linha['instrumento_custo_quantidade'];
    if( (int) $linha['tr_custo_avulso'] ) {
      $tipo = 'travulso';
      $nome = $linha['tr_avulso_custo_nome'];
      $descricao = $linha['tr_avulso_custo_descricao'];
      $valorUnitario = (float) $linha['tr_avulso_custo_custo'];
      $valorUnitarioAtual = (float) $linha['tr_avulso_custo_custo_atual'];
      $valorAcrescimo = null;
      $bdi = (float) $linha['tr_avulso_custo_bdi'];
      $data = new CData($linha['tr_avulso_custo_data_limite']);
      $nd = $linha['tr_avulso_custo_nd'];
      $categoriaEconomica = $linha['tr_avulso_custo_categoria_economica'];
      $grupoDespesa = $linha['tr_avulso_custo_grupo_despesa'];
      $modalidadeAplicacao = $linha['tr_avulso_custo_modalidade_aplicacao'];
      $moeda = $linha['tr_avulso_custo_moeda'];
      $responsavel = $linha['responsavel_travulsocusto'];
      $quantidadeMeses = $linha['tr_avulso_custo_servico'] == 1 ? (float) $linha['tr_avulso_custo_meses'] : null;
    	$servico=$linha['tr_avulso_custo_servico'];
    	}
    else if( (int) $linha['tr_custo_tarefa'] ) {
      $tipo = 'tarefa';
      $nome = $linha['tarefa_custos_nome'];
      $descricao = $linha['tarefa_custos_descricao'];
      $valorUnitario = (float) $linha['tarefa_custos_custo'];
      $valorUnitarioAtual = null;
      $valorAcrescimo = null;
      $bdi = (float) $linha['tarefa_custos_bdi'];
      $data = new CData($linha['tarefa_custos_data_limite']);
      $nd = $linha['tarefa_custos_nd'];
      $categoriaEconomica = $linha['tarefa_custos_categoria_economica'];
      $grupoDespesa = $linha['tarefa_custos_grupo_despesa'];
      $modalidadeAplicacao = $linha['tarefa_custos_modalidade_aplicacao'];
      $moeda = $linha['tarefa_custos_moeda'];
      $responsavel = $linha['responsavel_tarefacusto'];
      $quantidadeMeses = null;
      $servico=null;
    	}
    else { //demanda
      $tipo = 'demanda';
      $nome = $linha['demanda_custo_nome'];
      $descricao = $linha['demanda_custo_descricao'];
      $valorUnitario = (float) $linha['demanda_custo_custo'];
      $valorUnitarioAtual = null;
      $valorAcrescimo = null;
      $bdi = (float) $linha['demanda_custo_bdi'];
      $data = new CData($linha['demanda_custo_data_limite']);
      $nd = $linha['demanda_custo_nd'];
      $categoriaEconomica = $linha['demanda_custo_categoria_economica'];
      $grupoDespesa = $linha['demanda_custo_grupo_despesa'];
      $modalidadeAplicacao = $linha['demanda_custo_modalidade_aplicacao'];
      $moeda = $linha['demanda_custo_moeda'];
      $responsavel = $linha['responsavel_demandacusto'];
      $quantidadeMeses = null;
      $servico=null;
    	}

    $custoId = (int) $linha['tr_custo_id'];
    $valorTotal = ( ( $quantidade * ($valorUnitarioAtual > 0 ? $valorUnitarioAtual : $valorUnitario) ) * ( ( 100 + $bdi ) / 100 ) );
    
    $valorTotal = ($servico ? $quantidadeMeses * $valorTotal : $valorTotal);
    
    
    if( $quantidadeMeses !== null ) $valorTotal *= $quantidadeMeses;
   	if( $valorTotal > 0 ) $tem_total = true;
    $saida.= '<tr>';
    if( $trAtual != $linha['tr_id'] ) {
      $trAtual = $linha['tr_id'];
      $saida .= '<td colspan=20 '.$estilo_texto.'><b>'.$linha['tr_nome'] . '</b></td></tr><tr>';
    	}
    $saida .= '<td align="left" '.$estilo_texto.'>' . ++$numeroLinha . ' - '.$nome . '</td>';
    $saida .= '<td align="left" '.$estilo_texto.'>' . ( $descricao ? $descricao : '&nbsp;' ) . '</td>';
    $nd = ( $categoriaEconomica && $grupoDespesa && $modalidadeAplicacao ? $categoriaEconomica . '.'.$grupoDespesa . '.'.$modalidadeAplicacao . '.' : '' ) . $nd;
    $saida .= '<td '.$estilo_texto.'>'.$nd . '</td>';
    $moedaTexto = array_key_exists( $moeda, $moedas ) ? $moedas[ $moeda ] : '&nbsp;';
    $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorUnitario,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
    $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($valorUnitarioAtual > 0 ? ($moedaTexto . ' ' .  number_format( $valorUnitarioAtual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.')) : '') . '</td>';
    $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format( $quantidade,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
    $saida .= '<td align=right '.$estilo_texto.'>' . ( $quantidadeMeses !== null ? $quantidadeMeses : '&nbsp;' ) . '</td>';
    if( $config['bdi'] ) $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format( $bdi,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

    if($exibir['instrumento_avulso_custo_acrescimo']){
        $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($valorAcrescimo !== null ? number_format($valorAcrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '') . '</td>';
        }

    $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorTotal,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
    $saida .= '<td '.$estilo_texto.'>'.$responsavel . '</td>';
    $saida .= '</tr>';
    if( isset( $custo[ $moeda ][ $nd ] ) ) $custo[ $moeda ][ $nd ] += (float) $valorTotal;
    else $custo[ $moeda ][ $nd ] = (float) $valorTotal;
 
   	if( isset( $total[ $moeda ] ) ) $total[ $moeda ] += $valorTotal;
    else $total[ $moeda ] = $valorTotal;
    
    if( isset($custoLocal[$moeda][$nd])) $custoLocal[$moeda][$nd] += (float) $valorTotal;
    else $custoLocal[$moeda][$nd] = (float) $valorTotal;
 
   	if( isset( $totalLocal[$moeda])) $totalLocal[$moeda] += $valorTotal;
    else $totalLocal[$moeda] = $valorTotal;
    }
    $saida.=desenhaTotaisPlanilhaCusto($custoLocal, $totalLocal, 'Total Parcial', $exibir);
	return $saida;
	}

function desenhaAditivo($instrumento_id, &$custo, &$total, &$tem_total, $exibir) {
  global $config, $moedas, $estilo_texto, $estilo, $instrumento_id, $numeroLinha, $obj;
  $saida=null;
	$totalLocal=array();
	$custoLocal=array();
	
	$sql = new BDConsulta();

	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao_instrumento');
	$sql->adOnde('instrumento_gestao_semelhante ='.(int)$instrumento_id);
	$aditivos=$sql->carregarColuna();
	$sql->limpar();
	
	$primeriro=true;
	
	foreach ($aditivos as $aditivo){
		$sql->adTabela('instrumento_avulso_custo');
		$sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
		$sql->adCampo('instrumento_custo_id, instrumento_avulso_custo.*, instrumento_custo_quantidade, instrumento_custo_aprovado');
		$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');	
		$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((instrumento_avulso_custo_acrescimo)/100)) END AS acrescimo');	
			
		$sql->adOnde('instrumento_custo_instrumento ='.(int)$aditivo);
		$sql->adOrdem('instrumento_custo_ordem');
		$linhas=$sql->Lista();
		$sql->limpar();
	
	
		if (count($linhas) && $primeriro) {
			$saida .= '<tr><td colspan=20 '.$estilo_texto.'><b>Aditivos</b></td></tr>';
			$primeriro=false;
			}
			
		if (count($linhas)) $saida .= '<tr><td colspan=20 '.$estilo_texto.'><b>'.nome_instrumento($aditivo).'</b></td></tr>';	
			
		foreach ($linhas as $linha) {
	    
			$saida.= '<tr>';
	    $saida.= '<td align="left" '.$estilo_texto.'>' . ++$numeroLinha . ' - '.$linha['instrumento_avulso_custo_nome'] . '</td>';
	    $saida.= '<td align="left" '.$estilo_texto.'>' . ( $linha['instrumento_avulso_custo_descricao'] ? $linha['instrumento_avulso_custo_descricao'] : '&nbsp;' ) . '</td>';
	    $nd=($linha['instrumento_avulso_custo_categoria_economica'] && $linha['instrumento_avulso_custo_grupo_despesa'] && $linha['instrumento_avulso_custo_modalidade_aplicacao'] ? $linha['instrumento_avulso_custo_categoria_economica'].'.'.$linha['instrumento_avulso_custo_grupo_despesa'].'.'.$linha['instrumento_avulso_custo_modalidade_aplicacao'].'.' : '').$linha['instrumento_avulso_custo_nd'];
	    $saida.= '<td '.$estilo_texto.'>'.$nd.'</td>';
	    $moedaTexto = array_key_exists( $linha['instrumento_avulso_custo_moeda'], $moedas ) ? $moedas[$linha['instrumento_avulso_custo_moeda']] : '&nbsp;';
	    $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $linha['instrumento_avulso_custo_custo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
	    $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($linha['instrumento_avulso_custo_custo_atual'] > 0 ? ($moedaTexto . ' ' .  number_format( $linha['instrumento_avulso_custo_custo_atual'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.')) : '') . '</td>';
	    $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format( $linha['instrumento_avulso_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
	    $saida.= '<td align=right '.$estilo_texto.'>'.($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses'] : '&nbsp;' ).'</td>';
	    if($config['bdi']) $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.number_format($linha['instrumento_avulso_custo_bdi'],2,',','.').'</td>';
      if($exibir['instrumento_avulso_custo_acrescimo']) $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'. number_format($linha['instrumento_avulso_custo_acrescimo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

	    $valorTotal=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
	    
	    $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorTotal,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
	    $saida.= '<td '.$estilo_texto.'>'.nome_usuario($linha['instrumento_avulso_custo_usuario']).'</td>';
	    $saida.= '</tr>';
	
	
			if (isset($custo[$linha['instrumento_avulso_custo_moeda']][$nd])) $custo[$linha['instrumento_avulso_custo_moeda']][$nd] += (float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $custo[$linha['instrumento_avulso_custo_moeda']][$nd]=(float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			
			if (isset($total[$linha['instrumento_avulso_custo_moeda']])) $total[$linha['instrumento_avulso_custo_moeda']]+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $total[$linha['instrumento_avulso_custo_moeda']]=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']); 
			
			
			if (isset($custoLocal[$linha['instrumento_avulso_custo_moeda']][$nd])) $custoLocal[$linha['instrumento_avulso_custo_moeda']][$nd] += (float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $custoLocal[$linha['instrumento_avulso_custo_moeda']][$nd]=(float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			
			if (isset($totalLocal[$linha['instrumento_avulso_custo_moeda']])) $totalLocal[$linha['instrumento_avulso_custo_moeda']]+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $totalLocal[$linha['instrumento_avulso_custo_moeda']]=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']); 
			
			
			if( $valorTotal > 0) $tem_total = true;
			
			
			//checar se está em OS
			$sql->adTabela('os_custo');
			$sql->esqUnir('os', 'os', 'os_custo_os=os_id');
			$sql->adCampo('os_nome, os_custo_quantidade');
			$sql->adOnde('os_custo_instrumento ='.(int)$linha['instrumento_custo_id']);
			$oss=$sql->lista();
			$sql->limpar();
			foreach($oss as $os) $saida.= '<tr><td align="left" colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;'.ucfirst($config['os']).':'.$os['os_nome'].'</td><td align=right>'.number_format($os['os_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td><td colspan=20></td></tr>';	
			}  
		}
		
		
	$saida.=desenhaTotaisPlanilhaCusto($custoLocal, $totalLocal, 'Total Parcial', $exibir);
	return $saida;
	}

function desenhaCustosAVulso($custosAVulso, &$custo, &$total, &$tem_total, $exibir) {
    global $config, $moedas, $estilo_texto, $estilo, $instrumento_id, $numeroLinha, $obj;
    $saida=null;

    $totalLocal=array();
    $custoLocal=array();

    $sql = new BDConsulta();

    if (count($custosAVulso)) $saida .= '<tr><td colspan=20 '.$estilo_texto.'><b>Itens à vulso</b></td></tr>';

    foreach ($custosAVulso as $linha) {
        $isServico = $linha[ 'instrumento_avulso_custo_servico' ] ? true : false;
        $quantidade = $linha[ 'instrumento_avulso_custo_quantidade' ];
        $parcelas =  $isServico ? $linha['instrumento_avulso_custo_meses'] : 1;
        $valorBDI = $linha[ 'instrumento_avulso_custo_bdi' ];

        $saida.= '<tr>';
        $saida.= '<td align="left" '.$estilo_texto.'>' . ++$numeroLinha . ' - '.$linha['instrumento_avulso_custo_nome'] . '</td>';
        $saida.= '<td align="left" '.$estilo_texto.'>' . ( $linha['instrumento_avulso_custo_descricao'] ? $linha['instrumento_avulso_custo_descricao'] : '&nbsp;' ) . '</td>';
        $nd=($linha['instrumento_avulso_custo_categoria_economica'] && $linha['instrumento_avulso_custo_grupo_despesa'] && $linha['instrumento_avulso_custo_modalidade_aplicacao'] ? $linha['instrumento_avulso_custo_categoria_economica'].'.'.$linha['instrumento_avulso_custo_grupo_despesa'].'.'.$linha['instrumento_avulso_custo_modalidade_aplicacao'].'.' : '').$linha['instrumento_avulso_custo_nd'];
        $saida.= '<td '.$estilo_texto.'>'.$nd.'</td>';
        $tipoMoeda = $linha[ 'instrumento_avulso_custo_moeda' ];
        $moedaTexto = array_key_exists( $tipoMoeda, $moedas ) ? $moedas[ $tipoMoeda ] : '&nbsp;';
        $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $linha['instrumento_avulso_custo_custo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
        $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($linha['instrumento_avulso_custo_custo_atual'] > 0 ? $moedaTexto.' '. number_format($linha['instrumento_avulso_custo_custo_atual'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '') . '</td>';

        $saida.= '<td align=right style="white-space: nowrap;' . $estilo . '">' . number_format( $quantidade,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

        $saida.= '<td align=right ' . $estilo_texto . '>' . ( $isServico ? $parcelas : '&nbsp;' ) . '</td>';


        if($config[ 'bdi']) $saida.= '<td align=right style="white-space: nowrap;' . $estilo . '">' . number_format($valorBDI,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
				$valorAcrescimo = $linha['instrumento_avulso_custo_acrescimo'];
        if($exibir['instrumento_avulso_custo_acrescimo']) $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format($valorAcrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
       

        $valorCusto = $linha[ 'instrumento_avulso_custo_custo_atual' ] > 0 ? $linha[ 'instrumento_avulso_custo_custo_atual' ] : $linha[ 'instrumento_avulso_custo_custo' ];

        $valorTotal = (($quantidade*$valorCusto)*((100+$valorBDI)/100));
        $valorTotal *= $parcelas;

        if($valorAcrescimo > 0){
          if($linha['instrumento_avulso_custo_percentual']) $valorTotal += $valorTotal * ($valorAcrescimo/100);
          else $valorTotal += $valorCusto * $valorAcrescimo;   
        	}

        $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' '.number_format( $valorTotal,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
        $saida.= '<td '.$estilo_texto.'>'.nome_usuario($linha['instrumento_avulso_custo_usuario']).'</td>';
        $saida.= '</tr>';


        if (isset( $custo[ $tipoMoeda ][ $nd]))$custo[ $tipoMoeda ][ $nd] += (float)( $parcelas * $linha[ 'valor']);
        else $custo[ $tipoMoeda ][ $nd]=(float)( $parcelas * $linha[ 'valor']);

        if (isset( $total[ $tipoMoeda ]))$total[ $tipoMoeda ]+=( $isServico
            ? $linha[ 'instrumento_avulso_custo_meses'] * $linha[ 'valor'] : $linha[ 'valor']);
        else $total[ $tipoMoeda ]=( $isServico
            ? $linha[ 'instrumento_avulso_custo_meses'] * $linha[ 'valor'] : $linha[ 'valor']);


        if (isset( $custoLocal[ $tipoMoeda ][ $nd]))$custoLocal[ $tipoMoeda ][ $nd] += (float)( $parcelas * $linha[ 'valor']);
        else $custoLocal[ $tipoMoeda ][ $nd]=(float)($parcelas *  $linha[ 'valor']);

        if (isset( $totalLocal[ $tipoMoeda ]))$totalLocal[ $tipoMoeda ]+=( $parcelas *  $linha[ 'valor']);
        else $totalLocal[ $tipoMoeda ]=( $parcelas *  $linha[ 'valor']);


        if( $valorTotal > 0) $tem_total = true;

        //checar se está em OS
        $sql->adTabela('os_custo');
        $sql->esqUnir('os', 'os', 'os_custo_os=os_id');
        $sql->adCampo('os_nome, os_custo_quantidade');
        $sql->adOnde('os_custo_instrumento ='.(int)$linha['instrumento_custo_id']);
        $oss=$sql->lista();
        $sql->limpar();
        foreach($oss as $os) $saida.= '<tr><td align="left" colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;'.ucfirst($config['os']).':'.$os['os_nome'].'</td><td align=right>'.number_format($os['os_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td><td colspan=20></td></tr>';
    }


    $saida.=desenhaTotaisPlanilhaCusto($custoLocal, $totalLocal, 'Total Parcial', $exibir);

    return $saida;
}

function desenhaTotaisPlanilhaCusto($custo, $total, $titulo, $exibir){
  global $moedas, $config, $estilo, $estilo_texto, $obj;
  $saidaND = '';
  $saidaTotal = '';
  foreach( $custo as $tipo_moeda => $linha ) {
    $saidaND .= '<div style="'.$estilo.'">';
    foreach( $linha as $indice_nd => $somatorio ) {
    	if( $somatorio > 0 ) $saidaND .= '<br>' . ( $indice_nd ? $indice_nd : 'Sem ND' ); 
    	}
    $saidaND .= '<br><b>Total</b></div>';
    $saidaTotal .= '<div style="white-space: nowrap;'.$estilo.'">';
    foreach( $linha as $indice_nd => $somatorio ) {
      if( $somatorio > 0 ) $saidaTotal .= '<br>'.$moedas[ $tipo_moeda ] . ' ' . number_format($somatorio,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'); 
    	}
    $saidaTotal .= '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></div>';
  	}

    $span = 6;
    if($config['bdi']) ++$span;
    if($exibir['instrumento_avulso_custo_acrescimo']) ++$span;

    if($saidaTotal) return '<tr><td colspan="'. $span . '" '.$estilo_texto.'><b>'.$titulo.'</b></td><td class="std" align="right"><div style="text-align: right; white-space: nowrap;'.$estilo.'">'. $saidaND. '</div></td><td><div style="text-align: right;'.$estilo.'">'.$saidaTotal.'</div></td><td colspan="20">&nbsp;</td></tr>';
	else return null;
	}

function loadCustosOSTR($instrumento_id){
  $sql = new BDConsulta();

  $sql->adTabela('instrumento_custo', 'oscusto');
  $sql->esqUnir('tr_custo', 'trcusto', 'trcusto.tr_custo_id = oscusto.instrumento_custo_tr');
  $sql->esqUnir('tr', 'tr', 'tr.tr_id = trcusto.tr_custo_tr');
  $sql->esqUnir('tr_avulso_custo', 'travulsocusto', 'travulsocusto.tr_avulso_custo_id = trcusto.tr_custo_avulso');
  $sql->esqUnir('tarefa_custos', 'tarefacusto', 'tarefacusto.tarefa_custos_id = trcusto.tr_custo_tarefa');
  $sql->esqUnir('demanda_custo', 'demandacusto', 'demandacusto.demanda_custo_id = trcusto.tr_custo_demanda');
  //responsável custo tr avulso
  $sql->esqUnir('usuarios', 'utravulso', 'utravulso.usuario_id = travulsocusto.tr_avulso_custo_usuario');
  $sql->esqUnir('contatos', 'ctravulso', 'ctravulso.contato_id = utravulso.usuario_contato');
  $sql->adCampo('concatenar_tres(ctravulso.contato_posto, \' \', ctravulso.contato_nomeguerra) AS responsavel_travulsocusto');
  //responsável custo tarefa
  $sql->esqUnir('usuarios', 'utarefacusto', 'utarefacusto.usuario_id = tarefacusto.tarefa_custos_usuario');
  $sql->esqUnir('contatos', 'ctarefacusto', 'ctarefacusto.contato_id = utarefacusto.usuario_contato');
  $sql->adCampo('concatenar_tres(ctarefacusto.contato_posto, \' \', ctarefacusto.contato_nomeguerra) AS responsavel_tarefacusto');
  //responsável custo demanda
  $sql->esqUnir('usuarios', 'udemandacusto', 'udemandacusto.usuario_id = demandacusto.demanda_custo_usuario');
  $sql->esqUnir('contatos', 'cdemandacusto', 'cdemandacusto.contato_id = udemandacusto.usuario_contato');
  $sql->adCampo('concatenar_tres(cdemandacusto.contato_posto, \' \', cdemandacusto.contato_nomeguerra) AS responsavel_demandacusto');
  $sql->adCampo('instrumento_custo_id, instrumento_custo_quantidade');
  $sql->adCampo('tr_id, tr_nome');
  $sql->adCampo('tr_custo_id, tr_custo_avulso, tr_custo_tarefa, tr_custo_demanda, tr_custo_quantidade');
  $sql->adCampo('tr_avulso_custo_nome, tr_avulso_custo_descricao, tr_avulso_custo_nd, tr_avulso_custo_custo, tr_avulso_custo_custo_atual, tr_avulso_custo_bdi, tr_avulso_custo_servico, tr_avulso_custo_meses, tr_avulso_custo_moeda, tr_avulso_custo_data_limite, tr_avulso_custo_categoria_economica, tr_avulso_custo_grupo_despesa, tr_avulso_custo_modalidade_aplicacao');
  $sql->adCampo('tarefa_custos_nome, tarefa_custos_descricao, tarefa_custos_nd, tarefa_custos_custo, tarefa_custos_bdi, tarefa_custos_moeda, tarefa_custos_data_limite, tarefa_custos_categoria_economica, tarefa_custos_grupo_despesa, tarefa_custos_modalidade_aplicacao');
  $sql->adCampo('demanda_custo_nome, demanda_custo_descricao, demanda_custo_nd, demanda_custo_data_limite, demanda_custo_custo, demanda_custo_bdi, demanda_custo_moeda, demanda_custo_categoria_economica, demanda_custo_grupo_despesa, demanda_custo_modalidade_aplicacao');
  $sql->adOnde('oscusto.instrumento_custo_tr IS NOT NULL');
  $sql->adOnde('oscusto.instrumento_custo_instrumento='.(int)$instrumento_id);
  $sql->adOrdem('tr_id, instrumento_custo_ordem');
  $lista=$sql->Lista();
  $sql->limpar();
  return $lista;
	}

function loadCustoAVulso($instrumento_id){
	$sql = new BDConsulta();

	$sql->adTabela('instrumento_avulso_custo');
	$sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
	$sql->adCampo('instrumento_custo_id, instrumento_avulso_custo.*, instrumento_custo_quantidade, instrumento_custo_aprovado');
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');	
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((instrumento_avulso_custo_acrescimo)/100)) END AS acrescimo');	
	$sql->adOnde('instrumento_custo_instrumento ='.(int)$instrumento_id);
	$sql->adOrdem('instrumento_custo_ordem');
	$linhas=$sql->Lista();
	$sql->limpar();
	return $linhas;
	}



if (!$dialogo) {
	$caixaTab = new CTabBox('m=instrumento&a=instrumento_ver&instrumento_id='.(int)$instrumento_id, '', $tab);
	$qnt_aba=0;
	
	if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_instrumento = '.(int)$instrumento_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
			}
		}

	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso')) {
		$sql->adTabela('financeiro_rel_nc');
		$sql->adOnde('financeiro_rel_nc_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_rel_nc_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_nc', 'NC',null,null,'Notas de Crédito','Visualizar as notas de crédito relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}
		
		$sql->adTabela('financeiro_rel_ne');
		$sql->adOnde('financeiro_rel_ne_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_rel_ne_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ne', 'NE',null,null,'Notas de Empenho','Visualizar as notas de empenho relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}
			
		$sql->adTabela('financeiro_estorno_rel_ne_fiplan');
		$sql->adOnde('financeiro_estorno_rel_ne_fiplan_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_estorno_rel_ne_fiplan_ne_estorno)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_estorno_ne', 'Estorno NE',null,null,'Estorno de Notas de Empenho','Visualizar os estornos das notas de empenho relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}	
				
		$sql->adTabela('financeiro_rel_ns');
		$sql->adOnde('financeiro_rel_ns_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_rel_ns_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ns', 'NL',null,null,'Notas de Liquidação','Visualizar as notas de liquidação relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}
			
						
		$sql->adTabela('financeiro_estorno_rel_ns_fiplan');
		$sql->adOnde('financeiro_estorno_rel_ns_fiplan_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_estorno_rel_ns_fiplan_ns_estorno)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_estorno_ns', 'Estorno NL',null,null,'Estorno de Notas de Liquidação','Visualizar os estornos das notas de liquidação relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}	
		
				
		$sql->adTabela('financeiro_rel_ob');
		$sql->adOnde('financeiro_rel_ob_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_rel_ob_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ob', 'OB',null,null,'Ordens Bancárias','Visualizar as ordens bancárias relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}
		
		$sql->adTabela('financeiro_estorno_rel_ob_fiplan');
		$sql->adOnde('financeiro_estorno_rel_ob_fiplan_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_estorno_rel_ob_fiplan_ob_estorno)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_estorno_ob', 'Estorno OB',null,null,'Estorno de Ordens Bancárias','Visualizar os estornos das ordens bancárias relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}		
		
		
		$sql->adTabela('financeiro_rel_gcv');
		$sql->esqUnir('financeiro_gcv', 'financeiro_gcv', 'financeiro_gcv_id=financeiro_rel_gcv_gcv');
		$sql->adOnde('NUMR_GCV_ESTORNO IS NULL');
		$sql->adOnde('financeiro_rel_gcv_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_rel_gcv_gcv)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_gcv', 'GVC',null,null,'Guia de Crédito de Verbas','Visualizar as guias de crédito de verbas relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}
		
		$sql->adTabela('financeiro_rel_gcv');
		$sql->esqUnir('financeiro_gcv', 'financeiro_gcv', 'financeiro_gcv_id=financeiro_rel_gcv_gcv');
		$sql->adOnde('NUMR_GCV_ESTORNO IS NOT NULL');
		$sql->adOnde('financeiro_rel_gcv_instrumento ='.(int)$instrumento_id);
		$sql->adCampo('count(financeiro_rel_gcv_gcv)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_estorno_gcv', 'Estorno de GVC',null,null,'Estorno de Guia de Crédito de Verbas','Visualizar os estornos das guias de crédito de verbas relacionadas a '.($config['genero_instrumento']=='a' ? 'esta ': 'este ').$config['instrumento'].'.');
			}
		
		
		
		}	
	
	
	
	

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {
			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_instrumento = '.(int)$instrumento_id);
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

			$sql->adTabela('arquivo_gestao','arquivo_gestao');
			$sql->adOnde('arquivo_gestao_instrumento = '.(int)$instrumento_id);
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

			$sql->adTabela('pratica_indicador_gestao','pratica_indicador_gestao');
			$sql->adOnde('pratica_indicador_gestao_instrumento = '.(int)$instrumento_id);
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

			$sql->adTabela('plano_acao_gestao','plano_acao_gestao');
			$sql->adOnde('plano_acao_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('plano_acao_item_gestao');
		$sql->adOnde('plano_acao_item_gestao_instrumento = '.(int)$instrumento_id);
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

			$sql->adTabela('projeto_gestao');
			$sql->adOnde('projeto_gestao_instrumento = '.(int)$instrumento_id);
			$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
			$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
			$sql->adOnde('projeto_portfolio=0');
			$sql->adCampo('count(projeto_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projeto']),null,null,ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.');
			}
		
		
		
		
		$sql->adTabela('projeto_gestao');
		$sql->adOnde('projeto_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
		$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
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
		$sql->adTabela('ata_gestao','ata_gestao');
		$sql->adOnde('ata_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('ata_gestao_ata IS NOT NULL');
		$sql->adCampo('count(ata_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Ata',null,null,'Ata','Visualizar a ata de reunião relacionada.');
			}
		}
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) {
		$sql->adTabela('demanda_gestao');
		$sql->adOnde('demanda_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('msg_gestao','msg_gestao');
		$sql->adOnde('msg_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('msg_gestao_msg IS NOT NULL');
		$sql->adCampo('count(msg_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg', ucfirst($config['mensagem']),null,null,ucfirst($config['mensagem']),'Visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.');
				}
		if ($config['doc_interno']) {
			$sql->adTabela('modelo_gestao','modelo_gestao');
			$sql->adOnde('modelo_gestao_instrumento = '.(int)$instrumento_id);
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

			$sql->adTabela('link_gestao','link_gestao');
			$sql->adOnde('link_gestao_instrumento = '.(int)$instrumento_id);
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

			$sql->adTabela('forum_gestao','forum_gestao');
			$sql->adOnde('forum_gestao_instrumento = '.(int)$instrumento_id);
			$sql->adOnde('forum_gestao_forum IS NOT NULL');
			$sql->adCampo('count(forum_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fórum',null,null,'Fórum','Visualizar o fórum relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema_gestao','problema_gestao');
		$sql->adOnde('problema_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('risco_gestao','risco_gestao');
		$sql->adOnde('risco_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('risco_resposta_gestao');
		$sql->adOnde('risco_resposta_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('instrumento_gestao','instrumento_gestao');
		$sql->adOnde('instrumento_gestao_semelhante = '.(int)$instrumento_id);
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
		$sql->adTabela('recurso_gestao');
		$sql->adOnde('recurso_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('patrocinador_gestao');
		$sql->adOnde('patrocinador_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('programa_gestao');
		$sql->adOnde('programa_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('beneficio_gestao');
		$sql->adOnde('beneficio_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('licao_gestao','licao_gestao');
		$sql->adOnde('licao_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('pratica_gestao');
		$sql->adOnde('pratica_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('tr_gestao');
		$sql->adOnde('tr_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('tr_gestao_tr IS NOT NULL');
		$sql->adCampo('count(tr_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/tr/tr_tabela','Termo de Referência',null,null,'Termo de Referência','Visualizar o termos de referência relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'brainstorm')) {
		$sql->adTabela('brainstorm_gestao');
		$sql->adOnde('brainstorm_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('gut_gestao');
		$sql->adOnde('gut_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('causa_efeito_gestao');
		$sql->adOnde('causa_efeito_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('tgn_gestao');
		$sql->adOnde('tgn_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('canvas_gestao');
		$sql->adOnde('canvas_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('mswot_gestao');
		$sql->adOnde('mswot_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('swot_gestao');
		$sql->adOnde('swot_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('operativo_gestao');
		$sql->adOnde('operativo_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('monitoramento_gestao');
		$sql->adOnde('monitoramento_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('avaliacao_gestao');
		$sql->adOnde('avaliacao_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('avaliacao_gestao_avaliacao IS NOT NULL');
		$sql->adCampo('count(avaliacao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/avaliacao_ver_idx','Avaliação',null,null,'Avaliação','Visualizar a avaliação de indicadores relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'checklist')) {
		$sql->adTabela('checklist_gestao');
		$sql->adOnde('checklist_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('agenda_gestao');
		$sql->adOnde('agenda_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('agrupamento_gestao');
		$sql->adOnde('agrupamento_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('template_gestao');
		$sql->adOnde('template_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('painel_gestao');
		$sql->adOnde('painel_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('painel_odometro_gestao');
		$sql->adOnde('painel_odometro_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('painel_odometro_gestao_painel_odometro IS NOT NULL');
		$sql->adCampo('count(painel_odometro_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/odometro_pro_lista_idx','Odômetro',null,null,'Odômetro','Visualizar o odômetro de indicador relacionado.');
			}
		}				
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'composicao_painel')) {
		$sql->adTabela('painel_composicao_gestao');
		$sql->adOnde('painel_composicao_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('painel_composicao_gestao_painel_composicao IS NOT NULL');
		$sql->adCampo('count(painel_composicao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_composicao_pro_lista_idx','Composição de painéis',null,null,'Composição de painéis','Visualizar a composição de painéis relacionada.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'slideshow_painel')) {
		$sql->adTabela('painel_slideshow_gestao');
		$sql->adOnde('painel_slideshow_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('painel_slideshow_gestao_painel_slideshow IS NOT NULL');
		$sql->adCampo('count(painel_slideshow_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_slideshow_pro_lista_idx','Slideshow',null,null,'Slideshow','Visualizar o slideshow de painéis relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('agenda', 'acesso', null, null)) {
		$sql->adTabela('calendario_gestao');
		$sql->adOnde('calendario_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('perspectiva_gestao');
		$sql->adOnde('perspectiva_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('tema_gestao');
		$sql->adOnde('tema_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('objetivo_gestao');
		$sql->adOnde('objetivo_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('me_gestao');
		$sql->adOnde('me_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('fator_gestao');
		$sql->adOnde('fator_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('estrategia_gestao');
		$sql->adOnde('estrategia_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('meta_gestao');
		$sql->adOnde('meta_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('plano_gestao_gestao');
		$sql->adOnde('plano_gestao_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('plano_gestao_gestao_plano_gestao IS NOT NULL');
		$sql->adCampo('count(plano_gestao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gestao/gestao_tabela','Planejamento estratégico',null,null,'Planejamento estratégico','Visualizar o planejamento estratégico relacionado.');
			}
		}				

	if ($Aplic->checarModulo('projetos', 'acesso', null, 'abertura')) {
		$sql->adTabela('projeto_abertura_gestao');
		$sql->adOnde('projeto_abertura_gestao_instrumento = '.(int)$instrumento_id);
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
		$sql->adTabela('projeto_viabilidade_gestao');
		$sql->adOnde('projeto_viabilidade_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IS NOT NULL');
		$sql->adCampo('count(projeto_viabilidade_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/viabilidade_tabela','Estudo de viabilidade',null,null,'Estudo de viabilidade','Visualizar o estudo de viabilidade relacionado.');
			}
		}		

	if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso')) {
		$sql->adTabela('ssti_gestao','ssti_gestao');
		$sql->adOnde('ssti_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('ssti_gestao_ssti IS NOT NULL');
		$sql->adCampo('count(ssti_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/ssti/ssti_tabela', ucfirst($config['ssti']),null,null,ucfirst($config['ssti']),'Visualizar '.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso',null, 'laudo')) {
		$sql->adTabela('laudo_gestao','laudo_gestao');
		$sql->adOnde('laudo_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('laudo_gestao_laudo IS NOT NULL');
		$sql->adCampo('count(laudo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/ssti/laudo_tabela', ucfirst($config['laudo']),null,null,ucfirst($config['laudo']),'Visualizar '.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'acesso')) {
		$sql->adTabela('trelo_gestao','trelo_gestao');
		$sql->adOnde('trelo_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('trelo_gestao_trelo IS NOT NULL');
		$sql->adCampo('count(trelo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/trelo/trelo_tabela', ucfirst($config['trelo']),null,null,ucfirst($config['trelo']),'Visualizar '.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'acesso')) {
		$sql->adTabela('trelo_cartao_gestao','trelo_cartao_gestao');
		$sql->adOnde('trelo_cartao_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('trelo_cartao_gestao_trelo_cartao IS NOT NULL');
		$sql->adCampo('count(trelo_cartao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/trelo/trelo_cartao_tabela', ucfirst($config['trelo_cartao']),null,null,ucfirst($config['trelo_cartao']),'Visualizar '.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'acesso')) {
		$sql->adTabela('pdcl_gestao','pdcl_gestao');
		$sql->adOnde('pdcl_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('pdcl_gestao_pdcl IS NOT NULL');
		$sql->adCampo('count(pdcl_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_tabela', ucfirst($config['pdcl']),null,null,ucfirst($config['pdcl']),'Visualizar '.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'acesso')) {
		$sql->adTabela('pdcl_item_gestao','pdcl_item_gestao');
		$sql->adOnde('pdcl_item_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('pdcl_item_gestao_pdcl_item IS NOT NULL');
		$sql->adCampo('count(pdcl_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_item_tabela', ucfirst($config['pdcl_item']),null,null,ucfirst($config['pdcl_item']),'Visualizar '.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.');
			}
		}	
		
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('os') && $Aplic->checarModulo('os', 'acesso')) {
		$sql->adTabela('os_gestao','os_gestao');
		$sql->adOnde('os_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('os_gestao_os IS NOT NULL');
		$sql->adCampo('count(os_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/os/os_tabela', ucfirst($config['os']),null,null,ucfirst($config['os']),'Visualizar '.$config['genero_os'].' '.$config['os'].' relacionad'.$config['genero_os'].'.');
			}
		}		
		
	if ($qnt_aba) {
		$caixaTab->mostrar('','','','',true);
		echo estiloFundoCaixa('','', $tab);
		}	

	}
?>
<script type="text/javascript">

function popExtrato(tipo, id){
	if (tipo=='ne') titulo='Extrato de Notas de Empenho';
	else if (tipo=='ns') titulo='Extrato de Notas de Liquidação';
	else titulo='Extrato de Notas de Ordem Bancária';
	window.parent.gpwebApp.popUp(titulo, 950, 700, 'm=financeiro&a=extrato_pro&dialogo=1&modulo=instrumento&id='+id+'&tipo='+tipo, null, window);
	}



function popNE(financeiro_ne_id){
	window.parent.gpwebApp.popUp("Nota de Empenho", 950, 700, 'm=financeiro&a=siafi_ne_detalhe_pro&dialogo=1&os_id='+<?php echo $instrumento_id?>+'&financeiro_ne_id='+financeiro_ne_id, null, window);
	}

function popNS(financeiro_ns_id){
	window.parent.gpwebApp.popUp("Nota de Liquidação", 950, 700, 'm=financeiro&a=siafi_ns_detalhe_pro&dialogo=1&os_id='+<?php echo $instrumento_id?>+'&financeiro_ns_id='+financeiro_ns_id, null, window);
	}	
	
function popOB(financeiro_ob_id){
	window.parent.gpwebApp.popUp("Ordem Bancária", 950, 700, 'm=financeiro&a=siafi_ob_detalhe_pro&dialogo=1&os_id='+<?php echo $instrumento_id?>+'&financeiro_ob_id='+financeiro_ob_id, null, window);
	}	



function mudar_tipo(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Mudar Tipo", 400, 300, 'm=instrumento&a=instrumento_selecao_pro&chamar_volta=setMudar_tipo', window.setMudar_tipo, window);
	else url_passar(0, 'm=instrumento&a=instrumento_editar&instrumento_campo=1');
	}


function setMudar_tipo(tipo){
	xajax_mudar_tipo(document.getElementById('instrumento_id').value, tipo);
	url_passar(0, 'm=instrumento&a=instrumento_ver&instrumento_id='+document.getElementById('instrumento_id').value);
	}



function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo=generico', null, window);
	}	
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
	
function excluir() {
	if (confirm("Tem certeza que deseja excluir?")) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}
	
function mudar_percentagem(instrumento_id, valor){
	xajax_mudar_percentagem(instrumento_id, valor);
	}

function mudar_status(instrumento_id, valor){
	xajax_mudar_status(instrumento_id, valor);
	}	
	
function clonar(){
  var nome_instrumento = prompt("Nome d<?php echo $config['genero_instrumento'].' '.$config['instrumento'] ?>:","");
  xajax_instrumento_existe(nome_instrumento, document.getElementById('instrumento_id').value, document.getElementById('cia_id').value);
  if (nome_instrumento && document.getElementById("existe_instrumento").value==0){
      xajax_criar_instrumento(nome_instrumento, document.getElementById('instrumento_id').value);
      
      if (document.getElementById('instrumento_criado').value > 0) url_passar(0, 'm=instrumento&a=instrumento_editar&instrumento_id='+document.getElementById('instrumento_criado').value);
      else alert('Houve problema na clonagem');
      }
  else if (document.getElementById('existe_instrumento').value > 0) alert("Já existe <?php echo $config['instrumento'].' com este nome.'?>");
  else alert('Escreva um nome válido.');
	}	
		
</script>
