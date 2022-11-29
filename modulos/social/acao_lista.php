<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $dialogo;

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('AcaoListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('AcaoListaTab') !== null ? $Aplic->getEstado('AcaoListaTab') : 0);

if (isset($_REQUEST['acaobusca'])) $Aplic->setEstado('acaobusca', getParam($_REQUEST, 'acaobusca', null));
$pesquisa = $Aplic->getEstado('acaobusca') !== null ? $Aplic->getEstado('acaobusca') : '';

$sql = new BDConsulta;

$lista_programas=array('' => '');
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();

if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="'.$u.'" />';
	$botoesTitulo = new CBlocoTitulo('Lista de A��es Sociais', '../../../modulos/social/imagens/acao.gif', $m, $m.'.'.$a);
	$programas='<tr><td align="right" style="white-space: nowrap">'.dica('Programa Social', 'Filtre as fam�lias por programa social em que est�o inseridas.').'Programa:'.dicaF().'</td><td align="left" style="white-space: nowrap">'. selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onChange="document.frm_filtro.submit();"', $social_id) .'</td></tr>';
	$pesquisar='<tr><td align="right" style="white-space: nowrap">'.dica('Pesquisa', 'Pesquisar as a��es sociais pelo campo texto � direita.').'Pesquisar:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input type="text" class="texto" style="width:145px;" name="acaobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisa.'"/><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=acao_lista&acaobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$pesquisar.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$programas.'</table>');
	if ($Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao') || $Aplic->usuario_super_admin) $botoesTitulo->adicionaCelula('<table><tr><td style="white-space: nowrap">'.dica('Nova A��o Social', 'Criar uma nova a��o social, que ser� integrante de um programa social.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'acao_editar\'; frm_filtro.submit();" ><span>nova&nbsp;a��o</span></a>'.dicaF().'</td></tr><tr><td style="white-space: nowrap"></td></tr></table>');
	$botoesTitulo->adicionaCelula('<td align="right" style="white-space: nowrap">'.dica('Imprimir A��es Sociais', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a lista de a��es sociais.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo '</form>';
	}

if (!$dialogo)echo estiloTopoCaixa();
include_once(BASE_DIR.'/modulos/social/ver_idx_acao.php');

if (!$dialogo) echo estiloFundoCaixa();
if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script type="text/javascript">self.print()</script>';

?>
