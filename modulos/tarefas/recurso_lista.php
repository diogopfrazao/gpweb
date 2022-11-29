<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $baseline_id, $tarefa_id, $dialogo;

$icone_ver_obs=imagem('icones/msg10000.gif','Ver Observa��es','Clique neste �cone '.imagem('icones/msg10000.gif').' para ver as observa��es');

$sql = new BDConsulta;

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();


$sql->adTabela('projetos');
$sql->esqUnir('tarefas','tarefas', 'projetos.projeto_id=tarefas.tarefa_projeto');
$sql->adCampo('projeto_moeda');
$sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
$projeto_moeda=$sql->Resultado();
$sql->limpar();
	
$divisor_cotacao=($projeto_moeda!=1 ? cotacao($projeto_moeda, date('Y-m-d')) : 1);


$sql->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefa', 'recurso_tarefa', ($baseline_id ? 'recurso_tarefa.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = recurso_tarefa_recurso');
$sql->adCampo('recurso_tarefa_id, recursos.recurso_id, recurso_tipo, recurso_tarefa_custo, recurso_tarefa_tarefa, recurso_tarefa_recurso, recurso_nivel_acesso, recurso_tarefa_aprovou, recurso_tarefa_aprovado, recurso_tarefa_valor_hora, formatar_data(recurso_tarefa_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(recurso_tarefa_fim, \'%d/%m/%Y %H:%i\') AS fim, recurso_tarefa_duracao, recurso_tarefa_quantidade, recurso_tarefa_percentual, formatar_data(recurso_tarefa_data, \'%d/%m/%Y %H:%i\') AS data_aprovacao');
$sql->adCampo('(SELECT count(custo_observacao_id) FROM custo_observacao WHERE custo_observacao_recurso_tarefa=recurso_tarefa_id) AS qnt_obs');
$sql->adOnde('recurso_tarefa_tarefa = '.(int)$tarefa_id);
$sql->adOrdem('recurso_tarefa_tarefa, recurso_tarefa_ordem');
$recursos = $sql->Lista();
$sql->limpar();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="tbl1">
	<th>'.dica('Nome','Nome do recurso alocado.').'Nome'.dicaF().'</th>
	<th>'.dica('In�cio','In�cio da aloca��o do recurso.').'In�cio'.dicaF().'</th>
	<th>'.dica('T�rmino','T�rmino da aloca��o do recurso.').'T�rmino'.dicaF().'</th>
	<th>'.dica('Horas','Total de horas �teis na aloca��o do recurso.').'Horas'.dicaF().'</th>
	<th>'.dica('Quantidade','Quantidade do recurso alocado.').'Quant.'.dicaF().'</th>
	<th>'.dica('Percentual','Percentual do recurso alocado.').'%'.dicaF().'</th>
	<th>'.dica('Valor hora', 'O valor da hora de aloca��o do recurso.').'Valor/hora'.dicaF().'</th>
	<th>'.dica('Valor Unit�rio', 'O valor unit�rio do recurso.').'Valor/unit.'.dicaF().'</th>
	<th>'.dica('Custo Extra','Custo extra do recurso alocado.').'Custo Extra'.dicaF().'</th>
	<th>'.dica('Aprovado','Se o recurso alocado foi aprovado.').'Aprov.'.dicaF().'</th>';
	if (!$dialogo) echo '<th>'.dica('Observa��o','Observa��o sobre a aloca��o do recurso alocado.').'Obs.'.dicaF().'</th>';
	echo '</tr>';
foreach ($recursos as $linha1) {
	if ($linha1['recurso_tarefa_recurso'] && !isset($recurso[$linha1['recurso_tarefa_recurso']])) $recurso[$linha1['recurso_tarefa_recurso']]=link_recurso($linha1['recurso_tarefa_recurso']);
	if ($linha1['recurso_tarefa_aprovou'] && !isset($usuario[$linha1['recurso_tarefa_aprovou']])) $usuario[$linha1['recurso_tarefa_aprovou']]=link_usuario($linha1['recurso_tarefa_aprovou']);
	echo '<tr>';
	echo '<td>'.($linha1['recurso_tarefa_recurso']  ? $recurso[$linha1['recurso_tarefa_recurso']] : '&nbsp;').'</td>';
	echo '<td align=center style="white-space: nowrap" width=110>'.($linha1['recurso_tipo']!=5 ? $linha1['inicio'] : '').'</td>';
	echo '<td align=center style="white-space: nowrap" width=110>'.($linha1['recurso_tipo']!=5 ? $linha1['fim'] : '').'</td>';
	echo '<td align=right style="white-space: nowrap" width=50>'.($linha1['recurso_tipo']!=5 ? number_format($linha1['recurso_tarefa_duracao'], 2, ',', '.') : '').'</td>';
  echo '<td align=right>'.number_format($linha1['recurso_tarefa_quantidade'], 2, ',', '.').'</td>';
  echo '<td align=right style="white-space: nowrap" width=30>'.($linha1['recurso_tipo'] < 4 ? $linha1['recurso_tarefa_percentual'] : '').'</td>';
	echo '<td align=right style="white-space: nowrap" width=70>'.($linha1['recurso_tipo'] < 4 ? number_format($linha1['recurso_tarefa_valor_hora'], 2, ',', '.') : '').'</td>';
	echo '<td align=right style="white-space: nowrap" width=70>'.($linha1['recurso_tipo']==4 ? number_format($linha1['recurso_tarefa_custo'], 2, ',', '.') : '').'</td>';
	if ($linha1['recurso_tipo'] < 4){
		//gastos extras
		$sql->adTabela('recurso_tarefa_custo');
		$sql->adCampo('SUM((recurso_tarefa_custo_quantidade*recurso_tarefa_custo_valor*recurso_tarefa_custo_cotacao)*((100+recurso_tarefa_custo_bdi)/100))');
		$sql->adOnde('recurso_tarefa_custo_recurso_tarefa='.(int)$linha1['recurso_tarefa_id']);
		$gasto_extra=$sql->Resultado();
		$sql->limpar();
		echo '<td align=right>'.($gasto_extra > 0 ? '<a href="javascript:void(0);" onclick="popGastoExtra('.$linha1['recurso_tarefa_id'].')">' : '').$moedas[$projeto_moeda].' '.number_format($gasto_extra/$divisor_cotacao, 2, ',', '.').($gasto_extra > 0 ? '</a>' : '').'</td>';
		}
	else echo '<td align=right></td>';
	echo '<td align=center width=30>'.($linha1['recurso_tarefa_aprovado'] > 0  ? 'Sim' : 'N�o').'</td>';
	
	if (!$dialogo) echo '<td width=16 align=center style="white-space: nowrap">'.($linha1['qnt_obs'] ? '<a href="javascript:void(0);" onclick="ver_observacao('.$linha1['recurso_tarefa_id'].')">'.$icone_ver_obs.'</a>' : '').'</td>';
	
	echo '</tr>';
	}
echo '</table>';
?>
<script type="text/JavaScript">
	
function ver_observacao(recurso_tarefa_id) {
	parent.gpwebApp.popUp('Observa��es', 1000, 600, 'm=projetos&a=aprovar_custos_observacao_pro&dialogo=1&editar=0&recurso_tarefa_id='+recurso_tarefa_id, null, window);
	}			

function popGastoExtra(recurso_tarefa_id) {
	parent.gpwebApp.popUp('Gastos Extras', 800, 600, 'm=projetos&a=aprovar_recurso_custo_extra_pro&dialogo=1&recurso_tarefa_id='+recurso_tarefa_id, null, window);
	}

</script>