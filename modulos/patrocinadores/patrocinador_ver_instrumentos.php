<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $m, $a, $u, $estilo_interface, $Aplic, $patrocinador_id, $tab;


$ordenarPor=getParam($_REQUEST, 'ordenar', 'instrumento_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina=getParam($_REQUEST, 'pagina', 1);


$xpg_tamanhoPagina = $config['qnt_instrumentos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 




$sql = new BDConsulta();
$sql->adTabela('patrocinadores_instrumentos');
$sql->esqUnir('instrumento', 'instrumento', 'instrumento.instrumento_id = patrocinadores_instrumentos.instrumento_id');
$sql->adCampo('instrumento.instrumento_id, instrumento_data_inicio, instrumento_data_termino, instrumento_responsavel, instrumento_acesso, instrumento_nome, instrumento_valor_atual');
$sql->adOnde('patrocinador_id='.$patrocinador_id);
$sql->adOrdem($ordenarPor.($seta ? ' ASC': ' DESC'));
$instrumentos = $sql->Lista();
$sql->limpar();



$xpg_totalregistros = ($instrumentos ? count($instrumentos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'instrumento', 'instrumentos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='instrumento_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Neste campo fica um nome para identifica��o d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Nome d'.$config['genero_instrumento'].' '.$config['instrumento'].dicaF().'</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='instrumento_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'Neste campo fica o respons�vel pel'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Respons�vel'.dicaF().'</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_data_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='instrumento_data_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('In�cio', 'Neste campo fica a datade in�cio d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'In�cio'.dicaF().'</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_data_termino&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='instrumento_data_termino' ? imagem('icones/'.$seta[$ordem]) : '').dica('T�rmino', 'Neste campo fica a data de t�rmino d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'T�rmino'.dicaF().'</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_valor_atual&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='instrumento_valor_atual' ? imagem('icones/'.$seta[$ordem]) : '').dica('Valor Atual', 'Neste campo fica o valor atual.').'Valor atual'.dicaF().'</a></th>';



echo '</tr>';

$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $instrumentos[$i];
	if (permiteAcessarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id'])){	
		$qnt++;
		$editar=permiteEditarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id']);
		echo '<tr>';
		echo '<td style="white-space: nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=instrumentos&a=instrumento_editar&instrumento_id='.$linha['instrumento_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td style="white-space: nowrap">'.dica($linha['instrumento_nome'], 'Clique para visualizar os detalhes d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=instrumentos&a=instrumento_ver&instrumento_id='.$linha['instrumento_id'].'\');">'.$linha['instrumento_nome'].'</a>'.dicaF().'</td>';
		echo '<td>'.link_usuario($linha['instrumento_responsavel'],'','','esquerda').'</td>';
		echo '<td style="white-space: nowrap" align="center">'.retorna_data($linha['instrumento_data_inicio'], false).'</td>';
		echo '<td style="white-space: nowrap" align="center">'.retorna_data($linha['instrumento_data_termino'], false).'</td>';
		
		echo '<td style="white-space: nowrap" align="center">'.number_format($linha['instrumento_valor_atual'], 2, ',', '.').'</td>';
		echo '</tr>';
		}
	}
if (!count($instrumentos)) echo '<tr><td colspan=20><p>Nenhum instrumento encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="8"><p>N�o tem autoriza��o para visualizar nenhum dos instrumentos.</p></td></tr>';		
echo '</table>';
?>