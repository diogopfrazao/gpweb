<?php 
/*
Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa GP-Web
O GP-Web ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $config;


$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tipo=getParam($_REQUEST, 'tipo', '');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');
$nd=array(0 => '');
$nd+= getSisValorND();
$unidade=getSisValor('TipoUnidade');
echo '<link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css">';
echo '<form name="frm" id="frm" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input type="hidden" name="a" value="gasto" />';
if ($ir) echo '<input type="hidden" name="acao" value="'.$ir.'" />';

echo '<center><a href=\'javascript:self.print()\'><h1>'.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').'  - '.nome_tarefa($tarefa_id).'<h1></a></center>';
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td valign="top" align="center">';
$q = new BDConsulta;
if ($tipo=='estimado'){
	$q->adTabela('tarefa_custos', 't');
	$q->adCampo('t.*,((tarefa_custos_quantidade*tarefa_custos_custo*tarefa_custos_cotacao)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$q->adOnde('t.tarefa_custos_tarefa ='.$tarefa_id);
	if ($Aplic->profissional && $config['aprova_custo']) $q->adOnde('tarefa_custos_aprovado = 1');
	$q->adOrdem('tarefa_custos_ordem');	
	}
else {
	$q->adTabela('tarefa_gastos', 't');
	$q->adCampo('t.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	$q->adOnde('t.tarefa_gastos_tarefa ='.$tarefa_id);
	if ($Aplic->profissional && $config['aprova_gasto']) $q->adOnde('tarefa_gastos_aprovado = 1');
	$q->adOrdem('tarefa_gastos_ordem');
	}
$linhas= $q->Lista();
$qnt=0;
echo '<table width="100%" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
echo '<tr><td><b><center>Nome</th><td><b><center>Descri??o</center></b></td><td><b><center>Unidade</center></b></td><td width="40"><b><center>Qnt.</center></b></td><td><b><center>Valor</center></b></td><td><b><center>ND</center></b></td><td width="100"><b><center>Total</center></b></td><td><b><center>Respons?vel</center></b></td></tr>';
$total=0;
$custo=array();
foreach ($linhas as $linha) {
	if ($tipo=='estimado'){
		$data = new CData($linha['tarefa_custos_data_inicio']);
		echo '<tr align="center"><td align="left">'.++$qnt.' - '.$linha['tarefa_custos_nome'].'</td><td align="left">'.$linha['tarefa_custos_descricao'].'</td><td style="white-space: nowrap">'.$unidade[$linha['tarefa_custos_tipo']].'</td><td style="white-space: nowrap">'.$linha['tarefa_custos_quantidade'].'</td><td align="right" style="white-space: nowrap">'.number_format($linha['tarefa_custos_custo'], 2, ',', '.').'</td><td style="white-space: nowrap">'.dica('Natureza da Despesa', $nd[$linha['tarefa_custos_nd']]).($linha['tarefa_custos_categoria_economica'] && $linha['tarefa_custos_grupo_despesa'] && $linha['tarefa_custos_modalidade_aplicacao'] ? $linha['tarefa_custos_categoria_economica'].'.'.$linha['tarefa_custos_grupo_despesa'].'.'.$linha['tarefa_custos_modalidade_aplicacao'].'.' : '').$linha['tarefa_custos_nd'].dicaF().'</td><td align="right" style="white-space: nowrap">'.number_format($linha['valor'], 2, ',', '.').'</td><td align="left" style="white-space: nowrap">'.nome_usuario($linha['tarefa_custos_usuario']).'</td><tr>';
		$custo[$linha['tarefa_custos_nd']] += (float)$linha['valor'];	
		}
	else{
		$data = new CData($linha['tarefa_gastos_data_inicio']);
		echo '<tr align="center"><td align="left">'.++$qnt.' - '.$linha['tarefa_gastos_nome'].'</td><td align="left">'.$linha['tarefa_gastos_descricao'].'</td><td style="white-space: nowrap">'.$unidade[$linha['tarefa_gastos_tipo']].'</td><td style="white-space: nowrap">'.$linha['tarefa_gastos_quantidade'].'</td><td align="right" style="white-space: nowrap">'.number_format($linha['tarefa_gastos_custo'], 2, ',', '.').'</td><td style="white-space: nowrap">'.dica('Natureza da Despesa', (isset($nd[$linha['tarefa_gastos_nd']]) ? $nd[$linha['tarefa_gastos_nd']] : 'Sem natureza de despesa')).($linha['tarefa_gastos_categoria_economica'] && $linha['tarefa_gastos_grupo_despesa'] && $linha['tarefa_gastos_modalidade_aplicacao'] ? $linha['tarefa_gastos_categoria_economica'].'.'.$linha['tarefa_gastos_grupo_despesa'].'.'.$linha['tarefa_gastos_modalidade_aplicacao'].'.' : '').$linha['tarefa_gastos_nd'].dicaF().'</td><td align="right" style="white-space: nowrap">'.number_format($linha['valor'], 2, ',', '.').'</td><td align="left" style="white-space: nowrap">'.nome_usuario($linha['tarefa_gastos_usuario']).'</td><tr>';
		$custo[$linha['tarefa_gastos_nd']] += (float)$linha['valor'];	
		} 
	$total+=$linha['valor'];
	}
if ($qnt) {
	if ($total) {
		echo '<tr><td colspan="6" class="std" align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.(isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="2">&nbsp;</td></tr>';	
		}	
	}
else echo '<tr><td colspan="8" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
echo '</table></td></tr>';
echo '</table></form>';
?>