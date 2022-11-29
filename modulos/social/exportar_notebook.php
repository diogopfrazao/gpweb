<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $config;
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'gera_notebook')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
include_once BASE_DIR.'/modulos/social/funcoes.php';

$exportar=getParam($_REQUEST, 'exportar', 0);
 
$botoesTitulo = new CBlocoTitulo('Gerar Arquivo de Prepara��o de Dispositivo Off-Line', 'importar.gif', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema&a=index&u=', 'sistema','','Administra��o do Sistema','Voltar � tela de Administra��o do Sistema.');
$botoesTitulo->mostrar(); 
 
 
 if ($exportar){
	
	$nome=exportar_social();
	
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><table><tr><td align=right>Arquivo criado:</td><td><b><a href="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome.'.zip">'.$nome.'.zip</a></b></td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	}


echo '<form name="env" id="env" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="exportar" value="0" />'; 
 
if(!$exportar){
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20><table align=center><tr><td>'.botao('gerar arquivo', 'Gerar Arquivo', 'Clique neste bot�o para gerar o arquivo o arquivo de prapara��o dos dispositivos que ir�o trabalhar off-line no cadastramento de benefici�rios, com os programas, as a��es, comit�s e comunidades.','','env.exportar.value=1; env.submit()').'</td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	} 
 
 
 
 
 
echo '</form>';



 
 
 

 
 
 
?>