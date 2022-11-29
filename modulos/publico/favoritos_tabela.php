<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $linhas;


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap">'.dica('Nome', 'Nome para identifica��o do favorito.').'Nome'.dicaF().'</th>';
echo '</tr>';

foreach ($linhas as $linha) {
	echo '<tr>';
	echo '<td style="white-space: nowrap" width="16">'.dica('Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o favorito.').'<a href="javascript:void(0);" onclick="adicionar('.$linha['favorito_id'].');">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
	echo '<td style="white-space: nowrap">'.dica($linha['favorito_nome'], 'Clique para visualizar os detalhes deste favorito.').'<a href="javascript:void(0);" onclick="visualizar('.$linha['favorito_id'].');">'.$linha['favorito_nome'].'</a>'.dicaF().'</td>';	
	echo '</tr>';
	}
if (!count($linhas)) echo '<tr><td colspan=20><p>Nenhum favorito encontrado.</p></td></tr>';
echo '</table>';
?>