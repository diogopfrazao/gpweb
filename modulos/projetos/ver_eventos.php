<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $projeto_id, $negar, $podeAcessar, $podeEditar, $config, $data_inicio, $data_fim, $este_dia, $evento_filtro, $evento_filtro_lista;
require_once $Aplic->getClasseModulo('calendario');

$eventos = CEvento::getEventoParaPeriodo(null, null, 'todos', '','','', $projeto_id);

$tipos = getSisValor('TipoEvento');
$html = '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
$html .= '<tr><th>'.dica('Data - Hora', 'A data e hora do in?cio e t?rmino do evento.').'Data'.dicaF().'</th><th>'.dica('Tipo', 'O tipo de evento.').'Tipo'.dicaF().'</th><th>'.dica('Evento', 'O nome do evento.').'Evento'.dicaF().'</th></tr>';
$qnt=0;
foreach ($eventos as $linha) {
	$qnt++;
	$html .= '<tr>';
	$html .= '<td width="25%" style="white-space: nowrap">'.retorna_data($linha['evento_inicio']).'&nbsp;-&nbsp;'.retorna_data($linha['evento_fim']).'</td>';
	$html .= '<td width="10%" style="white-space: nowrap">'.imagem('icones/evento'.$linha['evento_tipo'].'.png', 'Tipo de Evento', 'Cada evento tem um gr?fico diferente para facilitar a identifica??o visual.').'&nbsp;<b>'.$tipos[$linha['evento_tipo']].'</b></td>';
	$html .= '<td>'.link_evento($linha['evento_id']).'</td></tr>';
	}
if (!$qnt) $html .= '<tr><td colspan="3"><p>Nenhum evento encontrado.</p></td></tr>';	
$html .= '</table>';
echo $html;
?>