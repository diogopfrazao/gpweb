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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	global $config;
	
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	
	$saida2='';
	$sql = new BDConsulta;
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, descricao');
	$sql->adOnde('organizacao='.(int)$config['militar']);
	$tipos=$sql->lista();
	$sql->limpar();
	
	$sql->adTabela('modelo_cia');
	$sql->adCampo('modelo_cia_tipo');
	$sql->adOnde('modelo_cia_cia='.(int)$cia_id);
	$selecionados=$sql->carregarColuna();
	$sql->limpar();
	$saida2.= '<table cellspacing=0 cellpadding=0 class="tbl1">';
	$saida2.= '<tr><th></th><th>Nome</th><th>Descri��o</th></tr>';
	foreach($tipos as $linha){
		$saida2.= '<tr><td><input type="checkbox" value="1" name="priorizacao_'.$linha['modelo_tipo_id'].'" id="priorizacao_'.$linha['modelo_tipo_id'].'" '.(in_array($linha['modelo_tipo_id'], $selecionados) ? 'checked="checked"' : '').' onchange="mudar('.$linha['modelo_tipo_id'].')" /></td><td align=left>'.$linha['modelo_tipo_nome'].'</td><td align=left>'.$linha['descricao'].'</td></tr>';
		}
	$saida2.= '</table>';	

	$objResposta->assign('combo_tabela',"innerHTML", utf8_encode($saida2));
	
	
	return $objResposta;
	}
	
$xajax->registerFunction("selecionar_om_ajax");	


function mudar($cia_id=1, $modelo_tipo_id, $inserir=false){
	$sql = new BDConsulta;
	$sql->setExcluir('modelo_cia');
	$sql->adOnde('modelo_cia_cia = '.(int)$cia_id);
	$sql->adOnde('modelo_cia_tipo = '.(int)$modelo_tipo_id);
	$sql->exec();
	$sql->limpar();

	if ($inserir){
		$sql->adTabela('modelo_cia');
		$sql->adInserir('modelo_cia_cia', (int)$cia_id);
		$sql->adInserir('modelo_cia_tipo', (int)$modelo_tipo_id);
		$sql->exec();
		$sql->limpar();
		}

	}
	
$xajax->registerFunction("mudar");	


$xajax->processRequest();

?>