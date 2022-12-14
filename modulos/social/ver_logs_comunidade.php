<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
include_once BASE_DIR.'/modulos/social/comunidade.class.php';
global $Aplic, $social_comunidade_id, $podeExcluir, $podeEditar, $m, $tab;

$sql = new BDConsulta;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$codigo_custo=getParam($_REQUEST, 'codigo_custo', '0');

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', 0));
$usuario_id = $Aplic->getEstado('usuario_id') ? $Aplic->getEstado('usuario_id') : 0;


$nd=array(0 => '');
$nd+= getSisValorND();
$RefRegistroAcao = getSisValor('RefRegistroTarefa');
$RefRegistroAcaoImagem = getSisValor('RefRegistroTarefaImagem');
$ordenar=getParam($_REQUEST, 'ordenar', 'social_comunidade_log_data');
$ordem=getParam($_REQUEST, 'ordem', '0');

$ordenacao=$ordenar.($ordem ? ' DESC' : ' ASC');

echo '<form name="frmExcluir2" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_comunidade" />';
echo '<input type="hidden" name="social_comunidade_log_id" value="" />';
echo '<input type="hidden" name="social_comunidade_log_comunidade" value="'.$social_comunidade_id.'" />';
echo '<input type="hidden" name="del" value="1" />';
echo '</form>';

echo '<form name="frmFiltro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="social_comunidade_id" value="'.$social_comunidade_id.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';

echo '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="std2"><tr><td><table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th width="98%">&nbsp;</th><th width="1%" style="white-space: nowrap">'.dica('Filtro', 'Selecione de qual '.$config['usuario'].' deseja ver os registros de cadastrados.').'Filtro'.dicaF().'</th><th><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /></th><th><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></th></tr>';
echo '</table></td></tr></form>';
echo '<tr><td><table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inser??o do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Refer?ncia', 'A forma como se chegou aos dados que est?o registrandos.').($ordenar=='social_comunidade_log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').'Ref.'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Nome do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_nome' ? imagem('icones/'.$seta[$ordem]) : '').'T?tulo'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_criador&ordem='.($ordem ? '0' : '1').'\');">'.dica('Respons?vel', 'Respons?vel pela inser??o do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_criador' ? imagem('icones/'.$seta[$ordem]) : '').'Respons?vel'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_horas&ordem='.($ordem ? '0' : '1').'\');">'.dica('Horas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_horas' ? imagem('icones/'.$seta[$ordem]) : '').'Horas'.dicaF().'</a></th>';
echo '<th width="100%"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Coment?rios', 'Coment?rios sobre o registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Coment?rios'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_nd&ordem='.($ordem ? '0' : '1').'\');">'.dica('ND', 'N?mero de Despesa j? empenhado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_nd' ? imagem('icones/'.$seta[$ordem]) : '').'ND'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_comunidade_id='.$social_comunidade_id.'&ordenar=social_comunidade_log_custo&ordem='.($ordem ? '0' : '1').'\');">'.dica('Custo', 'Custo extras gasto n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_comunidade_log_custo' ? imagem('icones/'.$seta[$ordem]) : '').'Custos'.dicaF().'</a></th>';
echo '<th>&nbsp;</th><th>&nbsp;</th></tr>';


$sql->adTabela('social_comunidade_log');
$sql->adCampo('social_comunidade_log.*');
$sql->adUnir('usuarios', 'usuarios', 'usuario_id = social_comunidade_log_criador');
$sql->adOnde('social_comunidade_log_comunidade = '.$social_comunidade_id);
if ($usuario_id) $sql->adOnde('social_comunidade_log_criador = '.$usuario_id);

$sql->adOrdem($ordenacao);
$logs = $sql->Lista();

$hrs = 0;
$qnt=0;
$custo=array();

