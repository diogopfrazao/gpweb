<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $config;


$projeto_id=getParam($_REQUEST, 'projeto_id', 0);


if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	$portfolio=ser_portfolio($projeto_id);
	if (!$portfolio) $portfolio=$projeto_id;
	}
else $portfolio=$projeto_id;

$tipo=getParam($_REQUEST, 'tipo', '');
$unidade=getSisValor('TipoUnidade');

$sql = new BDConsulta;

/*
$siafi=($Aplic->profissional && $tipo!='estimado' && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso'));
if ($siafi){
	$sql->adTabela('financeiro_config');
	$sql->adCampo('financeiro_config_valor');
	$sql->adOnde('financeiro_config_campo = \'organizacao\'');
	$tipo_organizacao = $sql->Resultado();
	$sql->limpar();
	if ($tipo_organizacao=='sema_mt') $siafi=false;
	}
*/

echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>Custos Estimados - '.link_projeto($projeto_id, '', '', '', '',true).'</h1></center></td><td align="right" width="10%">'.dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=projetos&a='.$a.'&dialogo=1&projeto_id='.$projeto_id.'\', null, window);' : 'window.open(\'./index.php?m=projetos&a='.$a.'&dialogo=1&projeto_id='.$projeto_id.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr></table>';
echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td valign="top" align="center">';

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();



$sql->adTabela('projeto_custo');
$sql->adCampo('projeto_custo.*,((projeto_custo_quantidade*projeto_custo_custo)*((100+projeto_custo_bdi)/100)) AS valor, projeto_custo_quantidade AS quantidade');
$sql->adOnde('projeto_custo_projeto IN ('.$portfolio.')');
$sql->adOrdem('projeto_custo_projeto, projeto_custo_ordem');	
if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('projeto_custo_aprovado = 1');

$linhas= $sql->Lista();
$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>
<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
<th>'.dica('Descri��o', 'Descri��o do item.').'Descri��o'.dicaF().'</th>
<th>'.dica('Unidade', 'A unidade de refer�ncia para o item.').'Un.'.dicaF().'</th>
<th width="40">'.dica('Quantidade', 'A quantidade demandada do �tem').'Qnt.'.dicaF().'</th>
<th>'.dica('Valor Unit�rio', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
($config['bdi'] ? '<th>'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
<th width="100">'.dica('Valor Total', 'O valor total � o pre�o unit�rio multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
'<th>'.dica('Respons�vel', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Respons�vel'.dicaF().'</th>';
//echo ($siafi ? '<th>'.dica('NE', 'A nota de empenho relacionada a este item.').'NE'.dicaF().'</th>' : '').
echo '</tr>';

$total=array();
$custo=array();
$projeto=0;
foreach ($linhas as $linha) {
	if ($projeto!=$linha['projeto_custo_projeto']){
		echo '<tr><td align="left" colspan=20>'.link_projeto($linha['projeto_custo_projeto']).'</td></tr>';
		$projeto=$linha['projeto_custo_projeto'];
		$qnt=0;
		}
	if (isset($linha['projeto_custo_data_inicio'])) $data = new CData($linha['projeto_custo_data_inicio']);
	echo '<tr align="center">';
	echo '<td align="left">'.++$qnt.' - '.$linha['projeto_custo_nome'].'</td>';
	echo '<td align="left">'.($linha['projeto_custo_descricao'] ? $linha['projeto_custo_descricao'] : '&nbsp;').'</td>';
	echo '<td style="white-space: nowrap">'.$unidade[$linha['projeto_custo_tipo']].'</td>';
	echo '<td align="right" style="white-space: nowrap">'.number_format($linha['quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['projeto_custo_moeda']].' '.number_format($linha['projeto_custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['projeto_custo_bdi'], 2, ',', '.').'</td>';
	
	$nd=($linha['projeto_custo_categoria_economica'] && $linha['projeto_custo_grupo_despesa'] && $linha['projeto_custo_modalidade_aplicacao'] ? $linha['projeto_custo_categoria_economica'].'.'.$linha['projeto_custo_grupo_despesa'].'.'.$linha['projeto_custo_modalidade_aplicacao'].'.' : '').$linha['projeto_custo_nd'];
	
	echo '<td style="white-space: nowrap">'.$nd.'</td>';
	echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['projeto_custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['projeto_custo_codigo'] ? $linha['projeto_custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['projeto_custo_fonte'] ? $linha['projeto_custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['projeto_custo_regiao'] ? $linha['projeto_custo_regiao'] : '&nbsp;').'</td>'; 
	
	echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['projeto_custo_usuario'],'','','esquerda').'</td>';
	
	echo '</tr>';
	
	
	if (isset($custo[$linha['projeto_custo_moeda']][$nd])) $custo[$linha['projeto_custo_moeda']][$nd] += (float)$linha['valor'];
	else $custo[$linha['projeto_custo_moeda']][$nd]=(float)$linha['valor'];
	
	if (isset($total[$linha['projeto_custo_moeda']])) $total[$linha['projeto_custo_moeda']]+=$linha['valor'];
	else $total[$linha['projeto_custo_moeda']]=$linha['valor']; 
	}
$tem_total=false;
foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
	
if ($tem_total) {
	foreach ($custo as $tipo_moeda => $linha) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}
if (!$qnt) echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';

echo '</table></td></tr>';

echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close()').'</td>' : '');

echo '</tr></table></td></tr>';
echo '</td></tr></table></form>';
echo estiloFundoCaixa();
?>
<script type="text/javascript">

</script>	