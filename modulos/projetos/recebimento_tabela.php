<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
global $m, $a, $u, $estilo_interface, $dialogo, $tab, $projeto_id;
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;
$pagina=getParam($_REQUEST, 'pagina', 1);

$xpg_tamanhoPagina = ($dialogo ? 90000 : 30);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 




$ordenar=getParam($_REQUEST, 'ordenar', 'projeto_recebimento_data_prevista');
$ordem=getParam($_REQUEST, 'ordem', '0');


$obj = new CProjeto();
$obj->load($projeto_id);
$sql = new BDConsulta();

$editar=permiteEditar($obj->projeto_acesso,$obj->projeto_id);


$sql->adTabela('projeto_recebimento');
$sql->adCampo('projeto_recebimento.*');
if ($tab==1) $sql->adOnde('projeto_recebimento_provisorio=1');	
if ($tab==2) $sql->adOnde('projeto_recebimento_definitivo=1');	
$sql->adOnde('projeto_recebimento_projeto='.$projeto_id);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('projeto_recebimento_id');
$recebimentos=$sql->Lista();
$sql->limpar();



$xpg_totalregistros = ($recebimentos ? count($recebimentos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'recebimento', 'recebimentos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identifica??o da recebimento.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_numero&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_numero' ? imagem('icones/'.$seta[$ordem]) : '').dica('N?mero do recebimento', 'Neste campo fica a n?mero do recebimento.').'N?mero'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_data_prevista&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_data_prevista' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data da Prevista', 'Neste campo fica a data prevista para o recebimento.').'Previsto'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_data_entrega&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_data_entrega' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data da recebimento', 'Neste campo fica a data do recebimento.').'Recebido'.dicaF().'</a></th>';


echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons?vel pela Entrega', 'O '.$config['usuario'].' respons?vel pela entrega.').'Entrega'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons?vel pelo Recebimento', 'O contato que recebeu o produto/servi?o.').'Recebimento'.dicaF().'</a></th>';

echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $recebimentos[$i];
	$qnt++;
	echo '<tr>';
	if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar a recebimento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=recebimento_editar&projeto_id='.$projeto_id.'&projeto_recebimento_id='.$linha['projeto_recebimento_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_recebimento_cor'].'"><font color="'.melhorCor($linha['projeto_recebimento_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td>'.link_recebimento($linha['projeto_recebimento_id']).'</td>';
	echo '<td>'.($linha['projeto_recebimento_data_prevista'] ? retorna_data($linha['projeto_recebimento_data_prevista'], false): '&nbsp;').'</td>';
	echo '<td>'.($linha['projeto_recebimento_data_entrega'] ? retorna_data($linha['projeto_recebimento_data_entrega'], false): '&nbsp;').'</td>';
	echo '<td>'.link_usuario($linha['projeto_recebimento_responsavel'],'','','esquerda').'</td>';
	echo '<td style="white-space: nowrap">'.link_contato($linha['projeto_recebimento_cliente'],'','','esquerda').'</td>';
	echo '</tr>';

	}
if (!count($recebimentos)) echo '<tr><td colspan=20><p>Nenhuma recebimento encontrada.</p></td></tr>';
echo '</table>';
?>