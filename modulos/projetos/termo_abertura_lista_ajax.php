<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function painel_filtro($visao){
	global $Aplic;
	if ($visao=='none') $painel_filtro=0; 
	else  $painel_filtro=1;
	$Aplic->setEstado('painel_filtro',$painel_filtro);
	}
$xajax->registerFunction("painel_filtro");

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
	
$xajax->registerFunction("selecionar_om_ajax");

function lista_nome($usuarios_id='', $posicao=''){
	$saida=nome_usuario($usuarios_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"value", ($saida!='&nbsp;' ? previnirXSS(utf8_encode($saida)) : ''));
	return $objResposta;
	}
$xajax->registerFunction("lista_nome");

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	global $Aplic;
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');

	$lista=$sql->Lista();
	$sql->limpar();
	$vetor=array(0 => '&nbsp;');	
	foreach($lista as $linha) $vetor[utf8_encode($linha['sisvalor_valor_id'])]=utf8_encode($linha['sisvalor_valor']);	
	
	$cleanScript = str_replace('\\"', '"', $script);
	$saida=selecionaVetor($vetor, $campo, $cleanScript);

	$objResposta = new xajaxResponse(); 
	$objResposta->assign($posicao,"innerHTML", $saida); 
	
	if($Aplic->profissional && $campo=='projeto_abertura_segmento') $objResposta->call('criarComboSegmento');
	if($Aplic->profissional && $campo=='projeto_abertura_intervencao') $objResposta->call('criarComboIntervencao');
	if($Aplic->profissional && $campo=='projeto_abertura_tipo_intervencao') $objResposta->call('criarComboTipoIntervencao');
	return $objResposta; 
	}	
$xajax->registerFunction("mudar_ajax");


$xajax->processRequest();

?>