foreach ($logs as $linha) {
	$qnt++;
	$social_comunidade_log_horas = intval($linha['social_comunidade_log_horas']) ? new CData($linha['social_comunidade_log_horas']) : null;
	$estilo = $linha['social_comunidade_log_problema'] ? 'background-color:#cc6666;color:#ffffff' : '';
	echo '<tr bgcolor="white" valign="top"><td>';
	echo($podeEditar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&tab=1&social_comunidade_id='.$social_comunidade_id.'&social_comunidade_log_id='.$linha['social_comunidade_log_id'].'\');">'.imagem('icones/editar.gif').'</a>' : '&nbsp;');
	echo '</td><td style="white-space: nowrap" >'.retorna_data($linha['social_comunidade_log_data'], false).'</td>';
	$imagem_referencia = '-';
	if ($linha['social_comunidade_log_referencia'] > 0) {
		if (isset($RefRegistroAcaoImagem[$linha['social_comunidade_log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroAcaoImagem[$linha['social_comunidade_log_referencia']], imagem('icones/'.$RefRegistroAcaoImagem[$linha['social_comunidade_log_referencia']]).' '.$RefRegistroAcao[$linha['social_comunidade_log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroAcao[$linha['social_comunidade_log_referencia']])) $imagem_referencia = $RefRegistroAcao[$linha['social_comunidade_log_referencia']];
		}
	echo '<td align="center" valign="middle">'.$imagem_referencia.'</td>';
	echo '<td style="white-space: nowrap;'.$estilo.'">'.($linha['social_comunidade_log_nome'] ? $linha['social_comunidade_log_nome'] : '&nbsp;').'</td>';
	echo '<td>'.link_usuario($linha['social_comunidade_log_criador'],'','','esquerda').'</td>';
	echo '<td width="100" align="right">'.($linha['social_comunidade_log_horas'] ? number_format($linha['social_comunidade_log_horas'], 2, ',', '.') : '&nbsp;').'</td>';
	echo '<td>'.($linha['social_comunidade_log_descricao'] ? str_replace("\n", '<br />', $linha['social_comunidade_log_descricao']) : '&nbsp;').'</td>';
	$nd=($linha['social_comunidade_log_categoria_economica'] && $linha['social_comunidade_log_grupo_despesa'] && $linha['social_comunidade_log_modalidade_aplicacao'] ? $linha['social_comunidade_log_categoria_economica'].'.'.$linha['social_comunidade_log_grupo_despesa'].'.'.$linha['social_comunidade_log_modalidade_aplicacao'].'.' : '').$linha['social_comunidade_log_nd'];
	echo '<td align="center" valign="middle">'.($linha['social_comunidade_log_custo']!=0 ? $nd: '&nbsp;').'</td>';
	echo '<td width="100" align="right">'.number_format( $linha['social_comunidade_log_custo'], 2, ',', '.').'</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>'.($podeEditar ?  dica('Excluir Registro', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir este registro.').'<a href="javascript:excluir2('.$linha['social_comunidade_log_id'].');" >'.imagem('icones/remover.png').'</a>' : '&nbsp;').'</td></tr>';
	$hrs += (float)$linha['social_comunidade_log_horas'];
	if (isset($custo[$nd]))	$custo[$nd] += (float)$linha['social_comunidade_log_custo'];
	else $custo[$nd] = (float)$linha['social_comunidade_log_custo'];
	}
if (!$qnt) echo '<tr><td bgcolor="white" colspan=20><p>Nenhum registro de ocorr?ncia encontrado.</p></td></tr></table>';	
else {
	echo '<tr bgcolor="white" valign="top">';
	echo '<td colspan="6" align="right" valign="middle"><b>Total de Horas:</b></td>';
	$minutos = (int)(($hrs - ((int)$hrs)) * 60);
	$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	echo '<td align="left" valign="middle"><b>'.(int)$hrs.':'.$minutos.'</b></td>';
	echo '<td align="right" colspan="2" style="white-space: nowrap"><b>Custos</b>';
	foreach ($custo as $nd => $somatorio) {
		if ($somatorio > 0) echo '<br>'.$nd;
		}
	echo '<br><b>Total Geral</b>';
	echo'</td>';
	echo '<td align="right">';
	$somatorio_total=0;
	foreach ($custo as $nd => $somatorio) {
		if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		$somatorio_total+=$somatorio;
		}
	 echo '<br><b>'.number_format($somatorio_total, 2, ',', '.').'</b></td>';	
	echo '<td></tr>';
	echo '</table></td></tr><tr><td><table><tr><td>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Legenda</td><td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro Normal', 'Todos os registros que n?o forem marcados como tendo problema ser?o considerados normais.').'Registro Normal'.dicaF().'&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro de Problema', 'Todos os registros que forem marcados como tendo problema aparecer?o com o sum?rio na cor vermelha.').'Registro de Problema'.dicaF().'</td></tr></table>';
	}
echo '</table></td></tr></table>';

echo estiloFundoCaixa();


?>



<script type="text/javascript">
	
function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usu?rio','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	frmFiltro.submit();
	}	

	
function excluir2(id) {
	if (confirm( 'Tem certeza que deseja excluir o registro da ocorr?ncia?' )) {
		document.frmExcluir2.social_comunidade_log_id.value = id;
		document.frmExcluir2.submit();
		}
	}
</script>
