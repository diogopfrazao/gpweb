<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$ponto_relatorio_tipo='data_custo';

$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tarefas_subordinadas=getParam($_REQUEST, 'tarefas_subordinadas', 0);

$financeiro=getParam($_REQUEST, 'financeiro', '');

if (!$tarefas_subordinadas){
	$vetor=array();
	tarefas_subordinadas($tarefa_id, $vetor);
	$tarefas_subordinadas=implode(',',$vetor);
	}


$unidade=getSisValor('TipoUnidade');
$unidade= getSisValor('TipoUnidade');

echo '<table cellpadding=0 cellspacing=1 width="100%">';


echo '<tr><td><h2>Per�odos Trabalhados'.($financeiro ? ' ('.ucfirst($financeiro).')' : '').'<br></h2></td></tr>';

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if ($financeiro){
	$sql->adTabela('recurso_ponto_gasto');
	$sql->esqUnir('recurso_ponto', 'recurso_ponto', 'recurso_ponto_gasto_ponto=recurso_ponto_id');
	$sql->esqUnir('eventos', 'eventos', 'eventos.evento_id = recurso_ponto.recurso_ponto_evento');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_ponto.recurso_ponto_tarefa');
	$sql->adCampo('recurso_ponto_id');
	$sql->adOnde('evento_tarefa IN ('.$tarefas_subordinadas.') OR tarefa_id IN ('.$tarefas_subordinadas.')');
	if ($financeiro=='empenhado') $sql->adOnde('recurso_ponto_gasto_empenhado > 0');
	elseif ($financeiro=='liquidado') $sql->adOnde('recurso_ponto_gasto_liquidado > 0');
	elseif ($financeiro=='pago') $sql->adOnde('recurso_ponto_gasto_pago > 0');
	$tem_gasto=$sql->carregarColuna();
	$sql->limpar();
	$tem_gasto=implode(',',$tem_gasto);
	}


$sql->adTabela('recurso_ponto');
$sql->esqUnir('eventos', 'eventos', 'eventos.evento_id = recurso_ponto.recurso_ponto_evento');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_ponto.recurso_ponto_tarefa');
$sql->esqUnir('recursos', 'recursos', 'recurso_id = recurso_ponto_recurso');
$sql->adCampo('recurso_nome, recurso_ponto_recurso, recurso_ponto_id, tarefa_nome, recurso_ponto_evento, recurso_ponto_tarefa, evento_titulo, formatar_data(recurso_ponto_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(recurso_ponto_fim, \'%d/%m/%Y %H:%i\') AS fim, recurso_ponto_duracao, recurso_ponto_valor_hora, recurso_ponto_quantidade, recurso_ponto_percentual');

if ($financeiro=='empenhado') $sql->adCampo('(recurso_ponto_empenhado*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total, recurso_ponto_empenhado AS duracao');
elseif ($financeiro=='liquidado') $sql->adCampo('(recurso_ponto_liquidado*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total, recurso_ponto_liquidado AS duracao');
elseif ($financeiro=='pago') $sql->adCampo('(recurso_ponto_pago*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total, recurso_ponto_pago AS duracao');
else $sql->adCampo('(recurso_ponto_duracao*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total, recurso_ponto_duracao AS duracao');
$sql->adOnde('evento_tarefa IN ('.$tarefas_subordinadas.') OR tarefa_id IN ('.$tarefas_subordinadas.')');
$sql->adOnde('recurso_ponto_fim IS NOT NULL');
if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
if ($financeiro=='empenhado') $sql->adOnde('recurso_ponto_empenhado > 0'.($tem_gasto ? ' OR recurso_ponto_id IN ('.$tem_gasto.')' : ''));
elseif ($financeiro=='liquidado') $sql->adOnde('recurso_ponto_liquidado > 0'.($tem_gasto ? ' OR recurso_ponto_id IN ('.$tem_gasto.')' : ''));
elseif ($financeiro=='pago') $sql->adOnde('recurso_ponto_pago > 0'.($tem_gasto ? ' OR recurso_ponto_id IN ('.$tem_gasto.')' : ''));
$sql->adOrdem('recurso_nome, recurso_ponto_inicio');
$existe=$sql->lista();
$sql->limpar();

