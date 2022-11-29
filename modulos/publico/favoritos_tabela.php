<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $linhas;


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap">'.dica('Nome', 'Nome para identificação do favorito.').'Nome'.dicaF().'</th>';
echo '</tr>';

foreach ($linhas as $linha) {
	echo '<tr>';
	echo '<td style="white-space: nowrap" width="16">'.dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o favorito.').'<a href="javascript:void(0);" onclick="adicionar('.$linha['favorito_id'].');">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
	echo '<td style="white-space: nowrap">'.dica($linha['favorito_nome'], 'Clique para visualizar os detalhes deste favorito.').'<a href="javascript:void(0);" onclick="visualizar('.$linha['favorito_id'].');">'.$linha['favorito_nome'].'</a>'.dicaF().'</td>';	
	echo '</tr>';
	}
if (!count($linhas)) echo '<tr><td colspan=20><p>Nenhum favorito encontrado.</p></td></tr>';
echo '</table>';
?>