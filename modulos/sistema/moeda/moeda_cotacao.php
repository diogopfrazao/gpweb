<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $m, $a, $u, $moeda_id, $dialogo, $estilo_interface, $tab;

$ordenar=getParam($_REQUEST, 'ordenar', 'moeda_cotacao_data');
$ordem=getParam($_REQUEST, 'ordem', 1);
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : 30);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$sql = new BDConsulta;
$sql->adTabela('moeda_cotacao');
$sql->adCampo('count(DISTINCT moeda_cotacao_id) AS soma');
$sql->adOnde('moeda_cotacao_moeda ='.(int)$moeda_id);	
$xtotalregistros = $sql->Resultado();
$sql->limpar();

$sql->adTabela('moeda_cotacao');
$sql->adCampo('moeda_cotacao_id, formatar_data(moeda_cotacao_data, \'%d/%m/%Y\') AS data, moeda_cotacao_cotacao');
$sql->adOnde('moeda_cotacao_moeda ='.(int)$moeda_id);	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$lista = $sql->lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;

mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Cotações', 'Cotação','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 '.(!$dialogo ? 'class="std"' : '').'><tr><td>';
echo '<table cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap" width=70><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&pagina='.$pagina.'&moeda_id='.$moeda_id.($tab ? '&tab='.$tab : '').'&ordenar=moeda_cotacao_data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='moeda_cotacao_data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Neste campo fica a data da cotação da moeda.').'Data'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap" width=70><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&pagina='.$pagina.'&moeda_id='.$moeda_id.($tab ? '&tab='.$tab : '').'&ordenar=moeda_cotacao_cotacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='moeda_cotacao_cotacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cotação', 'Neste campo fica o valor da cotação da moeda.').'Cotação'.dicaF().'</a></th>';
echo '</tr>';

foreach($lista as $linha){
	echo '<tr>';
	if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.dica('Editar Cotação', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a cotação da moeda.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=moeda_cotacao_editar&u='.$u.'&moeda_id='.$moeda_id.'&pagina='.$pagina.'&ordem='.$ordem.'&ordenar='.$ordenar.'&moeda_cotacao_id='.$linha['moeda_cotacao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
	echo '<td align=center>'.$linha['data'].'</td>';
	echo '<td align=right>'.number_format($linha['moeda_cotacao_cotacao'], 4, ',', '.').'</td>';
	echo '</tr>';
	}	
echo '</table></td></tr></table>';	
?>