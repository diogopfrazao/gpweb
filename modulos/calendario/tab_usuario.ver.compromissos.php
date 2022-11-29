<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $projeto_id, $negar, $podeAcessar, $podeEditar, $config, $data_inicio, $data_fim, $este_dia, $agenda_filtro, $agenda_filtro_lista, $usuario_id;
require_once $Aplic->getClasseModulo('calendario');
require_once (BASE_DIR.'/modulos/email/email.class.php');

if(!$usuario_id) $usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);

$data_inicio =  new CData();
$data_fim =  new CData('9999-12-31 23:59:59');
if (isset($_REQUEST['usuario_id'])) $usuario_id=getParam($_REQUEST, 'usuario_id', '');

$compromissos = CAgenda::getCompromissoParaPeriodo($data_inicio, $data_fim, '', $usuario_id);

$inicio_hora = config('cal_dia_inicio');
$fim_hora = config('cal_dia_fim');


$tipos = getSisValor('TipoEvento');
$html = '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
$html .= '<tr><th>'.dica('Data - Hora', 'A data e hora do in�cio e t�rmino do evento.').'Data'.dicaF().'</th><th>'.dica('Compromisso', 'O nome do compromisso.').'Compromisso'.dicaF().'</th></tr>';
$qnt=0;
foreach ($compromissos as $linha) {
	$qnt++;
	$html .= '<tr>';
	$inicio = new CData($linha['agenda_inicio']);
	$fim = new CData($linha['agenda_fim']);
	$html .= '<td style="white-space: nowrap" width="230">'.$inicio->format('%d/%m/%Y %H:%M').'&nbsp;-&nbsp;'.$fim->format('%d/%m/%Y %H:%M').'</td>';
	$html .= '<td>'.link_agenda($linha['agenda_id']).'</td></tr>';
	}
if (!$qnt) $html .= '<tr><td colspan=3 align="left"><p>Nenhum compromisso encontrado.</p></td></tr>';
$html .= '</table>';
echo $html;
?>