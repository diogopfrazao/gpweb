<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');

$sql = new BDConsulta;

$_REQUEST['viabilidade_ativo']=(isset($_REQUEST['viabilidade_ativo']) ? 1 : 0);

$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$projeto_viabilidade_id=getParam($_REQUEST, 'projeto_viabilidade_id', null);

$obj = new CViabilidade();
if ($projeto_viabilidade_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=viabilidade_lista');
	}
$Aplic->setMsg('Estudo de viabilidade');
if ($excluir) {
	$obj->load($projeto_viabilidade_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id);
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=viabilidade_lista');
		}
	}
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_viabilidade_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_viabilidade_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
$obj->setSequencial();	


if ($Aplic->profissional && !$projeto_viabilidade_id && $obj->projeto_viabilidade_id){
	$sql->adTabela('demanda_custo');
	$sql->adCampo('demanda_custo.*');
	$sql->adOnde('demanda_custo_demanda = '.(int)$obj->projeto_viabilidade_demanda);
	$lista = $sql->lista();
	$sql->limpar();
	foreach ($lista as $linha){
		$sql->adTabela('projeto_viabilidade_custo');
		$sql->adInserir('projeto_viabilidade_custo_projeto_viabilidade', $obj->projeto_viabilidade_id);
		$sql->adInserir('projeto_viabilidade_custo_nome', $linha['demanda_custo_nome']);	
		$sql->adInserir('projeto_viabilidade_custo_tipo', $linha['demanda_custo_tipo']);
		$sql->adInserir('projeto_viabilidade_custo_quantidade', $linha['demanda_custo_quantidade']);
		$sql->adInserir('projeto_viabilidade_custo_custo', $linha['demanda_custo_custo']);
		$sql->adInserir('projeto_viabilidade_custo_descricao', $linha['demanda_custo_descricao']);
		$sql->adInserir('projeto_viabilidade_custo_nd', $linha['demanda_custo_nd']);
		$sql->adInserir('projeto_viabilidade_custo_categoria_economica', $linha['demanda_custo_categoria_economica']);
		$sql->adInserir('projeto_viabilidade_custo_grupo_despesa', $linha['demanda_custo_grupo_despesa']);
		$sql->adInserir('projeto_viabilidade_custo_modalidade_aplicacao', $linha['demanda_custo_modalidade_aplicacao']);
		$sql->adInserir('projeto_viabilidade_custo_data_limite', $linha['demanda_custo_data_limite']);
		$sql->adInserir('projeto_viabilidade_custo_usuario', $linha['demanda_custo_usuario']);
		$sql->adInserir('projeto_viabilidade_custo_data', date('Y-m-d H:i:s'));
		$sql->adInserir('projeto_viabilidade_custo_bdi', $linha['demanda_custo_bdi']);
		$sql->adInserir('projeto_viabilidade_custo_moeda', $linha['demanda_custo_moeda']);
		$sql->adInserir('projeto_viabilidade_custo_data_moeda', $linha['demanda_custo_data_moeda']);
		$sql->adInserir('projeto_viabilidade_custo_cotacao', $linha['demanda_custo_cotacao']);
		$sql->adInserir('projeto_viabilidade_custo_codigo', $linha['demanda_custo_codigo']);
		$sql->adInserir('projeto_viabilidade_custo_fonte', $linha['demanda_custo_fonte']);
		$sql->adInserir('projeto_viabilidade_custo_regiao', $linha['demanda_custo_regiao']);
		$sql->adInserir('projeto_viabilidade_custo_ordem', $linha['demanda_custo_ordem']);
		$sql->exec();
		$sql->limpar();
		}
	}	


$sql->adTabela('projeto_viabilidade_gestao');
$sql->adCampo('projeto_viabilidade_gestao.*');
$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade='.(int)$obj->projeto_viabilidade_id);
$sql->adOrdem('projeto_viabilidade_gestao_ordem ASC');
$linha=$sql->linha();
$sql->limpar();

$sql->adTabela('projeto_viabilidade_gestao');
$sql->adCampo('count(projeto_viabilidade_gestao_id)');
$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade='.(int)$obj->projeto_viabilidade_id);
$qnt=$sql->Resultado();
$sql->limpar();

