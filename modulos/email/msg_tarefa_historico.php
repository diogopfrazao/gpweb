<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$sql = new BDConsulta; 
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);

$sql->adTabela('msg_tarefa_historico');
$sql->adCampo('data, progresso');
$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
$lista = $sql->Lista();
$sql->limpar();

echo '<table rules="ALL" border="1" align="center" cellspacing=0 cellpadding=0 width=200>'; 
echo '<tr><th>Data</th><th>Percentagem</th></tr>';
foreach($lista as $linha) echo '<tr><td>'.retorna_data($linha['data']).'</td><td align="center">'.(int)$linha['progresso'].'</td></tr>';
if (!count($lista)) echo '<tr><td colspan=2>Nenhuma alteração na percentagem foi realizada ainda.</td></tr>';
echo '</table>';
echo estiloFundoCaixa();

?>