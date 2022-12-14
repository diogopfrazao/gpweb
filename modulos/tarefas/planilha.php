<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic;
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tarefas_subordinadas=getParam($_REQUEST, 'tarefas_subordinadas', 0);

$obj = new CTarefa(($baseline_id ? true : false), true);
$obj->load($tarefa_id);


$impressao=getParam($_REQUEST, 'impressao', 0);
$tipo=getParam($_REQUEST, 'tipo', '');

$sql = new BDConsulta;

$siafi=($Aplic->profissional && $tipo!='estimado' && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso'));

$tr_exibir=($Aplic->profissional && $tipo=='estimado' && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso'));

if ($siafi){
	$sql->adTabela('financeiro_config');
	$sql->adCampo('financeiro_config_valor');
	$sql->adOnde('financeiro_config_campo = \'organizacao\'');
	$tipo_organizacao = $sql->Resultado();
	$sql->limpar();
	if ($tipo_organizacao=='sema_mt') $siafi=false;
	}

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');

if (!$Aplic->profissional) {
	$nd=array(0 => '');
	$nd+= getSisValorND();
	}

$unidade=getSisValor('TipoUnidade');
echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>'.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').'</h1></center></td><td align="right" width="10%">'.(!$impressao ? dica('Imprimir a Planilha', 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&impressao=1&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$tarefas_subordinadas.'&baseline_id='.$baseline_id.'&tipo='.$tipo.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('imprimir_p.png').'</a>'.dicaF() : '').'</td></tr></table>';
if (!$impressao) echo estiloTopoCaixa();
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

if ($tipo=='estimado'){
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_custos', 't', ($baseline_id ? 't.baseline_id='.(int)$baseline_id : ''));
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=t.tarefa_custos_tarefa'.($baseline_id ? ' AND tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('tarefa_nome');
	$sql->adCampo('t.*,((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$sql->adOnde('t.tarefa_custos_tarefa IN ('.$obj->tarefas_subordinadas.')');
	$sql->adOrdem('tarefas.tarefa_inicio, tarefa_custos_ordem');	
	if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
	}
else {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_gastos', 't', ($baseline_id ? 't.baseline_id='.(int)$baseline_id : ''));
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=t.tarefa_gastos_tarefa'.($baseline_id ? ' AND tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('tarefa_nome');
	$sql->adCampo('t.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor');
	$sql->adOnde('t.tarefa_gastos_tarefa IN ('.$obj->tarefas_subordinadas.')');
	$sql->adOrdem('tarefa_inicio, tarefa_nome, tarefa_gastos_ordem');
	if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
	}
$linhas=$sql->Lista();


$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>'.($tarefas_subordinadas && $tarefas_subordinadas!=$tarefa_id ? '<th>'.ucfirst($config['tarefa']).'</th>' : '').
'<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
<th>'.dica('Descri??o', 'Descri??o do item.').'Descri??o'.dicaF().'</th>
<th>'.dica('Unidade', 'A unidade de refer?ncia para o item.').'Un.'.dicaF().'</th>
<th width="40">'.dica('Quantidade', 'A quantidade demandada do ?tem').'Qnt.'.dicaF().'</th>
<th>'.dica('Valor Unit?tio', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
($config['bdi'] ? '<th>'.dica('BDI', 'Benef?cios e Despesas Indiretas, ? o elemento or?ament?rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit?rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n?o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material ? m?o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb?m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
<th width="100">'.dica('Valor Total', 'O valor total ? o pre?o unit?rio multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
'<th>'.dica('Respons?vel', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Respons?vel'.dicaF().'</th>'.
($siafi ? '<th>'.dica('NE', 'A nota de empenho relacionada a este item.').'NE'.dicaF().'</th>' : '').
($tr_exibir ? '<th>'.dica($config['tr'], 'O '.$config['tr'].' que importou o item de custo.').'TR'.dicaF().'</th>' : '').

'</tr>';

$total=array();
$custo=array();
$tarefa_atual=0;
foreach ($linhas as $linha) {
	if ($tipo=='estimado'){
		
	if ($linha['tarefa_custos_tarefa']!=$tarefa_atual) {
			echo '<tr><td colspan=20>'.$linha['tarefa_nome'].'</td></tr>';
			$tarefa_atual=$linha['tarefa_custos_tarefa'];	
			}
		$nd=($linha['tarefa_custos_categoria_economica'] && $linha['tarefa_custos_grupo_despesa'] && $linha['tarefa_custos_modalidade_aplicacao'] ? $linha['tarefa_custos_categoria_economica'].'.'.$linha['tarefa_custos_grupo_despesa'].'.'.$linha['tarefa_custos_modalidade_aplicacao'].'.' : '').$linha['tarefa_custos_nd'];
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_custos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_custos_descricao'] ? $linha['tarefa_custos_descricao'] : '&nbsp;').'</td>';
		echo '<td style="white-space: nowrap">'.$unidade[$linha['tarefa_custos_tipo']].'</td>';
		echo '<td style="white-space: nowrap">'.number_format($linha['tarefa_custos_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_custos_moeda']].' '.number_format($linha['tarefa_custos_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_custos_bdi'], 2, ',', '.').'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$nd.'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_custos_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_custos_codigo'] ? $linha['tarefa_custos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_custos_fonte'] ? $linha['tarefa_custos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_custos_regiao'] ? $linha['tarefa_custos_regiao'] : '&nbsp;').'</td>'; 
		
		
		echo '<td align="left">'.link_usuario($linha['tarefa_custos_usuario'],'','','esquerda').'</td>';
		
		if ($tr_exibir){
			
			$sql->adTabela('tr_custo');
			$sql->adCampo('DISTINCT tr_custo_tr');
			$sql->adOnde('tr_custo_tarefa='.(int)$linha['tarefa_custos_id']);
			$trs = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_trs='';
		
			if (isset($trs) && count($trs)){
					$qnt_trs=count($trs);
					$lista_tr='';
					for ($i = 0, $i_cmp = $qnt_trs; $i < $i_cmp; $i++) $lista_tr.='<br>'.link_tr($trs[$i]);
					$saida_trs.= dica($config['tr'], 'Clique para visualizar '.$config['genero_tr'].'s '.$config['tr'].'.').'<a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_trs_'.$linha['tarefa_custos_id'].'\');">('.$qnt_trs.')</a>'.dicaF().'<span style="display: none" id="lista_trs_'.$linha['tarefa_custos_id'].'">'.$lista_tr.'</span>';
					}
			echo '<td align="left">'.$saida_trs.'</td>';
			}	
			
			
		echo '</tr>';
		
		if (isset($custo[$linha['tarefa_custos_moeda']][$nd])) $custo[$linha['tarefa_custos_moeda']][$nd] += (float)$linha['valor'];
		else $custo[$linha['tarefa_custos_moeda']][$nd]=(float)$linha['valor'];
		
		if (isset($total[$linha['tarefa_custos_moeda']])) $total[$linha['tarefa_custos_moeda']]+=$linha['valor'];
		else $total[$linha['tarefa_custos_moeda']]=$linha['valor']; 
		
		
		
		
		
		}
	else{
		if ($linha['tarefa_gastos_tarefa']!=$tarefa_atual) {
			echo '<tr><td colspan=20>'.$linha['tarefa_nome'].'</td></tr>';
			$tarefa_atual=$linha['tarefa_gastos_tarefa'];	
			}
		$nd=($linha['tarefa_gastos_categoria_economica'] && $linha['tarefa_gastos_grupo_despesa'] && $linha['tarefa_gastos_modalidade_aplicacao'] ? $linha['tarefa_gastos_categoria_economica'].'.'.$linha['tarefa_gastos_grupo_despesa'].'.'.$linha['tarefa_gastos_modalidade_aplicacao'].'.' : '').$linha['tarefa_gastos_nd'];
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_gastos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_gastos_descricao'] ? $linha['tarefa_gastos_descricao'] : '&nbsp;').'</td>';
		echo '<td style="white-space: nowrap">'.$unidade[$linha['tarefa_gastos_tipo']].'</td>';
		echo '<td style="white-space: nowrap">'.number_format($linha['tarefa_gastos_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_gastos_moeda']].' '.number_format($linha['tarefa_gastos_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_gastos_bdi'], 2, ',', '.').'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$nd.'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_gastos_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_gastos_codigo'] ? $linha['tarefa_gastos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_gastos_fonte'] ? $linha['tarefa_gastos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_gastos_regiao'] ? $linha['tarefa_gastos_regiao'] : '&nbsp;').'</td>'; 
		
		
		
		echo '<td align="left">'.link_usuario($linha['tarefa_gastos_usuario'],'','','esquerda').'</td>';
		
		if ($siafi){
			echo '<td style="white-space: nowrap">';
			$sql->adTabela('financeiro_gasto');
			$sql->esqUnir('financeiro_rel_nelista', 'financeiro_rel_nelista', 'financeiro_rel_nelista_id=financeiro_gasto_relnelista');
			$sql->esqUnir('financeiro_nelista', 'financeiro_nelista', 'financeiro_nelista_id=financeiro_rel_nelista_nelista');
			$sql->esqUnir('financeiro_ne', 'financeiro_ne', 'financeiro_ne_id=financeiro_nelista_ne');
			$sql->adCampo('CONE, financeiro_nelista.NUITEM, financeiro_ne_id');
			$sql->adOnde('financeiro_gasto_tarefagasto='.(int)$linha['tarefa_gastos_id']);
			$nes = $sql->lista();
			$sql->limpar();
			$qnt_ne=0;
			foreach($nes as $ne) echo ($qnt_ne++ ? '<br>' : '').'<a href="javascript: void(0);" onclick="popNE('.$ne['financeiro_ne_id'].')">'.$ne['CONE'].'|'.$ne['NUITEM'].'</a>';
			echo '</td>';
			}
			
		echo '<tr>';
		
		if (isset($custo[$linha['tarefa_gastos_moeda']][$nd])) $custo[$linha['tarefa_gastos_moeda']][$nd] += (float)$linha['valor'];
		else $custo[$linha['tarefa_gastos_moeda']][$nd]=(float)$linha['valor'];
		
		if (isset($total[$linha['tarefa_gastos_moeda']])) $total[$linha['tarefa_gastos_moeda']]+=$linha['valor'];
		else $total[$linha['tarefa_gastos_moeda']]=$linha['valor']; 
		} 
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

if (!$impressao) {
	echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close();').'</td>' : '');	
	$link='';
		if ($tipo=='estimado') {
			echo '<td align="right">'.botao('gasto', 'Gastos','Clique para ver a planilha de gastos.','','gasto('.$tarefa_id.')').'</td>';
			}
		elseif ($tipo=='efetivo')  { 
			echo '<td align="right">'.botao('custo', 'Custos Estimados','Clique para ver a planilha de custos estimados.','','custo('.$tarefa_id.')').'</td>';
			}
	echo '</tr></table></td></tr>';
	}
echo '</td></tr></table></form>';
if (!$impressao) echo estiloFundoCaixa();
elseif($impressao && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';


?>
<script type="text/javascript">

function gasto(tarefa_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Gasto', 1024, 500, 'm=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=efetivo', null, window);
	else window.open('./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=efetivo', 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}	

function custo(tarefa_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Custo', 1024, 500, 'm=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=estimado', null, window);
	else window.open('./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=estimado', 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}	

function popNE(financeiro_ne_id){
	window.parent.gpwebApp.popUp("Nota de Empenho", 950, 700, 'm=financeiro&a=siafi_ne_detalhe_pro&dialogo=1&projeto_id='+<?php echo $obj->tarefa_projeto?>+'&financeiro_ne_id='+financeiro_ne_id, null, window);
	}	
	
function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
	
</script>	