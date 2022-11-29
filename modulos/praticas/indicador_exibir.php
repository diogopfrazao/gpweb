<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (!$Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $Aplic->redirecionar('m=publico&a=acesso_negado');


$ano=getParam($_REQUEST, 'ano', 0);
$faixas=getParam($_REQUEST, 'faixas', 0);
$mostrar_valor=getParam($_REQUEST, 'mostrar_valor', null);
$mostrar_pontuacao=getParam($_REQUEST, 'mostrar_pontuacao', 0);
$data = new CData(getParam($_REQUEST, 'data_final', null));
$data2 = new CData(getParam($_REQUEST, 'data_final2', null));
$nr_pontos=getParam($_REQUEST, 'nr_pontos', null);
$mostrar_titulo=getParam($_REQUEST, 'mostrar_titulo',null);
$max_min=getParam($_REQUEST, 'max_min', null);
$agrupar=getParam($_REQUEST, 'agrupar', null);
$tipografico=getParam($_REQUEST, 'tipografico', null); 
$segundo_indicador=getParam($_REQUEST, 'segundo_indicador', 0);
$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', 0);

$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&faixas='.$faixas.'&mostrar_valor='.$mostrar_valor.'&mostrar_pontuacao='.$mostrar_pontuacao.'&data_final='.$data->format("%Y-%m-%d").'&data_final2='.$data2->format("%Y-%m-%d").'&nr_pontos='.$nr_pontos.'&mostrar_titulo='.$mostrar_titulo.'&max_min='.$max_min.'&agrupar='.$agrupar.'&tipografico='.$tipografico.'&segundo_indicador='.$segundo_indicador.'&pratica_indicador_id='.$pratica_indicador_id."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<table cellspacing=0 cellpadding=0 align='center' class='tbl3'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";

?>