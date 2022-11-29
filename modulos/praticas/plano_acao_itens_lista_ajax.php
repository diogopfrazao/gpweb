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

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function mudar_percentagem_item_plano($plano_acao_item_id=null, $plano_acao_item_percentagem=null){
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_percentagem', $plano_acao_item_percentagem);
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	}
$xajax->registerFunction("mudar_percentagem_item_plano");

$corItemPlano = getSisValor('StatusAcaoPlanoCor');

function mudar_status_item_plano($plano_acao_item_id=null, $plano_acao_item_status=null){
	global $corItemPlano;
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_status', $plano_acao_item_status);
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	if (isset($corItemPlano[$plano_acao_item_status])){
		$objResposta = new xajaxResponse();
		$objResposta->assign('status_item_plano_'.$plano_acao_item_id,'style.backgroundColor', '#'.$corItemPlano[$plano_acao_item_status]);
		return $objResposta;
		}
	}
$xajax->registerFunction("mudar_status_item_plano");

function ver_obs_item_plano($plano_acao_item_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adCampo('plano_acao_item_observacao');
	$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
	$obs=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("obs_item_plano_".$plano_acao_item_id,"innerHTML", utf8_encode($obs));	
	return $objResposta;
	}	
$xajax->registerFunction("ver_obs_item_plano");	


$xajax->processRequest();

?>