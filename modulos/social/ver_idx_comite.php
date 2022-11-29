<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
 

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $m, $a, $u, $estilo_interface, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $municipio_id , $social_id, $acao_id, $social_comite_comunidade_id, $social_comite_id, $pesquisa;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_projetos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 




$ordenar=getParam($_REQUEST, 'ordenar', 'social_comite_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_comite');
if ($social_id || $acao_id) $sql->esqUnir('social_comite_acao', 'social_comite_acao', 'social_comite_acao_comite=social_comite_id');
if ($social_id || $acao_id) $sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_comite_acao_acao');
$sql->adCampo('count(DISTINCT social_comite_id)');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_comite_acao_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_comite_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_comite_municipio="'.$municipio_id.'"');
if ($social_comite_comunidade_id) $sql->adOnde('social_comite_comunidade='.$social_comite_comunidade_id);	
if ($pesquisa) $sql->adOnde('(social_comite_nome LIKE \'%'.$pesquisa.'%\')');
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_comite');
if ($social_id || $acao_id) $sql->esqUnir('social_comite_acao', 'social_comite_acao', 'social_comite_acao_comite=social_comite_id');
if ($social_id || $acao_id) $sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_comite_acao_acao');
$sql->adCampo('DISTINCT social_comite_id, social_comite_nome');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_comite_acao_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_comite_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_comite_municipio="'.$municipio_id.'"');
if ($social_comite_comunidade_id) $sql->adOnde('social_comite_comunidade='.$social_comite_comunidade_id);	
if ($pesquisa) $sql->adOnde('(social_comite_nome LIKE \'%'.$pesquisa.'%\' OR social_comite_conjuge LIKE \'%'.$pesquisa.'%\' OR social_comite_nis LIKE \'%'.$pesquisa.'%\' OR social_comite_rg LIKE \'%'.$pesquisa.'%\' OR social_comite_cpf LIKE \'%'.$pesquisa.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $config['qnt_projetos']);
$social=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Comit�', 'Comit�s','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=social_comite_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_comite_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica o nome d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Nome'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;

for ($i = 0; $i < count($social); $i++) {

	$linha = $social[$i];
	$qnt++;
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td style="white-space: nowrap" width="20">'.($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comite') ? dica('Editar Comit�', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o comit�.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=comite_editar&social_comite_id='.$linha['social_comite_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=comite_ver&social_comite_id='.$linha['social_comite_id'].'\');">'.($linha['social_comite_nome'] ? $linha['social_comite_nome'] : '&nbsp;').'</a></td>';
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td colspan=20><p>Nenhum comit� encontrado.</p></td></tr>';
echo '</table>';

?>