if ($linha!=null && $linha['projeto_viabilidade_gestao_tarefa'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['projeto_viabilidade_gestao_tarefa'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_projeto'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['projeto_viabilidade_gestao_projeto'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_perspectiva'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['projeto_viabilidade_gestao_perspectiva'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_tema'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['projeto_viabilidade_gestao_tema'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_objetivo'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['projeto_viabilidade_gestao_objetivo'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_fator'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['projeto_viabilidade_gestao_fator'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_estrategia'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['projeto_viabilidade_gestao_estrategia'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_meta'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['projeto_viabilidade_gestao_meta'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_pratica'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['projeto_viabilidade_gestao_pratica'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_indicador'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['projeto_viabilidade_gestao_indicador'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_acao'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['projeto_viabilidade_gestao_acao'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_canvas'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['projeto_viabilidade_gestao_canvas'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_risco'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['projeto_viabilidade_gestao_risco'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_risco_resposta'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['projeto_viabilidade_gestao_risco_resposta'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_calendario'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['projeto_viabilidade_gestao_calendario'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_monitoramento'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['projeto_viabilidade_gestao_monitoramento'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_ata'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['projeto_viabilidade_gestao_ata'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_mswot'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['projeto_viabilidade_gestao_mswot'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_swot'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['projeto_viabilidade_gestao_swot'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_operativo'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['projeto_viabilidade_gestao_operativo'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_instrumento'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['projeto_viabilidade_gestao_instrumento'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_recurso'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['projeto_viabilidade_gestao_recurso'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_problema'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['projeto_viabilidade_gestao_problema'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_demanda'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['projeto_viabilidade_gestao_demanda'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_programa'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['projeto_viabilidade_gestao_programa'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_licao'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['projeto_viabilidade_gestao_licao'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_evento'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=calendario&a=ver&evento_id='.$linha['projeto_viabilidade_gestao_evento'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_link'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=links&a=ver&link_id='.$linha['projeto_viabilidade_gestao_link'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_avaliacao'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['projeto_viabilidade_gestao_avaliacao'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_tgn'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['projeto_viabilidade_gestao_tgn'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_brainstorm'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['projeto_viabilidade_gestao_brainstorm'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_gut'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['projeto_viabilidade_gestao_gut'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_causa_efeito'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['projeto_viabilidade_gestao_causa_efeito'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_arquivo'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['projeto_viabilidade_gestao_arquivo'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_forum'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=foruns&a=ver&forum_id='.$linha['projeto_viabilidade_gestao_forum'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_checklist'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['projeto_viabilidade_gestao_checklist'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_agenda'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['projeto_viabilidade_gestao_agenda'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_agrupamento'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['projeto_viabilidade_gestao_agrupamento'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_patrocinador'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['projeto_viabilidade_gestao_patrocinador'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_template'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['projeto_viabilidade_gestao_template'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_painel'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['projeto_viabilidade_gestao_painel'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_painel_odometro'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['projeto_viabilidade_gestao_painel_odometro'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_painel_composicao'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['projeto_viabilidade_gestao_painel_composicao'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_tr'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['projeto_viabilidade_gestao_tr'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_me'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['projeto_viabilidade_gestao_me'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_acao_item'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['projeto_viabilidade_gestao_acao_item'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_beneficio'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['projeto_viabilidade_gestao_beneficio'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_painel_slideshow'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['projeto_viabilidade_gestao_painel_slideshow'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_projeto_viabilidade'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['projeto_viabilidade_gestao_projeto_viabilidade'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_projeto_abertura'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['projeto_viabilidade_gestao_projeto_abertura'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_plano_gestao'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['projeto_viabilidade_gestao_plano_gestao'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_ssti'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['projeto_viabilidade_gestao_ssti'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_laudo'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['projeto_viabilidade_gestao_laudo'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_trelo'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['projeto_viabilidade_gestao_trelo'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_trelo_cartao'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['projeto_viabilidade_gestao_trelo_cartao'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_pdcl'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['projeto_viabilidade_gestao_pdcl'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_pdcl_item'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['projeto_viabilidade_gestao_pdcl_item'];
elseif ($linha!=null && $linha['projeto_viabilidade_gestao_os'] && $qnt==1 && !$projeto_viabilidade_id) $endereco='m=os&a=os_ver&os_id='.$linha['projeto_viabilidade_gestao_os'];

else $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.(int)$obj->projeto_viabilidade_id;
$Aplic->redirecionar($endereco);
?>