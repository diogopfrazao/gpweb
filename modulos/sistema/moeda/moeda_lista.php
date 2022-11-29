<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $m, $a, $u, $dialogo;

if (!$dialogo) $Aplic->salvarPosicao();

$sql = new BDConsulta;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['moedatextobusca'])) $Aplic->setEstado('moedatextobusca', getParam($_REQUEST, 'moedatextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('moedatextobusca') ? $Aplic->getEstado('moedatextobusca') : '');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaMoedaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaMoedaTab') !== null ? $Aplic->getEstado('ListaMoedaTab') : 0);


if (!$dialogo){
	$Aplic->salvarPosicao();
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="'.$u.'" />';
	$botoesTitulo = new CBlocoTitulo('Lista de Moedas', 'moeda.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e A��es','Clique nesta barra para esconder/mostrar os filtros e as a��es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/moeda_p.png').'&nbsp;Filtros e A��es</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e s�mbolo das moedas').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="moedatextobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=moeda&a=moeda_lista&tab='.$tab.'&moedatextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$nova_moeda=($podeAdicionar || $Aplic->usuario_admin ? '<tr><td style="white-space: nowrap" align=right>'.dica('Nova Moeda', 'Criar um nova moeda.').'<a href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'moeda_editar\'; frm_filtro.submit();" ><img src="'.acharImagem('moeda_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');
	$imprimir='<tr><td align="right" style="white-space: nowrap">'.dica('Imprimir Moeda', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a lista de moedas.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&u='.$u.'&dialogo=1&tab='.$tab.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procuraBuffer.'</table></td><td><table cellspacing=0 cellpadding=0>'.$nova_moeda.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	echo '</form>';
	}






$caixaTab = new CTabBox('m='.$m.'&a='.$a.'&u='.$u, '', $tab);
if (!$dialogo){
	$caixaTab->adicionar(BASE_DIR.'/modulos/sistema/moeda/moeda_lista_idx', 'Ativas',null,null,'Ativas','Visualizar as moedas ativas.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/sistema/moeda/moeda_lista_idx', 'Inativas',null,null,'Inativas','Visualizar as moedas inativas.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/sistema/moeda/moeda_lista_idx', 'Todas',null,null,'Todas','Visualizar todas as moeda.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else {
	include_once(BASE_DIR.'/modulos/sistema/moeda/moeda_ver_idx.php');
	if (!($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
	}

if($Aplic->profissional) $Aplic->carregarComboMultiSelecaoJS();

?>
<script type="text/javascript">



</script>