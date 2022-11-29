<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


global $podeEditar, $usuario_id, 
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;

$indicador_expandido=getParam($_REQUEST, 'indicador_expandido', 0);

if (isset($_REQUEST['somente_superiores'])) $Aplic->setEstado('somente_superiores', getParam($_REQUEST, 'somente_superiores', null));
$somente_superiores = $Aplic->getEstado('somente_superiores') !== null ? $Aplic->getEstado('somente_superiores') : 0;


$ordenar=getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta;

$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_acesso, pratica_indicador_externo, pratica_indicador_nome, pratica_indicador_unidade, pratica_indicador_sentido, pratica_indicador_periodo_anterior, pratica_indicador_tolerancia, pratica_indicador_acumulacao, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, pratica_indicador_cia, pratica_indicador_dept, pratica_indicador_codigo, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_agrupar, pratica_indicador_aprovado, pratica_indicador_ativo');

$sql->adCampo('pratica_indicador_requisito_descricao, pratica_indicador_requisito_oque, pratica_indicador_requisito_onde, pratica_indicador_requisito_quando, pratica_indicador_requisito_como, pratica_indicador_requisito_porque,
	pratica_indicador_requisito_quanto, pratica_indicador_requisito_quem, pratica_indicador_requisito_melhorias');

if ($usuario_id) {
	$sql->esqUnir('pratica_indicador_usuarios','pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->adOnde('pratica_indicador_responsavel IN ('.$usuario_id.') OR pratica_indicador_usuarios.usuario_id IN ('.$usuario_id.')');
	}


if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
if ($indicador_expandido) $sql->adCampo($indicador_expandido.'=pratica_indicador.pratica_indicador_id AS pai');
if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);

$sql->esqUnir('pratica_indicador_gestao','pratica_indicador_gestao','pratica_indicador_gestao_indicador = pratica_indicador.pratica_indicador_id');
if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id && !$indicador_expandido){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=pratica_indicador_gestao_tarefa');
	$sql->adOnde('pratica_indicador_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica IN ('.$pratica_id.')');

elseif ($pratica_indicador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_semelhante IN ('.$pratica_indicador_id.')');

elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento IN ('.$evento_id.')');
elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template IN ('.$template_id.')');
elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr IN ('.$tr_id.')');
elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
elseif ($os_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_os IN ('.$os_id.')');		

$sql->adCampo('(SELECT formatar_data(MAX(pratica_indicador_valor_data), \'%d/%m/%Y\') FROM pratica_indicador_valor WHERE pratica_indicador_valor_indicador=pratica_indicador.pratica_indicador_id) AS ultima_data');	
if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_indicador=pratica_indicador.pratica_indicador_id) AS tem_aprovacao');		
$sql->adGrupo('pratica_indicador.pratica_indicador_id');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$indicadores = $sql->lista();
$sql->limpar();

$detalhe_projeto=1;


include_once BASE_DIR.'/modulos/praticas/indicadores_ver_idx.php';