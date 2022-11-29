<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

require_once (BASE_DIR.'/modulos/praticas/plano_acao.class.php');

$sql = new BDConsulta;

$_REQUEST['plano_acao_ativo']=(isset($_REQUEST['plano_acao_ativo']) ? 1 : 0);

$del = intval(getParam($_REQUEST, 'del', 0));
$plano_acao_id=getParam($_REQUEST, 'plano_acao_id', null);

$uuid=getParam($_REQUEST, 'uuid', null);

$comentario=getParam($_REQUEST, 'email_comentario', '');


if (isset($_REQUEST['plano_acao_duracao'])) $_REQUEST['plano_acao_duracao']=float_americano(getParam($_REQUEST, 'plano_acao_duracao', null))*$config['horas_trab_diario'];
$obj = new CPlanoAcao();
if ($plano_acao_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=7&plano_acao_id='.$plano_acao_id);
	}
	
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->plano_acao_codigo=$codigo;


$Aplic->setMsg('Plano de A��o');
if ($del) {
	if ($Aplic->profissional){
		$sql->adTabela('plano_acao_observador');
		$sql->adCampo('plano_acao_observador.*');
		$sql->adOnde('plano_acao_observador_plano_acao ='.(int)$plano_acao_id);
		$lista = $sql->lista();
		$sql->limpar();
		}

	$obj->load($plano_acao_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		
		if ($Aplic->profissional){
			
			
			$qnt_projeto=0;
			$qnt_programa=0;
			$qnt_perspectiva=0;
			$qnt_tema=0;
			$qnt_objetivo=0;
			$qnt_me=0;
			$qnt_fator=0;
			$qnt_estrategia=0;
			$qnt_meta=0;
			$qnt_acao=0;
			$qnt_risco=0;
			$qnt_risco_resposta=0;
			$qnt_pdcl_item=0;
			
			foreach($lista as $linha){
				
				if ($linha['plano_acao_observador_projeto']){
					if (!($qnt_projeto++)) require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
					$obj= new CProjeto();
					$obj->load($linha['plano_acao_observador_projeto']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_programa']){
					if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
					$obj= new CPrograma();
					$obj->load($linha['plano_acao_observador_programa']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_risco']){
					if (!($qnt_risco++)) require_once BASE_DIR.'/modulos/praticas/risco_pro.class.php';
					$obj= new CRisco();
					$obj->load($linha['plano_acao_observador_risco']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				
				elseif ($linha['plano_acao_observador_risco_resposta']){
					if (!($qnt_risco_resposta++)) require_once BASE_DIR.'/modulos/praticas/risco_resposta_pro.class.php';
					$obj= new CRiscoResposta();
					$obj->load($linha['plano_acao_observador_risco_resposta']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}		
					
				elseif ($linha['plano_acao_observador_pdcl_item']){
					if (!($qnt_pdcl_item++)) require_once BASE_DIR.'/modulos/pdcl/pdcl_item.class.php';
					$obj= new CPDCLItem();
					$obj->load($linha['plano_acao_observador_pdcl_item']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}		
					
					
				elseif ($linha['plano_acao_observador_perspectiva']){
					if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
					$obj= new CPerspectiva();
					$obj->load($linha['plano_acao_observador_perspectiva']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_tema']){
					if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
					$obj= new CTema();
					$obj->load($linha['plano_acao_observador_tema']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj= new CObjetivo();
					$obj->load($linha['plano_acao_observador_objetivo']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj= new CMe();
					$obj->load($linha['plano_acao_observador_me']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}		
				elseif ($linha['plano_acao_observador_fator']){
					if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
					$obj= new CFator();
					$obj->load($linha['plano_acao_observador_fator']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_estrategia']){
					if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
					$obj= new CEstrategia();
					$obj->load($linha['plano_acao_observador_estrategia']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}	
				elseif ($linha['plano_acao_observador_meta']){
					if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
					$obj= new CMeta();
					$obj->load($linha['plano_acao_observador_meta']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->{$linha['plano_acao_observador_metodo']}();
						}
					}				
				}
			}
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		}
	if ($dialogo){
		echo '<script type="text/javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		} 
	$Aplic->redirecionar('m=praticas&a=plano_acao_lista');
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	if (getParam($_REQUEST, 'email_responsavel', 0)) $obj->notificarResponsavel($comentario, $plano_acao_id);
	if (getParam($_REQUEST, 'email_designados', 0)) $obj->notificarDesignados($comentario, $plano_acao_id);
	if (getParam($_REQUEST, 'email_contatos', 0)) $obj->notificarContatos($comentario, $plano_acao_id);
	$Aplic->setMsg($plano_acao_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	
		
if ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}


//checagem se todas as linhas tem data de inicio e t�rmino
$sql->adTabela('plano_acao_item');
$sql->adCampo('count(plano_acao_item_id)');
$sql->adOnde('plano_acao_item_inicio IS NULL OR plano_acao_item_fim IS NULL');
$sql->adOnde('plano_acao_item_acao='.(int)$obj->plano_acao_id);
$tem_vazio=$sql->resultado();
$sql->limpar();

if (($obj->plano_acao_data_apenas && $tem_vazio) || (!$obj->plano_acao_data_apenas && !$tem_vazio)){
	$sql->adTabela('plano_acao');
	$sql->adAtualizar('plano_acao_data_apenas', ($tem_vazio ? 0 : 1));
	$sql->adOnde('plano_acao_id='.(int)$obj->plano_acao_id);
	$sql->exec();
	$sql->limpar();
	}

	

	
$sql->adTabela('plano_acao_gestao');
$sql->adCampo('plano_acao_gestao.*');
$sql->adOnde('plano_acao_gestao_acao IN ('.$obj->plano_acao_id.')');
$sql->adOrdem('plano_acao_gestao_ordem ASC');
$linha=$sql->linha();
$sql->limpar();

$sql->adTabela('plano_acao_gestao');
$sql->adCampo('count(plano_acao_gestao_id)');
$sql->adOnde('plano_acao_gestao_acao IN ('.$obj->plano_acao_id.')');
$qnt=$sql->Resultado();
$sql->limpar();

if ($linha!=null && $linha['plano_acao_gestao_tarefa'] && $qnt==1 && !$plano_acao_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['plano_acao_gestao_tarefa'];
elseif ($linha!=null && $linha['plano_acao_gestao_projeto'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['plano_acao_gestao_projeto'];
elseif ($linha!=null && $linha['plano_acao_gestao_perspectiva'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['plano_acao_gestao_perspectiva'];
elseif ($linha!=null && $linha['plano_acao_gestao_tema'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['plano_acao_gestao_tema'];
elseif ($linha!=null && $linha['plano_acao_gestao_objetivo'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['plano_acao_gestao_objetivo'];
elseif ($linha!=null && $linha['plano_acao_gestao_fator'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['plano_acao_gestao_fator'];
elseif ($linha!=null && $linha['plano_acao_gestao_estrategia'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['plano_acao_gestao_estrategia'];
elseif ($linha!=null && $linha['plano_acao_gestao_meta'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['plano_acao_gestao_meta'];
elseif ($linha!=null && $linha['plano_acao_gestao_pratica'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['plano_acao_gestao_pratica'];
elseif ($linha!=null && $linha['plano_acao_gestao_indicador'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['plano_acao_gestao_indicador'];

elseif ($linha!=null && $linha['plano_acao_gestao_semelhante'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['plano_acao_gestao_semelhante'];

elseif ($linha!=null && $linha['plano_acao_gestao_canvas'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['plano_acao_gestao_canvas'];
elseif ($linha!=null && $linha['plano_acao_gestao_risco'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['plano_acao_gestao_risco'];
elseif ($linha!=null && $linha['plano_acao_gestao_risco_resposta'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['plano_acao_gestao_risco_resposta'];
elseif ($linha!=null && $linha['plano_acao_gestao_calendario'] && $qnt==1 && !$plano_acao_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['plano_acao_gestao_calendario'];
elseif ($linha!=null && $linha['plano_acao_gestao_monitoramento'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['plano_acao_gestao_monitoramento'];
elseif ($linha!=null && $linha['plano_acao_gestao_ata'] && $qnt==1 && !$plano_acao_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['plano_acao_gestao_ata'];
elseif ($linha!=null && $linha['plano_acao_gestao_mswot'] && $qnt==1 && !$plano_acao_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['plano_acao_gestao_mswot'];
elseif ($linha!=null && $linha['plano_acao_gestao_swot'] && $qnt==1 && !$plano_acao_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['plano_acao_gestao_swot'];
elseif ($linha!=null && $linha['plano_acao_gestao_operativo'] && $qnt==1 && !$plano_acao_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['plano_acao_gestao_operativo'];
elseif ($linha!=null && $linha['plano_acao_gestao_instrumento'] && $qnt==1 && !$plano_acao_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['plano_acao_gestao_instrumento'];
elseif ($linha!=null && $linha['plano_acao_gestao_recurso'] && $qnt==1 && !$plano_acao_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['plano_acao_gestao_recurso'];
elseif ($linha!=null && $linha['plano_acao_gestao_problema'] && $qnt==1 && !$plano_acao_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['plano_acao_gestao_problema'];
elseif ($linha!=null && $linha['plano_acao_gestao_demanda'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['plano_acao_gestao_demanda'];
elseif ($linha!=null && $linha['plano_acao_gestao_licao'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['plano_acao_gestao_licao'];
elseif ($linha!=null && $linha['plano_acao_gestao_programa'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['plano_acao_gestao_programa'];
elseif ($linha!=null && $linha['plano_acao_gestao_evento'] && $qnt==1 && !$plano_acao_id) $endereco='m=calendario&a=ver&evento_id='.$linha['plano_acao_gestao_evento'];
elseif ($linha!=null && $linha['plano_acao_gestao_link'] && $qnt==1 && !$plano_acao_id) $endereco='m=links&a=ver&link_id='.$linha['plano_acao_gestao_link'];
elseif ($linha!=null && $linha['plano_acao_gestao_avaliacao'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['plano_acao_gestao_avaliacao'];
elseif ($linha!=null && $linha['plano_acao_gestao_tgn'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['plano_acao_gestao_tgn'];
elseif ($linha!=null && $linha['plano_acao_gestao_brainstorm'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['plano_acao_gestao_brainstorm'];
elseif ($linha!=null && $linha['plano_acao_gestao_gut'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['plano_acao_gestao_gut'];
elseif ($linha!=null && $linha['plano_acao_gestao_causa_efeito'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['plano_acao_gestao_causa_efeito'];
elseif ($linha!=null && $linha['plano_acao_gestao_arquivo'] && $qnt==1 && !$plano_acao_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['plano_acao_gestao_arquivo'];
elseif ($linha!=null && $linha['plano_acao_gestao_forum'] && $qnt==1 && !$plano_acao_id) $endereco='m=foruns&a=ver&forum_id='.$linha['plano_acao_gestao_forum'];
elseif ($linha!=null && $linha['plano_acao_gestao_checklist'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['plano_acao_gestao_checklist'];
elseif ($linha!=null && $linha['plano_acao_gestao_agenda'] && $qnt==1 && !$plano_acao_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['plano_acao_gestao_agenda'];
elseif ($linha!=null && $linha['plano_acao_gestao_agrupamento'] && $qnt==1 && !$plano_acao_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['plano_acao_gestao_agrupamento'];
elseif ($linha!=null && $linha['plano_acao_gestao_patrocinador'] && $qnt==1 && !$plano_acao_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['plano_acao_gestao_patrocinador'];
elseif ($linha!=null && $linha['plano_acao_gestao_template'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['plano_acao_gestao_template'];
elseif ($linha!=null && $linha['plano_acao_gestao_painel'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['plano_acao_gestao_painel'];
elseif ($linha!=null && $linha['plano_acao_gestao_painel_odometro'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['plano_acao_gestao_painel_odometro'];
elseif ($linha!=null && $linha['plano_acao_gestao_painel_composicao'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['plano_acao_gestao_painel_composicao'];
elseif ($linha!=null && $linha['plano_acao_gestao_tr'] && $qnt==1 && !$plano_acao_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['plano_acao_gestao_tr'];
elseif ($linha!=null && $linha['plano_acao_gestao_me'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['plano_acao_gestao_me'];
elseif ($linha!=null && $linha['plano_acao_gestao_acao_item'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['plano_acao_gestao_acao_item'];
elseif ($linha!=null && $linha['plano_acao_gestao_beneficio'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['plano_acao_gestao_beneficio'];
elseif ($linha!=null && $linha['plano_acao_gestao_painel_slideshow'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['plano_acao_gestao_painel_slideshow'];
elseif ($linha!=null && $linha['plano_acao_gestao_projeto_viabilidade'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['plano_acao_gestao_projeto_viabilidade'];
elseif ($linha!=null && $linha['plano_acao_gestao_projeto_abertura'] && $qnt==1 && !$plano_acao_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['plano_acao_gestao_projeto_abertura'];
elseif ($linha!=null && $linha['plano_acao_gestao_plano_gestao'] && $qnt==1 && !$plano_acao_id) $endereco='m=praticas&u=gestao&a=menu&pg_id='.$linha['plano_acao_gestao_plano_gestao'];
elseif ($linha!=null && $linha['plano_acao_gestao_ssti'] && $qnt==1 && !$plano_acao_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['plano_acao_gestao_ssti'];
elseif ($linha!=null && $linha['plano_acao_gestao_laudo'] && $qnt==1 && !$plano_acao_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['plano_acao_gestao_laudo'];
elseif ($linha!=null && $linha['plano_acao_gestao_trelo'] && $qnt==1 && !$plano_acao_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['plano_acao_gestao_trelo'];
elseif ($linha!=null && $linha['plano_acao_gestao_trelo_cartao'] && $qnt==1 && !$plano_acao_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['plano_acao_gestao_trelo_cartao'];
elseif ($linha!=null && $linha['plano_acao_gestao_pdcl'] && $qnt==1 && !$plano_acao_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['plano_acao_gestao_pdcl'];
elseif ($linha!=null && $linha['plano_acao_gestao_pdcl_item'] && $qnt==1 && !$plano_acao_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['plano_acao_gestao_pdcl_item'];
elseif ($linha!=null && $linha['plano_acao_gestao_os'] && $qnt==1 && !$plano_acao_id) $endereco='m=os&a=os_ver&os_id='.$linha['plano_acao_gestao_os'];

else $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$obj->plano_acao_id;
$Aplic->redirecionar($endereco);
?>