echo '<tr><td align=center><table cellspacing=0 cellpadding=0 class="tbl1" width="100%">';
echo '<tr><th>Nome</th><th width="110">In�cio</th><th width="110">Fim</th><th width="50">Dura��o</th><th width="80">Valor '.$config['simbolo_moeda'].'</th></tr>';
$soma=0;
$soma2=0;
$soma3=0;
$gasto2=array();
$total2=0;

$hora_geral=0;
$valor_hora_geral=0;


$nd=getSisValorND();
$recursoatual='';
foreach($existe as $linha) {
	if ($recursoatual!=$linha['recurso_ponto_recurso']) {
		if ($recursoatual) echo '<tr><td colspan=3 align=right><b>Total</b></td><td align=right>'.number_format($soma, 1, ',', '.').'</td><td align=right>'.number_format($soma2, 2, ',', '.').'</td></tr>';

		$hora_geral+=$soma;
		$valor_hora_geral+=$soma2;

		$soma=0;
		$soma2=0;

		$recursoatual=$linha['recurso_ponto_recurso'];
		echo '<tr><td colspan=20 height=30 valign=bottom><b>'.($dialogo ? $linha['recurso_nome'] : link_recurso($linha['recurso_ponto_recurso'])).'</b></td></tr>';
		}
	echo '<tr><td>'.($linha['recurso_ponto_tarefa'] ? ($dialogo ? $linha['tarefa_nome'] : link_tarefa($linha['recurso_ponto_tarefa'])) : ($dialogo ? $linha['evento_titulo'] : link_evento($linha['recurso_ponto_evento']))).'</td><td>'.$linha['inicio'].'</td><td>'.$linha['fim'].'</td><td align=right>'.number_format($linha['duracao'], 1, ',', '.').'</td><td align=right>'.number_format($linha['total'], 2, ',', '.').'</td><tr>';
	$soma+=$linha['recurso_ponto_duracao'];
	$soma2+=$linha['recurso_ponto_duracao']*$linha['recurso_ponto_valor_hora']*$linha['recurso_ponto_quantidade']*($linha['recurso_ponto_percentual']/100);
	if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_resumido' || $ponto_relatorio_tipo=='data_custo_arquivo' || $ponto_relatorio_tipo=='data_custo_resumido_arquivo'){
		
		$sql->adTabela('recurso_ponto_gasto');
		$sql->adCampo('recurso_ponto_gasto.*');
		if ($financeiro=='empenhado') $sql->adCampo('((recurso_ponto_gasto_empenhado*recurso_ponto_gasto_gasto*recurso_ponto_gasto_cotacao)*((100+recurso_ponto_gasto_bdi)/100)) AS total, recurso_ponto_gasto_empenhado AS quantidade');
		elseif ($financeiro=='liquidado') $sql->adCampo('((recurso_ponto_gasto_liquidado*recurso_ponto_gasto_gasto*recurso_ponto_gasto_cotacao)*((100+recurso_ponto_gasto_bdi)/100)) AS total, recurso_ponto_gasto_liquidado AS quantidade');
		elseif ($financeiro=='pago') $sql->adCampo('((recurso_ponto_gasto_pago*recurso_ponto_gasto_gasto*recurso_ponto_gasto_cotacao)*((100+recurso_ponto_gasto_bdi)/100)) AS total, recurso_ponto_gasto_pago AS quantidade');
		else $sql->adCampo('((recurso_ponto_gasto_quantidade*recurso_ponto_gasto_gasto*recurso_ponto_gasto_cotacao)*((100+recurso_ponto_gasto_bdi)/100)) AS total, recurso_ponto_gasto_quantidade AS quantidade');
		$sql->adOnde('recurso_ponto_gasto_ponto = '.(int)$linha['recurso_ponto_id']);
		if ($financeiro=='empenhado') $sql->adOnde('recurso_ponto_gasto_empenhado > 0');
		elseif ($financeiro=='liquidado') $sql->adOnde('recurso_ponto_gasto_liquidado > 0');
		elseif ($financeiro=='pago') $sql->adOnde('recurso_ponto_gasto_pago > 0');
		$sql->adOrdem('recurso_ponto_gasto_ordem');
		$gastos=$sql->Lista();
		$sql->limpar();
		
		if (count($gastos)) {
			if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_arquivo') echo '<tr><td colspan=20><table cellspacing="1" cellpadding="2" border=0  align=left width="100%"><tr>
			<th>Nome</th>
			<th>Descri��o</th>
			<th>Unidade</th>
			<th>Qnt</th>
			<th>Valor</th>'.
			($config['bdi'] ? '<th>'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
			'<th>ND</th>
			<th>Total</th>'.
			(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
			(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
			(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
			'<th>Data</th>
			</tr>';
			else echo '<tr><td colspan=20><table cellspacing="1" cellpadding="2" border=0  align=left width="100%"><tr><th colspan=6>ND</th><th colspan=5>Valor</th></tr>';
			$qnt=0;
			$total=0;
			$gasto=array();
			foreach ($gastos as $item) {

				if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_arquivo'){
					echo '<tr align="center">';
					echo '<td align="left" width="190">'.++$qnt.' - '.$item['recurso_ponto_gasto_nome'].'</td>';
					echo '<td align="left">'.($item['recurso_ponto_gasto_descricao'] ? $item['recurso_ponto_gasto_descricao'] : '&nbsp;').'</td>';
					echo '<td width="30">'.$unidade[$item['recurso_ponto_gasto_tipo']].'</td><td width="50" align="right">'.number_format($item['quantidade'], 2, ',', '.').'</td>';
					echo '<td align="right" width="70" align="right">'.number_format($item['recurso_ponto_gasto_gasto'], 2, ',', '.').'</td>';
					
					if ($config['bdi']) echo '<td align="right">'.number_format($item['recurso_ponto_gasto_bdi'], 2, ',', '.').'</td>';
					
					echo '<td width="10" style="white-space: nowrap">'.($item['recurso_ponto_gasto_categoria_economica'] && $item['recurso_ponto_gasto_grupo_despesa'] && $item['recurso_ponto_gasto_modalidade_aplicacao'] ? $item['recurso_ponto_gasto_categoria_economica'].'.'.$item['recurso_ponto_gasto_grupo_despesa'].'.'.$item['recurso_ponto_gasto_modalidade_aplicacao'].'.' : '&nbsp;').$item['recurso_ponto_gasto_nd'].'</td>';
					echo '<td align="right" width="70">'.number_format($item['total'], 2, ',', '.').'</td>';
					if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($item['recurso_ponto_gasto_codigo'] ? $item['recurso_ponto_gasto_codigo'] : '&nbsp;').'</td>';
					if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($item['recurso_ponto_gasto_fonte'] ? $item['recurso_ponto_gasto_fonte'] : '&nbsp;').'</td>';
					if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($item['recurso_ponto_gasto_regiao'] ? $item['recurso_ponto_gasto_regiao'] : '&nbsp;').'</td>'; 
					
					echo '<td width="10" style="white-space: nowrap">'.($item['recurso_ponto_gasto_data_limite']? retorna_data($item['recurso_ponto_gasto_data_limite'],false) : '&nbsp;').'</td>';
					echo '</tr>';
					}

				if(isset($gasto[$item['recurso_ponto_gasto_nd']])) $gasto[$item['recurso_ponto_gasto_nd']] += (float)$item['total'];
				else $gasto[$item['recurso_ponto_gasto_nd']] = (float)$item['total'];

				if(isset($gasto2[$item['recurso_ponto_gasto_nd']])) $gasto2[$item['recurso_ponto_gasto_nd']] += (float)$item['total'];
				else $gasto2[$item['recurso_ponto_gasto_nd']] = (float)$item['total'];

				$total+=(float)$item['total'];
				$total2+=(float)$item['total'];
				}
			if ($total) {
				echo '<tr><td colspan='.($config['bdi'] ? 7 : 6).' class="std" align="right">';
				foreach ($gasto as $indice_nd => $somatorio) if ($somatorio > 0) echo (isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND').'<br>';
				echo '<b>Total</td><td align="right" width="90">';
				foreach ($gasto as $indice_nd => $somatorio) if ($somatorio > 0) echo number_format($somatorio, 2, ',', '.').'<br>';
				echo '<b>'.number_format($total, 2, ',', '.').'</b></td>'.($ponto_relatorio_tipo=='data_custo' ? '<td colspan=20>&nbsp;</td>' : '').'</tr>';
				}
			echo '</table></td></tr>';
			}
		}

	if ($ponto_relatorio_tipo=='data_custo_arquivo' || $ponto_relatorio_tipo=='data_custo_resumido_arquivo'){

		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);

		//arquivo anexo
		$sql->adTabela('recurso_ponto_arquivo');
		$sql->adCampo('recurso_ponto_arquivo_id, recurso_ponto_arquivo_data, recurso_ponto_arquivo_ordem, recurso_ponto_arquivo_nome');
		$sql->adOnde('recurso_ponto_arquivo_ponto='.(int)$linha['recurso_ponto_id']);
		$sql->adOrdem('recurso_ponto_arquivo_ordem ASC');
		$arquivos=$sql->Lista();
		$sql->limpar();
		$saida='';
		if (count($arquivos)) echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><th>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</th></tr><tr><td>';
		foreach ($arquivos as $linha) {
			echo '<a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=calendario&a=recurso_ponto_download&sem_cabecalho=1&recurso_ponto_arquivo_id='.$linha['recurso_ponto_arquivo_id'].'\');">'.$linha['recurso_ponto_arquivo_nome'].'</a><br>';
			}
		if (count($arquivos)) echo '</td></tr></table></td></tr>';
		}

	}

if ($recursoatual) echo '<tr><td colspan=3 align=right><b>Total</b></td><td align=right>'.number_format($soma, 1, ',', '.').'</td><td align=right>'.number_format($soma2, 2, ',', '.').'</td></tr>';
if (!count($existe)) '<tr><td colspan=4 align=right>N�o foi encontrado nenhum per�odo marcado como trabalhado</td></tr>';
echo '</table></td></tr>';
echo '</table>';

$hora_geral+=$soma;
$valor_hora_geral+=$soma2;

if ($total2 || count($existe)) {
		echo '<table cellspacing=4 cellpadding=0 border=0>';
		echo '<tr><td align=right><b>Sum�rio</b></td>&nbsp;<td></tt><tr>';
		if ($total2) {
			echo '<tr><td align=right  style="white-space: nowrap">';
			foreach ($gasto2 as $indice_nd => $somatorio) if ($somatorio > 0) echo (isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND').'<br>';
			echo 'Soma parcial</td><td align="right" style="white-space: nowrap">';
			foreach ($gasto2 as $indice_nd => $somatorio) if ($somatorio > 0) echo $config['simbolo_moeda'].' '.number_format($somatorio, 2, ',', '.').'<br>';
			echo $config['simbolo_moeda'].' '.number_format($total2, 2, ',', '.').'</td></tr>';
			}
		if (count($existe))	{
			echo '<tr><td align=right>Horas</td><td align=right>'.number_format($hora_geral, 1, ',', '.').'</td></tr>';
			echo '<tr><td align=right>Custo das horas</td><td align=right>'.$config['simbolo_moeda'].' '.number_format($valor_hora_geral, 2, ',', '.').'</td></tr>';
			}
		echo '<tr><td align=right><b>Soma Final</b></td><td align=right><b>'.$config['simbolo_moeda'].' '.number_format($valor_hora_geral+$total2, 2, ',', '.').'</b></td></tr>';
		echo '</table>';
		}
echo '</form>';

?>
