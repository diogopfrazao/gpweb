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

require_once (BASE_DIR.'/modulos/praticas/gestao/gestao.class.php');

transforma_vazio_em_nulo($_REQUEST);
$pg_id = intval(getParam($_REQUEST, 'pg_id', 0));
$del = intval(getParam($_REQUEST, 'del', 0));

$nao_eh_novo=getParam($_REQUEST, 'pg_id', null);

if ($del && !$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$nao_eh_novo && !$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$obj = new CGestao();
if ($pg_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

$obj->pg_ultima_alteracao = date('Y-m-d H:i:s');

$sql = new BDConsulta;

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&u=gestao&a=gestao_lista');
	}
$Aplic->setMsg('Planejamento Estrat�gico');
if ($del) {
	$obj->load($pg_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		}
	
	$Aplic->redirecionar('m=praticas&u=gestao&a=gestao_lista');	
		
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	
	
	
	
	
$importar_id=getParam($_REQUEST, 'importar_id', null);

if ($importar_id){

	

	$sql->adTabela('plano_gestao');
	$sql->adCampo('plano_gestao.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$plano_gestao_antigo=$sql->Linha();
	$sql->limpar();
	
	if ($importar_id){
		$sql->adTabela('plano_gestao');
		foreach($plano_gestao_antigo as $chave => $valor) if (
		$chave!='pg_id' && 
		$chave!='pg_ano' && 
		$chave!='pg_ultima_alteracao' && 
		$chave!='pg_usuario_ultima_alteracao' &&
		$chave!='pg_cia' && 
		$chave!='pg_dept' && 
		$chave!='pg_usuario' && 
		$chave!='pg_acesso' && 
		$chave!='pg_ativo' && 
		$chave!='pg_nome' && 
		$chave!='pg_inicio' && 
		$chave!='pg_fim' && 
		$chave!='pg_descricao' 
		) $sql->adAtualizar($chave, $valor);
		$sql->adOnde('pg_id='.(int)$obj->pg_id);
		$sql->exec();
		$sql->limpar();
		}
		
	
	
	$sql->adTabela('plano_gestao2');
	$sql->adCampo('plano_gestao2.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$plano_gestao_antigo2=$sql->Linha();
	$sql->limpar();
	if ($plano_gestao_antigo2['pg_id']){
		$sql->adTabela('plano_gestao2');
		foreach($plano_gestao_antigo2 as $chave => $valor) if ($chave!='pg_id') $sql->adAtualizar($chave, $valor);
		$sql->adOnde('pg_id='.(int)$obj->pg_id);
		$sql->exec();
		$sql->limpar();
		}

	
	$sql->adTabela('plano_gestao_tema');
	$sql->adCampo('plano_gestao_tema.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_tema');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('tema_id', $linha['tema_id']);
			$sql->adInserir('tema_ordem', $linha['tema_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		}	
	
	$sql->adTabela('plano_gestao_objetivo');
	$sql->adCampo('plano_gestao_objetivo.*');
	$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['plano_gestao_objetivo_plano_gestao']){
			$sql->adTabela('plano_gestao_objetivo');
			$sql->adInserir('plano_gestao_objetivo_plano_gestao', $obj->pg_id);
			$sql->adInserir('plano_gestao_objetivo_objetivo', $linha['plano_gestao_objetivo_objetivo']);
			$sql->adInserir('plano_gestao_objetivo_ordem', $linha['plano_gestao_objetivo_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		}	
		
	$sql->adTabela('plano_gestao_estrategias');
	$sql->adCampo('plano_gestao_estrategias.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_estrategias');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_estrategia_id', $linha['pg_estrategia_id']);
			$sql->adInserir('pg_estrategia_ordem', $linha['pg_estrategia_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		}	
	
	$sql->adTabela('plano_gestao_fator');
	$sql->adCampo('plano_gestao_fator.*');
	$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['plano_gestao_fator_plano_gestao']){
			$sql->adTabela('plano_gestao_fator');
			$sql->adInserir('plano_gestao_fator_plano_gestao', $obj->pg_id);
			$sql->adInserir('plano_gestao_fator_fator', $linha['plano_gestao_fator_fator']);
			$sql->adInserir('plano_gestao_fator_ordem', $linha['plano_gestao_fator_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		}	
		
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->adCampo('plano_gestao_perspectivas.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_perspectivas');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_perspectiva_id', $linha['pg_perspectiva_id']);
			$sql->adInserir('pg_perspectiva_ordem', $linha['pg_perspectiva_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		}	

	$sql->adTabela('plano_gestao_metas');
	$sql->adCampo('plano_gestao_metas.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_metas');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_meta_id', $linha['pg_meta_id']);
			$sql->adInserir('pg_meta_ordem', $linha['pg_meta_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		}	
	
	$sql->adTabela('plano_gestao_arquivo');
	$sql->adCampo('plano_gestao_arquivo.*');
	$sql->adOnde('plano_gestao_arquivo_plano_gestao='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['plano_gestao_arquivo_plano_gestao']){
			$sql->adTabela('plano_gestao_arquivo');
			$sql->adInserir('plano_gestao_arquivo_plano_gestao', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='plano_gestao_arquivo_id' && $chave!='plano_gestao_arquivo_plano_gestao') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	$sql->adTabela('plano_gestao_diretrizes');
	$sql->adCampo('plano_gestao_diretrizes.*');
	$sql->adOnde('pg_diretriz_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_diretriz_pg_id']){
			$sql->adTabela('plano_gestao_diretrizes');
			$sql->adInserir('pg_diretriz_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_id' && $chave!='pg_diretriz_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_diretrizes_superiores');
	$sql->adCampo('plano_gestao_diretrizes_superiores.*');
	$sql->adOnde('pg_diretriz_superior_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_diretriz_superior_pg_id']){
			$sql->adTabela('plano_gestao_diretrizes_superiores');
			$sql->adInserir('pg_diretriz_superior_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_superior_id' && $chave!='pg_diretriz_superior_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_fornecedores');
	$sql->adCampo('plano_gestao_fornecedores.*');
	$sql->adOnde('pg_fornecedor_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_fornecedor_pg_id']){
			$sql->adTabela('plano_gestao_fornecedores');
			$sql->adInserir('pg_fornecedor_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_fornecedor_id' && $chave!='pg_fornecedor_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_pessoal');
	$sql->adCampo('plano_gestao_pessoal.*');
	$sql->adOnde('pg_pessoal_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_pessoal_pg_id']){
			$sql->adTabela('plano_gestao_pessoal');
			$sql->adInserir('pg_pessoal_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_pessoal_id' && $chave!='pg_pessoal_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	
	
	$sql->adTabela('pg_swot');
	$sql->adCampo('pg_swot.*');
	$sql->adOnde('pg_swot_pg='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_swot_pg']){
			$sql->adTabela('pg_swot');
			$sql->adInserir('pg_swot_pg', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_swot_id' && $chave!='pg_swot_pg') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}	
	

	$sql->adTabela('plano_gestao_premiacoes');
	$sql->adCampo('plano_gestao_premiacoes.*');
	$sql->adOnde('pg_premiacao_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_premiacao_pg_id']){
			$sql->adTabela('plano_gestao_premiacoes');
			$sql->adInserir('pg_premiacao_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_premiacao_id' && $chave!='pg_premiacao_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	$sql->adTabela('plano_gestao_principios');
	$sql->adCampo('plano_gestao_principios.*');
	$sql->adOnde('pg_principio_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_principio_pg_id']){
			$sql->adTabela('plano_gestao_principios');
			$sql->adInserir('pg_principio_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_principio_id' && $chave!='pg_principio_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->limpar();
			}
		}		
	}	
	
	
	
if ($Aplic->profissional){	
	$pontuacao=$obj->calculo_percentagem();	
	}	
	
	

$sql->adTabela('plano_gestao_gestao');
$sql->adCampo('plano_gestao_gestao.*');
$sql->adOnde('plano_gestao_gestao_plano_gestao='.(int)$obj->pg_id);
$sql->adOrdem('plano_gestao_gestao_ordem ASC');
$linha=$sql->linha();
$sql->limpar();


$sql->adTabela('plano_gestao_gestao');
$sql->adCampo('count(plano_gestao_gestao_id)');
$sql->adOnde('plano_gestao_gestao_plano_gestao='.(int)$obj->pg_id);
$qnt=$sql->Resultado();
$sql->limpar();

if ($linha!=null && $linha['plano_gestao_gestao_tarefa'] && $qnt==1 && !$pg_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['plano_gestao_gestao_tarefa'];
elseif ($linha!=null && $linha['plano_gestao_gestao_projeto'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['plano_gestao_gestao_projeto'];
elseif ($linha!=null && $linha['plano_gestao_gestao_perspectiva'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['plano_gestao_gestao_perspectiva'];
elseif ($linha!=null && $linha['plano_gestao_gestao_tema'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['plano_gestao_gestao_tema'];
elseif ($linha!=null && $linha['plano_gestao_gestao_objetivo'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['plano_gestao_gestao_objetivo'];
elseif ($linha!=null && $linha['plano_gestao_gestao_fator'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['plano_gestao_gestao_fator'];
elseif ($linha!=null && $linha['plano_gestao_gestao_estrategia'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['plano_gestao_gestao_estrategia'];
elseif ($linha!=null && $linha['plano_gestao_gestao_meta'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['plano_gestao_gestao_meta'];
elseif ($linha!=null && $linha['plano_gestao_gestao_pratica'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['plano_gestao_gestao_pratica'];
elseif ($linha!=null && $linha['plano_gestao_gestao_indicador'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['plano_gestao_gestao_indicador'];
elseif ($linha!=null && $linha['plano_gestao_gestao_acao'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['plano_gestao_gestao_acao'];
elseif ($linha!=null && $linha['plano_gestao_gestao_canvas'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['plano_gestao_gestao_canvas'];
elseif ($linha!=null && $linha['plano_gestao_gestao_risco'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['plano_gestao_gestao_risco'];
elseif ($linha!=null && $linha['plano_gestao_gestao_risco_resposta'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['plano_gestao_gestao_risco_resposta'];
elseif ($linha!=null && $linha['plano_gestao_gestao_calendario'] && $qnt==1 && !$pg_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['plano_gestao_gestao_calendario'];
elseif ($linha!=null && $linha['plano_gestao_gestao_monitoramento'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['plano_gestao_gestao_monitoramento'];
elseif ($linha!=null && $linha['plano_gestao_gestao_ata'] && $qnt==1 && !$pg_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['plano_gestao_gestao_ata'];
elseif ($linha!=null && $linha['plano_gestao_gestao_mswot'] && $qnt==1 && !$pg_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['plano_gestao_gestao_mswot'];
elseif ($linha!=null && $linha['plano_gestao_gestao_swot'] && $qnt==1 && !$pg_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['plano_gestao_gestao_swot'];
elseif ($linha!=null && $linha['plano_gestao_gestao_operativo'] && $qnt==1 && !$pg_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['plano_gestao_gestao_operativo'];
elseif ($linha!=null && $linha['plano_gestao_gestao_instrumento'] && $qnt==1 && !$pg_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['plano_gestao_gestao_instrumento'];
elseif ($linha!=null && $linha['plano_gestao_gestao_recurso'] && $qnt==1 && !$pg_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['plano_gestao_gestao_recurso'];
elseif ($linha!=null && $linha['plano_gestao_gestao_problema'] && $qnt==1 && !$pg_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['plano_gestao_gestao_problema'];
elseif ($linha!=null && $linha['plano_gestao_gestao_demanda'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['plano_gestao_gestao_demanda'];
elseif ($linha!=null && $linha['plano_gestao_gestao_programa'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['plano_gestao_gestao_programa'];
elseif ($linha!=null && $linha['plano_gestao_gestao_licao'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['plano_gestao_gestao_licao'];
elseif ($linha!=null && $linha['plano_gestao_gestao_evento'] && $qnt==1 && !$pg_id) $endereco='m=calendario&a=ver&evento_id='.$linha['plano_gestao_gestao_evento'];
elseif ($linha!=null && $linha['plano_gestao_gestao_link'] && $qnt==1 && !$pg_id) $endereco='m=links&a=ver&link_id='.$linha['plano_gestao_gestao_link'];
elseif ($linha!=null && $linha['plano_gestao_gestao_avaliacao'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['plano_gestao_gestao_avaliacao'];
elseif ($linha!=null && $linha['plano_gestao_gestao_tgn'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['plano_gestao_gestao_tgn'];
elseif ($linha!=null && $linha['plano_gestao_gestao_brainstorm'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['plano_gestao_gestao_brainstorm'];
elseif ($linha!=null && $linha['plano_gestao_gestao_gut'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['plano_gestao_gestao_gut'];
elseif ($linha!=null && $linha['plano_gestao_gestao_causa_efeito'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['plano_gestao_gestao_causa_efeito'];
elseif ($linha!=null && $linha['plano_gestao_gestao_arquivo'] && $qnt==1 && !$pg_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['plano_gestao_gestao_arquivo'];
elseif ($linha!=null && $linha['plano_gestao_gestao_forum'] && $qnt==1 && !$pg_id) $endereco='m=foruns&a=ver&forum_id='.$linha['plano_gestao_gestao_forum'];
elseif ($linha!=null && $linha['plano_gestao_gestao_checklist'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['plano_gestao_gestao_checklist'];
elseif ($linha!=null && $linha['plano_gestao_gestao_agenda'] && $qnt==1 && !$pg_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['plano_gestao_gestao_agenda'];
elseif ($linha!=null && $linha['plano_gestao_gestao_agrupamento'] && $qnt==1 && !$pg_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['plano_gestao_gestao_agrupamento'];
elseif ($linha!=null && $linha['plano_gestao_gestao_patrocinador'] && $qnt==1 && !$pg_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['plano_gestao_gestao_patrocinador'];
elseif ($linha!=null && $linha['plano_gestao_gestao_template'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['plano_gestao_gestao_template'];
elseif ($linha!=null && $linha['plano_gestao_gestao_painel'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['plano_gestao_gestao_painel'];
elseif ($linha!=null && $linha['plano_gestao_gestao_painel_odometro'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['plano_gestao_gestao_painel_odometro'];
elseif ($linha!=null && $linha['plano_gestao_gestao_painel_composicao'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['plano_gestao_gestao_painel_composicao'];
elseif ($linha!=null && $linha['plano_gestao_gestao_tr'] && $qnt==1 && !$pg_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['plano_gestao_gestao_tr'];
elseif ($linha!=null && $linha['plano_gestao_gestao_me'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['plano_gestao_gestao_me'];
elseif ($linha!=null && $linha['plano_gestao_gestao_acao_item'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['plano_gestao_gestao_acao_item'];
elseif ($linha!=null && $linha['plano_gestao_gestao_beneficio'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['plano_gestao_gestao_beneficio'];
elseif ($linha!=null && $linha['plano_gestao_gestao_painel_slideshow'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['plano_gestao_gestao_painel_slideshow'];
elseif ($linha!=null && $linha['plano_gestao_gestao_projeto_viabilidade'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['plano_gestao_gestao_projeto_viabilidade'];
elseif ($linha!=null && $linha['plano_gestao_gestao_projeto_abertura'] && $qnt==1 && !$pg_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['plano_gestao_gestao_projeto_abertura'];

elseif ($linha!=null && $linha['plano_gestao_gestao_semelhante'] && $qnt==1 && !$pg_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['plano_gestao_gestao_semelhante'];

elseif ($linha!=null && $linha['plano_gestao_gestao_ssti'] && $qnt==1 && !$pg_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['plano_gestao_gestao_ssti'];
elseif ($linha!=null && $linha['plano_gestao_gestao_laudo'] && $qnt==1 && !$pg_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['plano_gestao_gestao_laudo'];
elseif ($linha!=null && $linha['plano_gestao_gestao_trelo'] && $qnt==1 && !$pg_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['plano_gestao_gestao_trelo'];
elseif ($linha!=null && $linha['plano_gestao_gestao_trelo_cartao'] && $qnt==1 && !$pg_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['plano_gestao_gestao_trelo_cartao'];
elseif ($linha!=null && $linha['plano_gestao_gestao_pdcl'] && $qnt==1 && !$pg_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['plano_gestao_gestao_pdcl'];
elseif ($linha!=null && $linha['plano_gestao_gestao_pdcl_item'] && $qnt==1 && !$pg_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['plano_gestao_gestao_pdcl_item'];
elseif ($linha!=null && $linha['plano_gestao_gestao_os'] && $qnt==1 && !$pg_id) $endereco='m=os&a=os_ver&os_id='.$linha['plano_gestao_gestao_os'];
else $endereco='m=praticas&u=gestao&a=menu&pg_id='.(int)$obj->pg_id;

$Aplic->redirecionar($endereco);		
?>