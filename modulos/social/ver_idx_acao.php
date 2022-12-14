<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $m, $a, $u, $estilo_interface, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $social_id , $pesquisa;


$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_projetos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 




$ordenar=getParam($_REQUEST, 'ordenar', 'social_acao_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_acao');
$sql->adCampo('count(DISTINCT social_acao.social_acao_id)');
if ($social_id) $sql->adOnde('social_acao_social="'.$social_id.'"');
if ($pesquisa) $sql->adOnde('(social_acao_nome LIKE \'%'.$pesquisa.'%\' OR social_acao_descricao LIKE \'%'.$pesquisa.'%\')');
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_acao');
$sql->adCampo('DISTINCT social_acao.social_acao_id, social_acao_cor, social_acao_nome, social_acao_descricao');
if ($social_id) $sql->adOnde('social_acao_social="'.$social_id.'"');
if ($pesquisa) $sql->adOnde('(social_acao_nome LIKE \'%'.$pesquisa.'%\' OR social_acao_descricao LIKE \'%'.$pesquisa.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $config['qnt_projetos']);

$acao=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'A??o', 'A??es','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da A??o Social', 'Neste campo fica a cor de identifica??o da a??o social.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da A??o Socia', 'Neste campo fica um nome para identifica??o da a??o social.').'Nome'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri??o da A??o Socia', 'Neste campo fica a descri??o da a??o social.').'Descri??o'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = 0; $i < count($acao); $i++) {
	$linha = $acao[$i];
	$qnt++;

	
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td style="white-space: nowrap" width="20">'.($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao') ? dica('Editar Social', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar o programa social.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=acao_editar&social_acao_id='.$linha['social_acao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['social_acao_cor'].'"><font color="'.melhorCor($linha['social_acao_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=acao_ver&social_acao_id='.$linha['social_acao_id'].'\');">'.($linha['social_acao_nome'] ? $linha['social_acao_nome'] : '&nbsp;').'</a></td>';
	echo '<td>'.($linha['social_acao_descricao'] ? $linha['social_acao_descricao'] : '&nbsp;').'</td>';
	echo '</tr>';

	}
if (!count($acao)) echo '<tr><td colspan=20><p>Nenhuma a??o social encontrada.</p></td></tr>';
echo '</table>';

?>