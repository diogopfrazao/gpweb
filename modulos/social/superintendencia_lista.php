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

if (isset($_REQUEST['tab'])) $Aplic->setEstado('SuperintendenciaListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('SuperintendenciaListaTab') !== null ? $Aplic->getEstado('SuperintendenciaListaTab') : 0);

if (isset($_REQUEST['estado_sigla'])) $Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');

if (isset($_REQUEST['municipio_id'])) $Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');


if (isset($_REQUEST['social_comunidade_id'])) $Aplic->setEstado('social_comunidade_id', getParam($_REQUEST, 'social_comunidade_id', null));
$social_comunidade_id = ($Aplic->getEstado('social_comunidade_id') !== null ? $Aplic->getEstado('social_comunidade_id') : 0);

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['acao_id'])) $Aplic->setEstado('acao_id', getParam($_REQUEST, 'acao_id', null));
$acao_id = ($Aplic->getEstado('acao_id') !== null ? $Aplic->getEstado('acao_id') : null);

if (!$social_id) $acao_id=null;

if (isset($_REQUEST['familiabusca'])) $Aplic->setEstado('familiabusca', getParam($_REQUEST, 'familiabusca', null));
$pesquisa = $Aplic->getEstado('familiabusca') !== null ? $Aplic->getEstado('familiabusca') : '';

$sql = new BDConsulta;

$lista_programas=array('' => '');
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$comunidades=array(''=>'');
$cidades=array(''=>'');
if (!$municipio_id) $cidades['5300108']='Bras?lia';

if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="'.$u.'" />';
	$botoesTitulo = new CBlocoTitulo('Lista de Superintend?ncias', '../../../modulos/social/imagens/superintendencia.gif', $m, $m.'.'.$a);

	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de op??o ? direita o Estado da superintend?ncia.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Munic?pio', 'Selecione o munic?pio da superintend?ncia.').'Munic?pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_comunidade='<tr><td align="right">'.dica('Comunidade', 'Selecione a comunidade da superintend?ncia.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($municipio_id,'social_comunidade_id', 'class="texto" style="width:160px;"', '', $social_comunidade_id, false).'</div></td></tr>';
	$botao_filtro='<tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ?cone '.imagem('icones/filtrar_p.png').' para filtrar os superintend?ncias pelos par?metros selecionados ? esquerda.').'</a></td></tr>';
	$programas='<tr><td align="right" style="white-space: nowrap">'.dica('Programa Social', 'Filtre os superintend?ncias por programa social em que est?o inseridas.').'Programa:'.dicaF().'</td><td align="left" style="white-space: nowrap">'. selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onchange="mudar_acao()"', $social_id) .'</td></tr>';
	$acoes='<tr><td align="right" style="white-space: nowrap">'.dica('A??o Social', 'Filtre os superintend?ncias pela a??o social.').'A??o:'.dicaF().'</td><td align="left" style="white-space: nowrap"><div id="acao_combo">'.selecionar_acao_para_ajax($social_id, 'acao_id', 'size="1" style="width:160px;" class="texto"', '', $acao_id, false).'</div></td></tr>';
	$pesquisar='<tr><td align="right" style="white-space: nowrap">'.dica('Pesquisa', 'Pesquisar os superintend?ncias pelo campo texto ? direita.').'Pesquisar:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input type="text" class="texto" style="width:145px;" name="familiabusca" onChange="document.frm_filtro.submit();" value="'.$pesquisa.'"/><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=superintendencia_lista&familiabusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ?cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$programas.$acoes.$pesquisar.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_estado.$procurar_municipio.$procurar_comunidade.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$botao_filtro.'</table>');
	if ($Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comite') || $Aplic->usuario_super_admin) $botoesTitulo->adicionaCelula('<table><tr><td style="white-space: nowrap">'.dica('Nova Superintend?ncia', 'Criar um novo cadastro de uma superintend?ncia.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'superintendencia_editar\'; frm_filtro.submit();" ><span>nova&nbsp;superintend?ncia</span></a>'.dicaF().'</td></tr><tr><td style="white-space: nowrap"></td></tr></table>');
	$botoesTitulo->adicionaCelula('<td align="right" style="white-space: nowrap">'.dica('Imprimir Superintend?ncias', 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir a lista de fam?lias.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo '</form>';
	}


if (!$dialogo)echo estiloTopoCaixa();
include_once(BASE_DIR.'/modulos/social/ver_idx_superintendencia.php');
if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
if (!$dialogo) echo estiloFundoCaixa();

?>
<script type="text/javascript">

function mudar_acao(){
	xajax_acao_ajax(document.getElementById('social_id').value, 0);
	}

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
	document.getElementById('social_comunidade_id').length=0;
	}	
	
function mudar_comunidades(){
	var municipio_id=(document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>);
	var social_comunidade_id=(document.getElementById('social_comunidade_id').value ? document.getElementById('social_comunidade_id').value : <?php echo ($social_comunidade_id ? $social_comunidade_id : 0) ?>);
	xajax_selecionar_comunidade_ajax(municipio_id, 'social_comunidade_id', 'combo_comunidade', 'class="texto" size=1 style="width:160px;"', '', social_comunidade_id); 	
	}		
	

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	

</script>