<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
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
if (!count($lista)) echo '<tr><td colspan=2>Nenhuma altera��o na percentagem foi realizada ainda.</td></tr>';
echo '</table>';
echo estiloFundoCaixa();

?>