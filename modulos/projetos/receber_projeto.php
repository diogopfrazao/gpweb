<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ReceberProjetoTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ReceberProjetoTab') !== null ? $Aplic->getEstado('ReceberProjetoTab') : 1);

$onde=getParam($_REQUEST, 'onde', '');


$botoesTitulo = new CBlocoTitulo('Envio e Recebimento de '. ucfirst($config['projetos']), 'receber_projeto.png');
$botoesTitulo->mostrar();
$caixaTab = new CTabBox('m=projetos&a=receber_projeto', BASE_DIR.'/modulos/projetos/', $tab);
$caixaTab->adicionar('lista_projeto_receber', 'Recebidos Pendente',null,null,'Recebidos Pendente','Clique nesta aba para visualizar os '.$config['projetos'].' recebidos de outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que anda n�o foram confirmados como aceitos ou recusados.');
$caixaTab->adicionar('lista_projeto_receber', 'Recebidos Aceitos',null,null,'Recebidos','Clique nesta aba para visualizar os '.$config['projetos'].' recebidos de outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que foram confirmados como aceitos ou recusados.');
$caixaTab->adicionar('lista_projeto_receber', 'Enviados Pendentes',null,null,'Enviados Pendentes', 'Clique nesta aba para visualizar os '.$config['projetos'].' enviados para outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que ainda n�o foram confirmados como aceitos ou recusados.');
$caixaTab->adicionar('lista_projeto_receber', 'Enviados Aceitos',null,null,'Enviados','Clique nesta aba para visualizar os '.$config['projetos'].' enviados para outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que foram confirmados como aceitos ou recusados.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>