<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$log_id=getParam($_REQUEST, 'log_id', 0);
$log_id=getParam($_REQUEST, 'log_id', 0);
$gasto=getParam($_REQUEST, 'gasto', 0);
$unidade=getSisValor('TipoUnidade');

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('custo');
$sql->adCampo('custo.*, ((custo_quantidade*custo_custo)*((100+custo_bdi)/100)) AS valor');
if ($log_id) $sql->adOnde('custo_log ='.(int)$log_id);	
else $sql->adOnde('custo_uuid =\''.$uuid.'\'');	
if ($gasto) $sql->adOnde('custo_gasto=1');
else $sql->adOnde('custo_gasto!=1');
$sql->adOrdem('custo_ordem');
$linhas=$sql->Lista();
$sql->limpar();
$qnt=0;

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

$ptres=0;
$pi=0;
foreach($linhas as $linha){
	if ($linha['custo_ptres']) $ptres++;
	if ($linha['custo_pi']) $pi++;
	}


echo '<table cellpadding=0 cellspacing=0 class="tbl1" width="100%">';
if ($gasto) echo '<tr><th colspan=20>Planilha de Gastos Efetuados</th></tr>';
else echo '<tr><th colspan=20>Planilha de Custos Estimados</th></tr>';

echo '<tr>
<th style="white-space: nowrap">'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
<th style="white-space: nowrap">'.dica('Descri��o', 'Descri��o do item.').'Descri��o'.dicaF().'</th>
<th style="white-space: nowrap">'.dica('Unidade', 'A unidade de refer�ncia para o item.').'Unidade'.dicaF().'</th>
<th style="white-space: nowrap">'.dica('Quantidade', 'A quantidade demandada do �tem').'Qnt.'.dicaF().'</th>
<th style="white-space: nowrap">'.dica('Valor Unit�rio', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
($config['bdi'] ? '<th style="white-space: nowrap">'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
'<th style="white-space: nowrap">'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
<th style="white-space: nowrap">'.dica('Valor Total', 'O valor total � o pre�o unit�rio multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').'
<th style="white-space: nowrap">'.dica('Respons�vel', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Respons�vel'.dicaF().'</th>
<th style="white-space: nowrap">'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
($pi ? '<th style="white-space: nowrap">'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
($ptres ? '<th style="white-space: nowrap">'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').
'</tr>';

	
$total=array();
$custo=array();
foreach ($linhas as $linha) {
	echo '<tr align="center">';
	echo '<td align="left">'.++$qnt.' - '.$linha['custo_nome'].'</td>';
	echo '<td align="left">'.($linha['custo_descricao'] ? $linha['custo_descricao'] : '&nbsp;').'</td>';
	echo '<td width=50>'.$unidade[$linha['custo_tipo']].'</td>';
	echo '<td width=50 align="right">'.number_format($linha['custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right" style="white-space: nowrap" width=50>'.number_format($linha['custo_bdi'], 2, ',', '.').'</td>';
	$nd=($linha['custo_categoria_economica'] && $linha['custo_grupo_despesa'] && $linha['custo_modalidade_aplicacao'] ? $linha['custo_categoria_economica'].'.'.$linha['custo_grupo_despesa'].'.'.$linha['custo_modalidade_aplicacao'].'.' : '').$linha['custo_nd'];
	echo '<td>'.$nd.'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	if (isset($exibir['codigo']) && $exibir['codigo']) echo '<td align="center">'.($linha['custo_codigo'] ? $linha['custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo '<td align="center">'.($linha['custo_fonte'] ? $linha['custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo '<td align="center">'.($linha['custo_regiao'] ? $linha['custo_regiao'] : '&nbsp;').'</td>';  
	echo '<td align="left">'.link_usuario($linha['custo_usuario'],'','','esquerda').'</td>';
	echo '<td>'.($linha['custo_data_limite']? retorna_data($linha['custo_data_limite'],false) : '&nbsp;').'</td>';
	if ($pi) echo '<td align="center">'.$linha['custo_pi'].'</td>';
	if ($ptres) echo '<td align="center">'.$linha['custo_ptres'].'</td>';
	echo '</tr>';
	
	if (isset($custo[$linha['custo_moeda']][$nd])) $custo[$linha['custo_moeda']][$nd] += (float)$linha['valor'];
	else $custo[$linha['custo_moeda']][$nd]=(float)$linha['valor'];
	
	if (isset($total[$linha['custo_moeda']])) $total[$linha['custo_moeda']]+=$linha['valor'];
	else $total[$linha['custo_moeda']]=$linha['valor']; 
	
	
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
echo '</table>';

?>