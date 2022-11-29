<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
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

function painel_filtro($visao){
	global $Aplic;
	if ($visao=='none') $painel_filtro=0; 
	else  $painel_filtro=1;
	$Aplic->setEstado('painel_filtro',$painel_filtro);
	}
$xajax->registerFunction("painel_filtro");

	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, false);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	$qnt=0;
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, true);
	$corpo='<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
	foreach($vetor as $nd_id => $valor) if ($valor!='Retornar a lista de elementos de despesa' && $valor) {
		$corpo.='<tr><td width=16><a href="javascript: void(0);" onclick="retornar(\''.$nd_id.'\', \''.$valor.'\');">'.$valor.'</td></tr>';
		$qnt++;
		}
	if (!$qnt) $corpo.='<tr><td>Nenhum dado</td></tr>';
	$corpo.='</table>';
	$objResposta->assign('corpo',"innerHTML", utf8_encode($corpo));
	
	
	return $objResposta;
	}	

$xajax->registerFunction("mudar_nd_ajax");
	
	
	
$xajax->processRequest();
?>