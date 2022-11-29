<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $m, $a, $u;
 
if (isset($_REQUEST['tab'])) $Aplic->setEstado('tab', getParam($_REQUEST, 'tab', 0), $m, $a, $u);
$tab = $Aplic->getEstado('tab', 0, $m, $a, $u);

$onde=getParam($_REQUEST, 'onde', '');
$botoesTitulo = new CBlocoTitulo(ucfirst($config['mensagens']).' do Tipo Atividade', 'task.png');


$botoesTitulo->adicionaCelula('<form name="frmPesquisa" method="post"><input type="hidden" name="m" value="email" /><input type="hidden" name="a" value="'.$a.'" /><input type="hidden" name="tab" value="'.$tab.'" /><table><tr><td align="right" style="white-space: nowrap">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" name="onde" class="texto" size="20" onChange="document.frmPesquisa.submit();" value="'.$onde.'" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&onde=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></form>');
$botoesTitulo->mostrar();
$caixaTab = new CTabBox('m=email&a=lista_msg_tarefa', BASE_DIR.'/modulos/email/', $tab);
$caixaTab->adicionar('msg_tarefa', 'Recebid'.$config['genero_mensagem'].'s em execu��o',null,null,'Recebid'.$config['genero_mensagem'].'s em Execu��o','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade recebid'.$config['genero_mensagem'].'s que ainda n�o foram marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Enviad'.$config['genero_mensagem'].'s em execu��o',null,null,'Enviad'.$config['genero_mensagem'].'s em Execu��o','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade enviad'.$config['genero_mensagem'].'s que ainda n�o foram marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Recebid'.$config['genero_mensagem'].'s encerrad'.$config['genero_mensagem'].'s',null,null,'Recebid'.$config['genero_mensagem'].'s Encerrad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade recebid'.$config['genero_mensagem'].'s que est�o marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Enviad'.$config['genero_mensagem'].'s encerrad'.$config['genero_mensagem'].'s',null,null,'Enviad'.$config['genero_mensagem'].'s Encerrad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade enviad'.$config['genero_mensagem'].'s que foram marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Recebid'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s',null,null,'Recebid'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade que foram ignorad'.$config['genero_mensagem'].'s.');
$caixaTab->adicionar('msg_tarefa', 'Enviad'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s',null,null,'Enviad'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade enviad'.$config['genero_mensagem'].'s que foram ignorad'.$config['genero_mensagem'].'s pelos destinat�rios.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>