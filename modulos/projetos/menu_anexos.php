<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));
$sql = new BDConsulta;

if($projeto_id && getParam($_REQUEST, 'gerar', 0)){
	$obj = new CProjeto();
	$obj->load($projeto_id);

	$sql->adTabela('demandas');
	$sql->adInserir('demanda_cia', $obj->projeto_cia);
	$sql->adInserir('demanda_usuario', $Aplic->usuario_id);
	$sql->adInserir('demanda_mensurador', $Aplic->usuario_id);
	$sql->adInserir('demanda_projeto', $projeto_id);
	$sql->adInserir('demanda_nome', $obj->projeto_nome);
	

	$sql->adInserir('demanda_justificativa', $obj->projeto_justificativa);
	$sql->adInserir('demanda_observacao', $obj->projeto_observacao);
	$sql->adInserir('demanda_descricao', $obj->projeto_descricao);
	$sql->adInserir('demanda_objetivos', $obj->projeto_objetivo);
	$sql->adInserir('demanda_como', $obj->projeto_como);
	$sql->adInserir('demanda_localizacao', $obj->projeto_localizacao);
	$sql->adInserir('demanda_beneficiario', $obj->projeto_beneficiario);
	$sql->adInserir('demanda_objetivo', $obj->projeto_objetivo);
	$sql->adInserir('demanda_objetivo_especifico', $obj->projeto_objetivo_especifico);
	$sql->adInserir('demanda_escopo', $obj->projeto_escopo);
	$sql->adInserir('demanda_nao_escopo', $obj->projeto_nao_escopo);
	$sql->adInserir('demanda_premissas', $obj->projeto_premissas);
	$sql->adInserir('demanda_restricoes', $obj->projeto_restricoes);
	$sql->adInserir('demanda_orcamento', $obj->projeto_orcamento);
	$sql->adInserir('demanda_beneficio', $obj->projeto_beneficio);
	$sql->adInserir('demanda_produto', $obj->projeto_produto);
	$sql->adInserir('demanda_requisito', $obj->projeto_requisito);
	
	
	$sql->adInserir('demanda_acesso', $obj->projeto_acesso);
	$sql->adInserir('demanda_caracteristica_projeto', 1);
	$sql->adInserir('demanda_data', date('Y-m-d H:i:s'));
	$sql->adInserir('demanda_mensuracao_data', date('Y-m-d H:i:s'));
	$sql->exec();
	$demanda_id=$bd->Insert_ID('demandas','demanda_id');
	$sql->limpar();
	
	$sql->adTabela('projeto_viabilidade');
	$sql->adInserir('projeto_viabilidade_cia', $obj->projeto_cia);
	$sql->adInserir('projeto_viabilidade_projeto', $projeto_id);
	$sql->adInserir('projeto_viabilidade_demanda', $demanda_id);
	$sql->adInserir('projeto_viabilidade_nome', $obj->projeto_nome);
	$sql->adInserir('projeto_viabilidade_codigo', $obj->getCodigo());
	$sql->adInserir('projeto_viabilidade_responsavel', $Aplic->usuario_id);
	$sql->adInserir('projeto_viabilidade_acesso', $obj->projeto_acesso);
	$sql->adInserir('projeto_viabilidade_cor', $obj->projeto_cor);
	$sql->adInserir('projeto_viabilidade_data', date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_viabilidade_viavel', 1);
	$sql->exec();
	$projeto_viabilidade_id=$bd->Insert_ID('projeto_viabilidade','projeto_viabilidade_id');
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura');
	$sql->adInserir('projeto_abertura_cia', $obj->projeto_cia);
	$sql->adInserir('projeto_abertura_projeto', $projeto_id);
	$sql->adInserir('projeto_abertura_demanda', $demanda_id);
	$sql->adInserir('projeto_abertura_nome', $obj->projeto_nome);
	$sql->adInserir('projeto_abertura_justificativa', $obj->projeto_justificativa);
	$sql->adInserir('projeto_abertura_objetivo', $obj->projeto_objetivo);
	$sql->adInserir('projeto_abertura_escopo', $obj->projeto_escopo);
	$sql->adInserir('projeto_abertura_nao_escopo', $obj->projeto_nao_escopo);
	$sql->adInserir('projeto_abertura_premissas', $obj->projeto_premissas);
	$sql->adInserir('projeto_abertura_restricoes', $obj->projeto_restricoes);
	$sql->adInserir('projeto_abertura_custo', $obj->projeto_orcamento);
	$sql->adInserir('projeto_abertura_descricao', $obj->projeto_descricao);
	$sql->adInserir('projeto_abertura_beneficio', $obj->projeto_beneficio);
	$sql->adInserir('projeto_abertura_observacao', $obj->projeto_observacao);
	$sql->adInserir('projeto_abertura_como', $obj->projeto_como);
	$sql->adInserir('projeto_abertura_localizacao', $obj->projeto_localizacao);
	$sql->adInserir('projeto_abertura_beneficiario', $obj->projeto_beneficiario);
	$sql->adInserir('projeto_abertura_objetivo_especifico', $obj->projeto_objetivo_especifico);
	$sql->adInserir('projeto_abertura_objetivos', $obj->projeto_objetivo);	
	$sql->adInserir('projeto_abertura_orcamento', $obj->projeto_orcamento);
	$sql->adInserir('projeto_abertura_responsavel', $Aplic->usuario_id);
	$sql->adInserir('projeto_abertura_gerente_projeto', $Aplic->usuario_id);
	$sql->adInserir('projeto_abertura_acesso', $obj->projeto_acesso);
	$sql->adInserir('projeto_abertura_cor', $obj->projeto_cor);
	$sql->adInserir('projeto_abertura_codigo', $obj->getCodigo());
	$sql->adInserir('projeto_abertura_aprovado', 1);
	$sql->adInserir('projeto_abertura_data', date('Y-m-d H:i:s'));
	$sql->exec();
	$projeto_abertura_id=$bd->Insert_ID('projeto_abertura','projeto_abertura_id');
	$sql->limpar();
	
	$sql->adTabela('demandas');
	$sql->adAtualizar('demanda_viabilidade', $projeto_viabilidade_id);
	$sql->adAtualizar('demanda_termo_abertura', $projeto_abertura_id);
	$sql->adOnde('demanda_id='.$demanda_id);
	$sql->exec();
	$sql->limpar();
	
	ver2('Artefatos criados');
	}
	
$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$projeto_id);	
?>