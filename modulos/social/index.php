<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
global $dialogo;

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('SocialListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('SocialListaTab') !== null ? $Aplic->getEstado('SocialListaTab') : 0);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

$pg_id=getParam($_REQUEST, 'pg_id', 0);


if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="'.$u.'" />';
	echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
	$botoesTitulo = new CBlocoTitulo('Lista de Programas Sociais', '../../../modulos/social/imagens/social.gif', $m, $m.'.'.$a);
	$procurar_om='<tr><td align=right>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ?cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada ? esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinadas','Clique neste ?cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas ? selecionada.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N?o Incluir Subordinadas','Clique neste ?cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas ? selecionada.').'</a></td>' : '').'</tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.'</table>');
	if ($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_social')) $botoesTitulo->adicionaCelula('<table><tr><td style="white-space: nowrap">'.dica('Novo Programa Social', 'Criar um novo programa social.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'social_editar\'; frm_filtro.submit();" ><span>programa&nbsp;social</span></a>'.dicaF().'</td></tr><tr><td style="white-space: nowrap"></td></tr></table>');

	$botoesTitulo->adicionaCelula('<td align="right" style="white-space: nowrap">'.dica('Imprimir Programas Sociais', 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir a lista de programas sociais.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a=index&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo '</form>';
	}

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}



if (!$dialogo)echo estiloTopoCaixa();
include_once(BASE_DIR.'/modulos/social/ver_idx_social.php');
if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';

?>
<script type="text/javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
</script>