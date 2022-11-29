<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));
include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_calculo, pratica_indicador_formula');
$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();
echo estiloTopoCaixa();

echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tt><td><h1>'.nome_indicador($pratica_indicador_id).'</h1></td></tr>';
if ($pratica_indicador['pratica_indicador_formula'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula');
	$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_formula_filho');
	$sql->esqUnir('cias','cias', 'cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_formula_filho, pratica_indicador_agrupar, pratica_indicador_formula_ordem, pratica_indicador_nome, cia_nome, pratica_indicador_formula_rocado');
	$sql->adOnde('pratica_indicador_formula_pai = '.(int)$pratica_indicador_id);
	$lista_formula = $sql->Lista();
	if ($lista_formula && count($lista_formula)) {
		echo '<tr><td align=center>&nbsp;</td></tr>';
		echo '<tr><td>F�rmula='.strtoupper($pratica_indicador['pratica_indicador_calculo']).'</td></tr>';
		echo '<tr><td align=center>&nbsp;</td></tr>';
		echo '<tr><td><table cellspacing=0 cellpadding=0 class="tbl1">';
		
		$objPai = new Indicador($pratica_indicador_id);
		$valorTotal=$objPai->Valor_atual($objPai->pratica_indicador_agrupar);
		
		
		
		$qnt_lista_formula=count($lista_formula);
		for ($i = 0, $i_cmp = $qnt_lista_formula; $i < $i_cmp; $i++) {
			$obj = new Indicador($lista_formula[$i]['pratica_indicador_formula_filho']);
			$valor=$obj->Valor_atual($objPai->pratica_indicador_agrupar);
			echo '<tr><td>'.$lista_formula[$i]['pratica_indicador_nome'].' - '.$lista_formula[$i]['cia_nome'].($lista_formula[$i]['pratica_indicador_formula_rocado'] ? ' - deslocado' : '').'</td><td align=center>I'.($lista_formula[$i]['pratica_indicador_formula_ordem']< 10 ? '0' : '').$lista_formula[$i]['pratica_indicador_formula_ordem'].'</td><td align=right>'.($valor !==null ? number_format($valor, $config['casas_decimais'], ',', '.') : 'sem valor').'</td></tr>';		
			}
			
		echo '<tr><td colspan=2 align=center><b>Total</b></td><td><b>'.($valorTotal !==null ? number_format($valorTotal, $config['casas_decimais'], ',', '.') : 'sem valor').'</b></td></tr>';
			
			
			
		echo '</table></td></tr>';
		} 
	}
else echo '<tr><td>N�o h� campos nesta f�rmula!</td></tr>';	
echo '</table>';	
echo estiloFundoCaixa();	
?>