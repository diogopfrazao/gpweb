<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/instrumento/instrumento_editar_ajax_pro.php';




$sql = new BDConsulta;

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("mudar_nd_ajax");
	
	
function incluir_custo_ajax(
		$instrumento_id=null, 
		$uuid=null, 
		$instrumento_casa_significativa=null,
		$instrumento_campo=1,
		$instrumento_valor=null,
		$instrumento_avulso_custo_id=null, 
		$instrumento_avulso_custo_nome=null,
		$instrumento_avulso_custo_tipo=null,
		$instrumento_avulso_custo_quantidade=null,
		$instrumento_avulso_custo_custo=null,
		$instrumento_avulso_custo_custo_atual=null,
		$instrumento_avulso_custo_descricao=null,
		$instrumento_avulso_custo_nd=null,
		$instrumento_avulso_custo_categoria_economica=null,
		$instrumento_avulso_custo_grupo_despesa=null,
		$instrumento_avulso_custo_modalidade_aplicacao=null,
		$instrumento_avulso_custo_data_limite=null,
		$instrumento_avulso_custo_codigo=null,
		$instrumento_avulso_custo_fonte=null,
		$instrumento_avulso_custo_regiao=null,
		$instrumento_avulso_custo_bdi=null,
		$instrumento_avulso_custo_moeda=null,
		$instrumento_avulso_custo_data_moeda=null,
		$instrumento_avulso_custo_meses=null, 
		$instrumento_avulso_custo_servico=null,
		$instrumento_avulso_custo_acrescimo=null,
		$instrumento_avulso_custo_percentual=null,
		$instrumento_avulso_custo_pi=null,
		$instrumento_avulso_custo_ptres=null,
		$instrumento_avulso_custo_exercicio=null
		){
			
	global $Aplic, $bd, $config;
	
	$instrumento_avulso_custo_cotacao=($instrumento_avulso_custo_moeda > 1 ? cotacao($instrumento_avulso_custo_moeda, $instrumento_avulso_custo_data_moeda) : 1);

	$instrumento_avulso_custo_codigo=previnirXSS(utf8_decode($instrumento_avulso_custo_codigo));
	$instrumento_avulso_custo_fonte=previnirXSS(utf8_decode($instrumento_avulso_custo_fonte));
	$instrumento_avulso_custo_regiao=previnirXSS(utf8_decode($instrumento_avulso_custo_regiao));

	$instrumento_avulso_custo_nome=previnirXSS(utf8_decode($instrumento_avulso_custo_nome));
	$instrumento_avulso_custo_descricao=previnirXSS(utf8_decode($instrumento_avulso_custo_descricao));
	
	$instrumento_avulso_custo_pi=previnirXSS(utf8_decode($instrumento_avulso_custo_pi));
	$instrumento_avulso_custo_ptres=previnirXSS(utf8_decode($instrumento_avulso_custo_ptres));
	
	$sql = new BDConsulta;
	if ($instrumento_avulso_custo_id){
		$sql->adTabela('instrumento_avulso_custo');
		$sql->adAtualizar('instrumento_avulso_custo_nome', $instrumento_avulso_custo_nome);	
		$sql->adAtualizar('instrumento_avulso_custo_tipo', $instrumento_avulso_custo_tipo);
		$sql->adAtualizar('instrumento_avulso_custo_quantidade', float_americano($instrumento_avulso_custo_quantidade));
		$sql->adAtualizar('instrumento_avulso_custo_custo', float_americano($instrumento_avulso_custo_custo));
		$sql->adAtualizar('instrumento_avulso_custo_custo_atual', float_americano($instrumento_avulso_custo_custo_atual));
		$sql->adAtualizar('instrumento_avulso_custo_acrescimo', float_americano($instrumento_avulso_custo_acrescimo));
		$sql->adAtualizar('instrumento_avulso_custo_percentual', $instrumento_avulso_custo_percentual);
		$sql->adAtualizar('instrumento_avulso_custo_descricao', $instrumento_avulso_custo_descricao);
		$sql->adAtualizar('instrumento_avulso_custo_nd', $instrumento_avulso_custo_nd);
		$sql->adAtualizar('instrumento_avulso_custo_categoria_economica', $instrumento_avulso_custo_categoria_economica);
		$sql->adAtualizar('instrumento_avulso_custo_grupo_despesa', $instrumento_avulso_custo_grupo_despesa);
		$sql->adAtualizar('instrumento_avulso_custo_modalidade_aplicacao', $instrumento_avulso_custo_modalidade_aplicacao);
		$sql->adAtualizar('instrumento_avulso_custo_data_limite', $instrumento_avulso_custo_data_limite);
		$sql->adAtualizar('instrumento_avulso_custo_usuario', $Aplic->usuario_id);
		$sql->adAtualizar('instrumento_avulso_custo_data', date('Y-m-d H:i:s'));
		$sql->adAtualizar('instrumento_avulso_custo_codigo', $instrumento_avulso_custo_codigo);
		$sql->adAtualizar('instrumento_avulso_custo_fonte', $instrumento_avulso_custo_fonte);
		$sql->adAtualizar('instrumento_avulso_custo_regiao', $instrumento_avulso_custo_regiao);
		$sql->adAtualizar('instrumento_avulso_custo_bdi', float_americano($instrumento_avulso_custo_bdi));
		$sql->adAtualizar('instrumento_avulso_custo_moeda', $instrumento_avulso_custo_moeda);
		$sql->adAtualizar('instrumento_avulso_custo_data_moeda', $instrumento_avulso_custo_data_moeda);
		$sql->adAtualizar('instrumento_avulso_custo_cotacao', $instrumento_avulso_custo_cotacao);
		$sql->adAtualizar('instrumento_avulso_custo_meses', ($instrumento_avulso_custo_meses ? $instrumento_avulso_custo_meses : null));
		$sql->adAtualizar('instrumento_avulso_custo_servico', $instrumento_avulso_custo_servico);
		$sql->adAtualizar('instrumento_avulso_custo_pi', $instrumento_avulso_custo_pi);
		$sql->adAtualizar('instrumento_avulso_custo_ptres', $instrumento_avulso_custo_ptres);
		$sql->adAtualizar('instrumento_avulso_custo_exercicio', ($instrumento_avulso_custo_exercicio ? $instrumento_avulso_custo_exercicio : null));
		
		
		
		
		$sql->adOnde('instrumento_avulso_custo_id ='.(int)$instrumento_avulso_custo_id);
		$sql->exec();
	  $sql->limpar();
	  
	  $sql->adTabela('instrumento_custo');
		$sql->adAtualizar('instrumento_custo_quantidade', float_americano($instrumento_avulso_custo_quantidade));
		$sql->adOnde('instrumento_custo_instrumento = '.(int)$instrumento_id);
		$sql->adOnde('instrumento_custo_avulso = '.(int)$instrumento_avulso_custo_id);
		$sql->exec();
		$sql->limpar();
	  
		}
	else {	
		
		$sql->adTabela('instrumento_avulso_custo');
		$sql->adCampo('MAX(instrumento_avulso_custo_ordem)');
		$sql->adOnde('instrumento_avulso_custo_instrumento ='.(int)$instrumento_id);	
	  $qnt = (int)$sql->Resultado();
	  $sql->limpar();
		
		
		
		$sql->adTabela('instrumento_avulso_custo');
		if ($instrumento_id) $sql->adInserir('instrumento_avulso_custo_instrumento', $instrumento_id);
		else $sql->adInserir('instrumento_avulso_custo_uuid', $uuid);
		
		$sql->adInserir('instrumento_avulso_custo_nome', $instrumento_avulso_custo_nome);	
		$sql->adInserir('instrumento_avulso_custo_tipo', $instrumento_avulso_custo_tipo);
		$sql->adInserir('instrumento_avulso_custo_quantidade', float_americano($instrumento_avulso_custo_quantidade));
		$sql->adInserir('instrumento_avulso_custo_custo', float_americano($instrumento_avulso_custo_custo));
		$sql->adInserir('instrumento_avulso_custo_custo_atual', float_americano($instrumento_avulso_custo_custo_atual));
		$sql->adInserir('instrumento_avulso_custo_acrescimo', float_americano($instrumento_avulso_custo_acrescimo));
		$sql->adInserir('instrumento_avulso_custo_percentual', $instrumento_avulso_custo_percentual);
		$sql->adInserir('instrumento_avulso_custo_descricao', $instrumento_avulso_custo_descricao);
		$sql->adInserir('instrumento_avulso_custo_nd', $instrumento_avulso_custo_nd);
		$sql->adInserir('instrumento_avulso_custo_categoria_economica', $instrumento_avulso_custo_categoria_economica);
		$sql->adInserir('instrumento_avulso_custo_grupo_despesa', $instrumento_avulso_custo_grupo_despesa);
		$sql->adInserir('instrumento_avulso_custo_modalidade_aplicacao', $instrumento_avulso_custo_modalidade_aplicacao);
		$sql->adInserir('instrumento_avulso_custo_data_limite', $instrumento_avulso_custo_data_limite);
		$sql->adInserir('instrumento_avulso_custo_codigo', $instrumento_avulso_custo_codigo);
		$sql->adInserir('instrumento_avulso_custo_fonte', $instrumento_avulso_custo_fonte);
		$sql->adInserir('instrumento_avulso_custo_regiao', $instrumento_avulso_custo_regiao);
		$sql->adInserir('instrumento_avulso_custo_bdi', float_americano($instrumento_avulso_custo_bdi));
		$sql->adInserir('instrumento_avulso_custo_moeda', $instrumento_avulso_custo_moeda);
		$sql->adInserir('instrumento_avulso_custo_data_moeda', $instrumento_avulso_custo_data_moeda);
		$sql->adInserir('instrumento_avulso_custo_cotacao', $instrumento_avulso_custo_cotacao);
		$sql->adInserir('instrumento_avulso_custo_usuario', $Aplic->usuario_id);
		$sql->adInserir('instrumento_avulso_custo_data', date('Y-m-d H:i:s'));
		$sql->adInserir('instrumento_avulso_custo_ordem', ++$qnt);
		$sql->adInserir('instrumento_avulso_custo_meses', ($instrumento_avulso_custo_meses ? $instrumento_avulso_custo_meses : null));
		$sql->adInserir('instrumento_avulso_custo_servico', $instrumento_avulso_custo_servico);
		$sql->adInserir('instrumento_avulso_custo_pi', $instrumento_avulso_custo_pi);
		$sql->adInserir('instrumento_avulso_custo_ptres', $instrumento_avulso_custo_ptres);
		$sql->adInserir('instrumento_avulso_custo_exercicio', ($instrumento_avulso_custo_exercicio ? $instrumento_avulso_custo_exercicio : null));
		
		$sql->exec();
		$instrumento_avulso_custo_id=$bd->Insert_ID('instrumento_avulso_custo','instrumento_avulso_custo_id');
		$sql->limpar();
		
		
		
		
		
		$sql->adTabela('instrumento_custo');
		$sql->adCampo('MAX(instrumento_custo_ordem)');
		$sql->adOnde('instrumento_custo_avulso IS NOT NULL');	
		if ($instrumento_id) $sql->adOnde('instrumento_custo_instrumento ='.(int)$instrumento_id);
		else $sql->adOnde('instrumento_custo_uuid =\''.$uuid.'\'');
	  $qnt = (int)$sql->Resultado();
	  $sql->limpar();
	
		$sql->adTabela('instrumento_custo');
		$sql->adInserir('instrumento_custo_quantidade', float_americano($instrumento_avulso_custo_quantidade));
		if ($instrumento_id) $sql->adInserir('instrumento_custo_instrumento', (int)$instrumento_id);
		else $sql->adInserir('instrumento_custo_uuid', $uuid);
		$sql->adInserir('instrumento_custo_avulso', (int)$instrumento_avulso_custo_id);
		$sql->adInserir('instrumento_custo_ordem', ++$qnt);
		$sql->exec();
		$sql->limpar();
		}
	$saida=atualizar_custos($instrumento_id, $uuid, $instrumento_casa_significativa, $instrumento_campo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custo","innerHTML", utf8_encode($saida['saida']));
	$objResposta->assign("total_acrescimo","innerHTML", number_format($instrumento_valor+$saida['acrescimo'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.'));
	
	$objResposta->assign("instrumento_avulso_custo_servico","value", $instrumento_valor+$saida['acrescimo']);
	
	return $objResposta;
	}
$xajax->registerFunction("incluir_custo_ajax");

function excluir_custo_ajax($instrumento_avulso_custo_id=null, $instrumento_id=null, $uuid=null, $instrumento_casa_significativa=null, $instrumento_campo=1, $instrumento_valor=null){
	$sql = new BDConsulta;
	$sql->setExcluir('instrumento_avulso_custo');
	$sql->adOnde('instrumento_avulso_custo_id='.(int)$instrumento_avulso_custo_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->setExcluir('instrumento_custo');
	$sql->adOnde('instrumento_custo_avulso = '.(int)$instrumento_avulso_custo_id);
	if ($instrumento_id) $sql->adOnde('instrumento_custo_instrumento = '.(int)$instrumento_id);
	else $sql->adOnde('instrumento_custo_uuid = \''.$uuid.'\'');
	$sql->exec();
	$sql->limpar();
	
	
	$saida=atualizar_custos($instrumento_id, $uuid, $instrumento_casa_significativa, $instrumento_campo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custo","innerHTML", utf8_encode($saida['saida']));
	$objResposta->assign("total_acrescimo","innerHTML", number_format($instrumento_valor+$saida['acrescimo'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.'));
	return $objResposta;
	}

$xajax->registerFunction("excluir_custo_ajax");	

$unidade=getSisValor('TipoUnidade');


function atualizar_custos($instrumento_id=null, $uuid=null, $instrumento_casa_significativa=null, $instrumento_campo=1){
	global $config, $unidade, $Aplic, $moedas;

	$sql = new BDConsulta;
	
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo.*');
	$sql->adOnde('instrumento_campo_id ='.(int)$instrumento_campo);
	$exibir=$sql->linha();
	$sql->limpar();	
	
	
	$sql->adTabela('instrumento_avulso_custo');
	$sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
	$sql->adCampo('instrumento_avulso_custo.*, instrumento_custo_quantidade, instrumento_custo_aprovado');
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');	
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((instrumento_avulso_custo_acrescimo)/100)) END AS acrescimo');	
	
	if ($instrumento_id) $sql->adOnde('instrumento_custo_instrumento ='.(int)$instrumento_id);
	else $sql->adOnde('instrumento_custo_uuid =\''.$uuid.'\'');
	$sql->adOrdem('instrumento_custo_ordem');
	$linhas=$sql->Lista();
	$qnt=0;
	//$total_geral=0;
	$total_acrescimo=0;
	
	$saida='';
	if (is_array($linhas) && count($linhas)) {
		$saida.= '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
		$saida.= '<tr>';
		$saida.= '<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>';
		$saida.= '<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_tipo']) $saida.= '<th width=50>'.$exibir['instrumento_avulso_custo_tipo_leg'].'</th>';
		$saida.= '<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>';
		$saida.= '<th>'.dica('Quantidade de Meses', 'A quantidade de meses, para o caso de serviço.').'Qnt. Meses'.dicaF().'</th>';
		$saida.= '<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>';
		$saida.= '<th>'.dica('Valor Unitário Atualizado', 'O valor de uma unidade do item atualizado.').'Unit. Atual'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_bdi']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_bdi_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_nd']) $saida.= '<th width=50>'.$exibir['instrumento_avulso_custo_nd_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_acrescimo']) $saida.= '<th width=50>'.($exibir['instrumento_avulso_custo_percentual'] ? $exibir['instrumento_avulso_custo_acrescimo_leg2'] : $exibir['instrumento_avulso_custo_acrescimo_leg']).'</th>';
		
		if ($exibir['instrumento_avulso_custo_acrescimo']) $saida.= '<th>'.dica('Valor Total com Acréscimo', 'O valor total é o preço unitário multiplicado pela quantidade e pelo acréscimo.').'Total com Acréscimo'.dicaF().'</th>';
		else $saida.= '<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>';
		
		if ($exibir['instrumento_avulso_custo_codigo']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_codigo_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_fonte']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_fonte_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_regiao']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_regiao_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_usuario']) $saida.= '<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_data_limite']) $saida.= '<th width=50>'.$exibir['instrumento_avulso_custo_data_limite_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_pi']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_pi_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_ptres']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_ptres_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_exercicio']) $saida.= '<th>'.$exibir['instrumento_avulso_custo_exercicio_leg'].'</th>';
		$saida.= '<th></th></tr>';
		
		$total=array();
		$custo=array();
		
		foreach ($linhas as $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td align="left">'.++$qnt.' - '.$linha['instrumento_avulso_custo_nome'].'</td>';
			$saida.= '<td align="left">'.($linha['instrumento_avulso_custo_descricao'] ? $linha['instrumento_avulso_custo_descricao'] : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_tipo']) $saida.= '<td>'.$unidade[$linha['instrumento_avulso_custo_tipo']].'</td>';
			$saida.= '<td style="white-space: nowrap">'.($linha['instrumento_avulso_custo_quantidade'] > 0  ? number_format($linha['instrumento_avulso_custo_quantidade'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '0').'</td>';
			$saida.= '<td>'.($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses'] : ' - ').'</td>';
			$saida.= '<td align="right" style="white-space: nowrap">'.$moedas[$linha['instrumento_avulso_custo_moeda']].' '.number_format($linha['instrumento_avulso_custo_custo'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			$saida.= '<td align="right" style="white-space: nowrap">'.($linha['instrumento_avulso_custo_custo_atual'] > 0 ? $moedas[$linha['instrumento_avulso_custo_moeda']].' '.number_format($linha['instrumento_avulso_custo_custo_atual'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '').'</td>';
			if ($exibir['instrumento_avulso_custo_bdi']) $saida.= '<td align="right" style="white-space: nowrap">'.number_format($linha['instrumento_avulso_custo_bdi'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			$nd=($linha['instrumento_avulso_custo_categoria_economica'] && $linha['instrumento_avulso_custo_grupo_despesa'] && $linha['instrumento_avulso_custo_modalidade_aplicacao'] ? $linha['instrumento_avulso_custo_categoria_economica'].'.'.$linha['instrumento_avulso_custo_grupo_despesa'].'.'.$linha['instrumento_avulso_custo_modalidade_aplicacao'].'.' : '').$linha['instrumento_avulso_custo_nd'];
			if ($exibir['instrumento_avulso_custo_nd']) $saida.= '<td>'.$nd.'</td>';
			if ($exibir['instrumento_avulso_custo_acrescimo']) $saida.= '<td align="right" style="white-space: nowrap">'.number_format($linha['instrumento_avulso_custo_acrescimo'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			$saida.= '<td align="right" style="white-space: nowrap">'.$moedas[$linha['instrumento_avulso_custo_moeda']].' '.number_format(($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']),($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			if ($exibir['instrumento_avulso_custo_codigo']) $saida.= '<td align="center">'.($linha['instrumento_avulso_custo_codigo'] ? $linha['instrumento_avulso_custo_codigo'] : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_fonte']) $saida.= '<td align="center">'.($linha['instrumento_avulso_custo_fonte'] ? $linha['instrumento_avulso_custo_fonte'] : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_regiao']) $saida.= '<td align="center">'.($linha['instrumento_avulso_custo_regiao'] ? $linha['instrumento_avulso_custo_regiao'] : '&nbsp;').'</td>'; 
			if ($exibir['instrumento_avulso_custo_usuario']) $saida.= '<td align="left" style="white-space: nowrap">'.link_usuario($linha['instrumento_avulso_custo_usuario'],'','','esquerda').'</td>';
			if ($exibir['instrumento_avulso_custo_data_limite']) $saida.= '<td>'.($linha['instrumento_avulso_custo_data_limite']? retorna_data($linha['instrumento_avulso_custo_data_limite'],false) : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_pi']) $saida.= '<td align="center">'.$linha['instrumento_avulso_custo_pi'].'</td>';
			if ($exibir['instrumento_avulso_custo_ptres']) $saida.= '<td align="center">'.$linha['instrumento_avulso_custo_ptres'].'</td>';
			if ($exibir['instrumento_avulso_custo_exercicio']) $saida.= '<td align="center">'.$linha['instrumento_avulso_custo_exercicio'].'</td>';
	
			$saida.= '<td width="72" align="left">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			if ($linha['instrumento_custo_aprovado']!=1 || !$config['aprova_custo']) {
				$saida.= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$linha['instrumento_avulso_custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['instrumento_avulso_custo_id'].');">'.imagem('icones/editar.gif').'</a>'.dicaF();
				$saida.= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$linha['instrumento_avulso_custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['instrumento_avulso_custo_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF();
				}
			$saida.= '</td>';
			
			$saida.= '</tr>';
			
			if (isset($custo[$linha['instrumento_avulso_custo_moeda']][$nd])) $custo[$linha['instrumento_avulso_custo_moeda']][$nd] += (float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $custo[$linha['instrumento_avulso_custo_moeda']][$nd]=(float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			
			if (isset($total[$linha['instrumento_avulso_custo_moeda']])) $total[$linha['instrumento_avulso_custo_moeda']]+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $total[$linha['instrumento_avulso_custo_moeda']]=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']); 
			
			$total_acrescimo+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['acrescimo'] : $linha['acrescimo']);
			
			}
		
		$tem_total=false;
		foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
		
		$total_colunas=6;
		if ($exibir['instrumento_avulso_custo_tipo']) $total_colunas++;
		if ($exibir['instrumento_avulso_custo_bdi']) $total_colunas++;
		if ($exibir['instrumento_avulso_custo_nd']) $total_colunas++;
		if ($exibir['instrumento_avulso_custo_acrescimo']) $total_colunas++;
			
		if ($tem_total) {
			foreach ($custo as $tipo_moeda => $linha) {
				$saida.= '<tr><td colspan="'.$total_colunas.'" class="std" align="right">';
				if ($exibir['instrumento_avulso_custo_nd']) foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
				$saida.= '<br><b>Total</td><td align="right" style="white-space: nowrap">';	
				if ($exibir['instrumento_avulso_custo_nd']) foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio,($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.');
				$saida.= '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
				}	
			}
		$saida.= '</table>';
		}
	return array('saida' => $saida, 'acrescimo' => $total_acrescimo);
	}
	
function mudar_posicao_custo($ordem, $instrumento_avulso_custo_id, $direcao, $instrumento_id=null, $uuid=null, $instrumento_casa_significativa=null, $instrumento_campo=1){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $instrumento_avulso_custo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_avulso_custo');
		$sql->adOnde('instrumento_avulso_custo_id != '.(int)$instrumento_avulso_custo_id);
		if ($instrumento_id) $sql->adOnde('instrumento_avulso_custo_instrumento = '.(int)$instrumento_id);
		else $sql->adOnde('instrumento_avulso_custo_uuid = \''.$uuid.'\'');
		$sql->adOrdem('instrumento_avulso_custo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('instrumento_avulso_custo');
			$sql->adAtualizar('instrumento_avulso_custo_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_avulso_custo_id = '.(int)$instrumento_avulso_custo_id);
			$sql->exec();
			$sql->limpar();
			
			
			$sql->adTabela('instrumento_custo');
			$sql->adAtualizar('instrumento_custo_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_custo_avulso = '.(int)$instrumento_avulso_custo_id);
			if ($instrumento_id) $sql->adOnde('instrumento_custo_instrumento = '.(int)$instrumento_id);
			else $sql->adOnde('instrumento_custo_uuid = \''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_avulso_custo');
					$sql->adAtualizar('instrumento_avulso_custo_ordem', $idx);
					$sql->adOnde('instrumento_avulso_custo_id = '.(int)$acao['instrumento_avulso_custo_id']);
					$sql->exec();
					$sql->limpar();
					
					
					$sql->adTabela('instrumento_custo');
					$sql->adAtualizar('instrumento_custo_ordem', $idx);
					$sql->adOnde('instrumento_custo_avulso = '.(int)$acao['instrumento_avulso_custo_id']);		
					if ($instrumento_id) $sql->adOnde('instrumento_custo_instrumento = '.(int)$instrumento_id);
					else $sql->adOnde('instrumento_custo_uuid = \''.$uuid.'\'');
					$sql->exec();
					$sql->limpar();
					
					
					$idx++;
					} 
				else {
					$sql->adTabela('instrumento_avulso_custo');
					$sql->adAtualizar('instrumento_avulso_custo_ordem', $idx + 1);
					$sql->adOnde('instrumento_avulso_custo_id = '.(int)$acao['instrumento_avulso_custo_id']);
					$sql->exec();
					$sql->limpar();
					
					
					$sql->adTabela('instrumento_custo');
					$sql->adAtualizar('instrumento_custo_ordem', $idx + 1);
					$sql->adOnde('instrumento_custo_avulso = '.(int)$acao['instrumento_avulso_custo_id']);
					if ($instrumento_id) $sql->adOnde('instrumento_custo_instrumento = '.(int)$instrumento_id);
					else $sql->adOnde('instrumento_custo_uuid = \''.$uuid.'\'');
					$sql->exec();
					$sql->limpar();
					
					
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_custos($instrumento_id, $uuid, $instrumento_casa_significativa, $instrumento_campo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custo","innerHTML", utf8_encode($saida['saida']));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_custo");	
	
	
	

function editar_custo($instrumento_avulso_custo_id, $instrumento_casa_significativa){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_avulso_custo');
	$sql->adCampo('instrumento_avulso_custo.*');
	$sql->adOnde('instrumento_avulso_custo_id = '.(int)$instrumento_avulso_custo_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$objResposta->assign("instrumento_avulso_custo_id","value", $instrumento_avulso_custo_id);
	$data = new CData($linha['instrumento_avulso_custo_data_limite']);
	$objResposta->assign("instrumento_avulso_custo_data_limite","value", $data->format('%Y-%m-%d'));
	$objResposta->assign("data_texto","value", $data->format('%d/%m/%Y'));	
	$objResposta->assign("instrumento_avulso_custo_quantidade","value", float_brasileiro($linha['instrumento_avulso_custo_quantidade']));	
	$objResposta->assign("instrumento_avulso_custo_bdi","value", number_format($linha['instrumento_avulso_custo_bdi'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.'));	
	$objResposta->assign("instrumento_avulso_custo_custo","value", float_brasileiro($linha['instrumento_avulso_custo_custo']));	
	$objResposta->assign("instrumento_avulso_custo_custo_atual","value", float_brasileiro($linha['instrumento_avulso_custo_custo_atual']));	
	$objResposta->assign("instrumento_avulso_custo_acrescimo","value", float_brasileiro($linha['instrumento_avulso_custo_acrescimo']));	
	$objResposta->assign("instrumento_avulso_custo_categoria_economica","value", $linha['instrumento_avulso_custo_categoria_economica']);
	$objResposta->assign("instrumento_avulso_custo_grupo_despesa","value", $linha['instrumento_avulso_custo_grupo_despesa']);
	$objResposta->assign("instrumento_avulso_custo_modalidade_aplicacao","value", $linha['instrumento_avulso_custo_modalidade_aplicacao']);
	$nd=vetor_nd($linha['instrumento_avulso_custo_nd'], true, null, 3 ,$linha['instrumento_avulso_custo_categoria_economica'], $linha['instrumento_avulso_custo_grupo_despesa'], $linha['instrumento_avulso_custo_modalidade_aplicacao']);
	$saida=selecionaVetor($nd, 'instrumento_avulso_custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', $linha['instrumento_avulso_custo_nd']);
	$objResposta->assign("combo_nd","innerHTML", $saida);
	$objResposta->assign("instrumento_avulso_custo_nome","value", utf8_encode($linha['instrumento_avulso_custo_nome']));
	$objResposta->assign("instrumento_avulso_custo_descricao","value", utf8_encode($linha['instrumento_avulso_custo_descricao']));
	
	$objResposta->assign("instrumento_avulso_custo_codigo","value", utf8_encode($linha['instrumento_avulso_custo_codigo']));
	$objResposta->assign("instrumento_avulso_custo_fonte","value", utf8_encode($linha['instrumento_avulso_custo_fonte']));
	$objResposta->assign("instrumento_avulso_custo_regiao","value", utf8_encode($linha['instrumento_avulso_custo_regiao']));
	
	$objResposta->assign("instrumento_avulso_custo_tipo","value", $linha['instrumento_avulso_custo_tipo']);
	$objResposta->assign("instrumento_avulso_custo_pi","value", utf8_encode($linha['instrumento_avulso_custo_pi']));
	$objResposta->assign("instrumento_avulso_custo_ptres","value", utf8_encode($linha['instrumento_avulso_custo_ptres']));
	$objResposta->assign("instrumento_avulso_custo_exercicio","value", $linha['instrumento_avulso_custo_exercicio']);
	$objResposta->assign("apoio1","value", utf8_encode($linha['instrumento_avulso_custo_descricao']));	
	$objResposta->assign("instrumento_avulso_custo_moeda","value", $linha['instrumento_avulso_custo_moeda']);
	$objResposta->assign("instrumento_avulso_custo_data_moeda","value", $linha['instrumento_avulso_custo_data_moeda']);
	$objResposta->assign("data2_texto","value", retorna_data($linha['instrumento_avulso_custo_data_moeda'], false));
	$objResposta->assign("instrumento_avulso_custo_meses","value", $linha['instrumento_avulso_custo_meses']);
	$objResposta->assign("instrumento_avulso_custo_servico","value", $linha['instrumento_avulso_custo_servico']);
	$objResposta->assign("campo_instrumento_avulso_meses","style.display", ($linha['instrumento_avulso_custo_servico']==1 ? '' : 'none'));

    //pede para o script calcular o valor assim não é preciso fazer em múltiplos lugares
    $objResposta->call('valor');

	//if ($linha['instrumento_avulso_custo_percentual']) $valor=(($linha['instrumento_avulso_custo_custo_atual'] > 0 ? $linha['instrumento_avulso_custo_custo_atual'] : $linha['instrumento_avulso_custo_custo'])*($linha['instrumento_avulso_custo_quantidade']+$linha['instrumento_avulso_custo_acrescimo']))*((100+$linha['instrumento_avulso_custo_bdi'])/100);
	//else $valor=(($linha['instrumento_avulso_custo_custo_atual'] > 0 ? $linha['instrumento_avulso_custo_custo_atual'] : $linha['instrumento_avulso_custo_custo'])*$linha['instrumento_avulso_custo_quantidade'])*((100+$linha['instrumento_avulso_custo_acrescimo'])/100)*((100+$linha['instrumento_avulso_custo_bdi'])/100);
	
	//$objResposta->assign("total","innerHTML", '<b>'.number_format($valor,($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b>');

	return $objResposta;
	}	
$xajax->registerFunction("editar_custo");		
	






































function mudar_posicao_financeiro_ajax($ordem, $instrumento_financeiro_id, $direcao, $instrumento_id=0, $uuid='', $instrumento_campo=null){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$instrumento_financeiro_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_financeiro');
		$sql->adOnde('instrumento_financeiro_id != '.$instrumento_financeiro_id);
		if ($uuid) $sql->adOnde('instrumento_financeiro_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_financeiro_instrumento = '.$instrumento_id);
		$sql->adOrdem('instrumento_financeiro_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('instrumento_financeiro');
			$sql->adAtualizar('instrumento_financeiro_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_financeiro_id = '.$instrumento_financeiro_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_financeiro');
					$sql->adAtualizar('instrumento_financeiro_ordem', $idx);
					$sql->adOnde('instrumento_financeiro_id = '.$acao['instrumento_financeiro_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('instrumento_financeiro');
					$sql->adAtualizar('instrumento_financeiro_ordem', $idx + 1);
					$sql->adOnde('instrumento_financeiro_id = '.$acao['instrumento_financeiro_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_financeiros($instrumento_id, $uuid, $instrumento_campo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_financeiro","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("mudar_posicao_financeiro_ajax");	


function incluir_financeiro_ajax($instrumento_financeiro_id=null, $instrumento_id=null, $uuid=null, $instrumento_casa_significativa=null, $instrumento_financeiro_projeto=null, $instrumento_financeiro_tarefa=null, $instrumento_financeiro_fonte=null, $instrumento_financeiro_regiao=null, $instrumento_financeiro_classificacao=null, $instrumento_financeiro_valor=null, $instrumento_financeiro_ano=null, $instrumento_campo=null){
	$sql = new BDConsulta;
	$instrumento_financeiro_projeto=previnirXSS(utf8_decode($instrumento_financeiro_projeto));
	$instrumento_financeiro_tarefa=previnirXSS(utf8_decode($instrumento_financeiro_tarefa));
	$instrumento_financeiro_fonte=previnirXSS(utf8_decode($instrumento_financeiro_fonte));
	$instrumento_financeiro_regiao=previnirXSS(utf8_decode($instrumento_financeiro_regiao));
	$instrumento_financeiro_classificacao=previnirXSS(utf8_decode($instrumento_financeiro_classificacao));
	$instrumento_financeiro_valor=previnirXSS(utf8_decode($instrumento_financeiro_valor));
	$instrumento_financeiro_ano=previnirXSS(utf8_decode($instrumento_financeiro_ano));
	
	if (!$instrumento_financeiro_id){
		$sql->adTabela('instrumento_financeiro');
		$sql->adCampo('count(instrumento_financeiro_id) AS soma');
		if ($uuid) $sql->adOnde('instrumento_financeiro_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_financeiro_instrumento ='.$instrumento_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('instrumento_financeiro');
		if ($uuid) $sql->adInserir('instrumento_financeiro_uuid', $uuid);
		else $sql->adInserir('instrumento_financeiro_instrumento', $instrumento_id);
		$sql->adInserir('instrumento_financeiro_ordem', $soma_total);
		$sql->adInserir('instrumento_financeiro_projeto', $instrumento_financeiro_projeto);
		$sql->adInserir('instrumento_financeiro_tarefa', $instrumento_financeiro_tarefa);
		$sql->adInserir('instrumento_financeiro_fonte', $instrumento_financeiro_fonte);
		$sql->adInserir('instrumento_financeiro_regiao', $instrumento_financeiro_regiao);
		$sql->adInserir('instrumento_financeiro_classificacao', $instrumento_financeiro_classificacao);
		$sql->adInserir('instrumento_financeiro_valor', float_americano($instrumento_financeiro_valor));
		$sql->adInserir('instrumento_financeiro_ano', $instrumento_financeiro_ano);
		$sql->exec();
		}
	else{
		$sql->adTabela('instrumento_financeiro');
		$sql->adAtualizar('instrumento_financeiro_projeto', $instrumento_financeiro_projeto);
		$sql->adAtualizar('instrumento_financeiro_tarefa', $instrumento_financeiro_tarefa);
		$sql->adAtualizar('instrumento_financeiro_fonte', $instrumento_financeiro_fonte);
		$sql->adAtualizar('instrumento_financeiro_regiao', $instrumento_financeiro_regiao);
		$sql->adAtualizar('instrumento_financeiro_classificacao', $instrumento_financeiro_classificacao);
		$sql->adAtualizar('instrumento_financeiro_valor', float_americano($instrumento_financeiro_valor));
		$sql->adAtualizar('instrumento_financeiro_ano', $instrumento_financeiro_ano);
		$sql->adOnde('instrumento_financeiro_id='.(int)$instrumento_financeiro_id);
		$sql->exec();
		}
		
	$saida=atualizar_financeiros($instrumento_id, $uuid, $instrumento_casa_significativa, $instrumento_campo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_financeiro","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_financeiro_ajax");



function excluir_financeiro_ajax($instrumento_financeiro_id, $instrumento_id, $uuid=null, $instrumento_casa_significativa=null, $instrumento_campo=null){
	$sql = new BDConsulta;
	$sql->setExcluir('instrumento_financeiro');
	$sql->adOnde('instrumento_financeiro_id='.(int)$instrumento_financeiro_id);
	$sql->exec();
	$sql->limpar();
	$saida=atualizar_financeiros($instrumento_id, $uuid, $instrumento_casa_significativa, $instrumento_campo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_financeiro","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_financeiro_ajax");	



$instrumentoFonte = getSisValor('instrumento_fonte');
function atualizar_financeiros($instrumento_id=0, $uuid=null, $instrumento_casa_significativa=null, $instrumento_campo=null){
	global $config, $instrumentoFonte;
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_financeiro');
	if ($uuid) $sql->adOnde('instrumento_financeiro_uuid = \''.$uuid.'\'');
	else $sql->adOnde('instrumento_financeiro_instrumento = '.(int)$instrumento_id);
	$sql->adCampo('instrumento_financeiro.*');
	$sql->adOrdem('instrumento_financeiro_ordem');
	$financeiros=$sql->ListaChave('instrumento_financeiro_id');
	$sql->limpar();
	
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo.*');
	$sql->adOnde('instrumento_campo_id ='.(int)$instrumento_campo);
	$exibir=$sql->linha();
	$sql->limpar();
	
	
	$saida='';
	if (is_array($financeiros) && count($financeiros)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th>';
		$saida.= '<th>'.$exibir['instrumento_financeiro_projeto_leg'].'</th>';
		if ($exibir['instrumento_financeiro_tarefa']) $saida.= '<th>'.$exibir['instrumento_financeiro_tarefa_leg'].'</th>';
		if ($exibir['instrumento_financeiro_fonte']) $saida.= '<th>'.$exibir['instrumento_financeiro_fonte_leg'].'</th>';
		if ($exibir['instrumento_financeiro_regiao']) $saida.= '<th>'.$exibir['instrumento_financeiro_regiao_leg'].'</th>';
		if ($exibir['instrumento_financeiro_classificacao']) $saida.= '<th>'.$exibir['instrumento_financeiro_classificacao_leg'].'</th>';
		$saida.= '<th>'.dica('Valor(R$)', 'Valor a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Valor(R$)'.dicaF().'</th>';
		$saida.= '<th>'.dica('Ano', 'Ano a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Ano'.dicaF().'</th>';
		
		$saida.= '<th></th></tr>';
		foreach ($financeiros as $instrumento_financeiro_id => $financeiro) {
			$saida.= '<tr>';
			$saida.= '<td>';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();		
			$saida.= '</td>';
			$saida.= '<td align="left">'.$financeiro['instrumento_financeiro_projeto'].'</td>';
			if ($exibir['instrumento_financeiro_tarefa']) $saida.= '<td align="left">'.$financeiro['instrumento_financeiro_tarefa'].'</td>';
			
			//if ($exibir['instrumento_financeiro_fonte']) $saida.= '<td align="left">'.(isset($instrumentoFonte[$financeiro['instrumento_financeiro_fonte']]) ? $instrumentoFonte[$financeiro['instrumento_financeiro_fonte']] : '').'</td>';
			if ($exibir['instrumento_financeiro_fonte']) $saida.= '<td align="left">'.$financeiro['instrumento_financeiro_fonte'].'</td>';
			
			if ($exibir['instrumento_financeiro_regiao']) $saida.= '<td align="left">'.$financeiro['instrumento_financeiro_regiao'].'</td>';
			if ($exibir['instrumento_financeiro_classificacao']) $saida.= '<td align="left">'.$financeiro['instrumento_financeiro_classificacao'].'</td>';
			$saida.= '<td align="right" style="white-space: nowrap">'.number_format($financeiro['instrumento_financeiro_valor'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			$saida.= '<td align="left">'.$financeiro['instrumento_financeiro_ano'].'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_financeiro('.$financeiro['instrumento_financeiro_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_financeiro('.$financeiro['instrumento_financeiro_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}


function editar_financeiro($instrumento_financeiro_id, $instrumento_casa_significativa){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_financeiro');
	$sql->adCampo('instrumento_financeiro.*');
	$sql->adOnde('instrumento_financeiro_id = '.(int)$instrumento_financeiro_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$objResposta->assign("instrumento_financeiro_id","value", $instrumento_financeiro_id);
	$objResposta->assign("instrumento_financeiro_projeto","value", utf8_encode($linha['instrumento_financeiro_projeto']));
	$objResposta->assign("instrumento_financeiro_tarefa","value", utf8_encode($linha['instrumento_financeiro_tarefa']));
	$objResposta->assign("instrumento_financeiro_fonte","value", utf8_encode($linha['instrumento_financeiro_fonte']));	
	$objResposta->assign("instrumento_financeiro_regiao","value", utf8_encode($linha['instrumento_financeiro_regiao']));	
	$objResposta->assign("instrumento_financeiro_classificacao","value", utf8_encode($linha['instrumento_financeiro_classificacao']));	
	$objResposta->assign("instrumento_financeiro_valor","value", utf8_encode(number_format($linha['instrumento_financeiro_valor'],($instrumento_casa_significativa ? $instrumento_casa_significativa : $config['casas_decimais']), ',', '.')));	
	$objResposta->assign("instrumento_financeiro_ano","value", utf8_encode($linha['instrumento_financeiro_ano']));		
	return $objResposta;
	}	
$xajax->registerFunction("editar_financeiro");


























function exibir_cias($cias){
	global $config;
	$cias_selecionadas=explode(',', $cias);
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0>';
			$saida_cias.= '<tr><td class="texto" style="width:400px;">'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else 	$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_cias',"innerHTML", utf8_encode($saida_cias));
	return $objResposta;
	}
$xajax->registerFunction("exibir_cias");


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

function exibir_recursos($recursos){
	global $config;
	$recursos_selecionados=explode(',', $recursos);
	$saida_recursos='';
	if (count($recursos_selecionados)) {
			$saida_recursos.= '<table cellpadding=0 cellspacing=0>';
			$saida_recursos.= '<tr><td class="texto" style="width:400px;">'.link_recurso($recursos_selecionados[0],'','','esquerda');
			$qnt_lista_recursos=count($recursos_selecionados);
			if ($qnt_lista_recursos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($recursos_selecionados[$i],'','','esquerda').'<br>';
					$saida_recursos.= dica('Outr'.$config['genero_recurso'].'s '.ucfirst($config['recursos']), 'Clique para visualizar '.$config['genero_recurso'].'s demais '.strtolower($config['recursos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
					}
			$saida_recursos.= '</td></tr></table>';
			}
	else $saida_recursos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_recursos',"innerHTML", utf8_encode($saida_recursos));
	return $objResposta;
	}
$xajax->registerFunction("exibir_recursos");


function exibir_contatos($contatos){
	global $config;
	$contatos_selecionados=explode(',', $contatos);
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0>';
			$saida_contatos.= '<tr><td class="texto" style="width:400px;">'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			}
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_contatos',"innerHTML", utf8_encode($saida_contatos));
	return $objResposta;
	}
$xajax->registerFunction("exibir_contatos");

function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			}
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_dept($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts_selecionados[$i]).'<br>';
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			}

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;
	}
$xajax->registerFunction("exibir_depts");



function mudar_posicao_gestao($ordem, $instrumento_gestao_id, $direcao, $instrumento_id=0, $uuid=null){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $instrumento_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_gestao');
		$sql->adOnde('instrumento_gestao_id != '.(int)$instrumento_gestao_id);
		if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOrdem('instrumento_gestao_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('instrumento_gestao');
			$sql->adAtualizar('instrumento_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_gestao_id = '.(int)$instrumento_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_gestao');
					$sql->adAtualizar('instrumento_gestao_ordem', $idx);
					$sql->adOnde('instrumento_gestao_id = '.(int)$acao['instrumento_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('instrumento_gestao');
					$sql->adAtualizar('instrumento_gestao_ordem', $idx + 1);
					$sql->adOnde('instrumento_gestao_id = '.(int)$acao['instrumento_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$instrumento_id=0, 
	$uuid='',  
	
	$instrumento_projeto=null,
	$instrumento_tarefa=null,
	$instrumento_perspectiva=null,
	$instrumento_tema=null,
	$instrumento_objetivo=null,
	$instrumento_fator=null,
	$instrumento_estrategia=null,
	$instrumento_meta=null,
	$instrumento_pratica=null,
	$instrumento_acao=null,
	$instrumento_canvas=null,
	$instrumento_risco=null,
	$instrumento_risco_resposta=null,
	$instrumento_indicador=null,
	$instrumento_calendario=null,
	$instrumento_monitoramento=null,
	$instrumento_ata=null,
	$instrumento_mswot=null,
	$instrumento_swot=null,
	$instrumento_operativo=null,
	$instrumento_instrumento=null,
	$instrumento_recurso=null,
	$instrumento_problema=null,
	$instrumento_demanda=null,
	$instrumento_programa=null,
	$instrumento_licao=null,
	$instrumento_evento=null,
	$instrumento_link=null,
	$instrumento_avaliacao=null,
	$instrumento_tgn=null,
	$instrumento_brainstorm=null,
	$instrumento_gut=null,
	$instrumento_causa_efeito=null,
	$instrumento_arquivo=null,
	$instrumento_forum=null,
	$instrumento_checklist=null,
	$instrumento_agenda=null,
	$instrumento_agrupamento=null,
	$instrumento_patrocinador=null,
	$instrumento_template=null,
	$instrumento_painel=null,
	$instrumento_painel_odometro=null,
	$instrumento_painel_composicao=null,
	$instrumento_tr=null,
	$instrumento_me=null,
	$instrumento_acao_item=null,
	$instrumento_beneficio=null,
	$instrumento_painel_slideshow=null,
	$instrumento_projeto_viabilidade=null,
	$instrumento_projeto_abertura=null,
	$instrumento_plano_gestao=null,
	$instrumento_ssti=null,
	$instrumento_laudo=null,
	$instrumento_trelo=null,
	$instrumento_trelo_cartao=null,
	$instrumento_pdcl=null,
	$instrumento_pdcl_item=null,
	$instrumento_os=null
	)
	{
	if (
		$instrumento_projeto || 
		$instrumento_tarefa || 
		$instrumento_perspectiva || 
		$instrumento_tema || 
		$instrumento_objetivo || 
		$instrumento_fator || 
		$instrumento_estrategia || 
		$instrumento_meta || 
		$instrumento_pratica || 
		$instrumento_acao || 
		$instrumento_canvas || 
		$instrumento_risco || 
		$instrumento_risco_resposta || 
		$instrumento_indicador || 
		$instrumento_calendario || 
		$instrumento_monitoramento || 
		$instrumento_ata || 
		$instrumento_mswot || 
		$instrumento_swot || 
		$instrumento_operativo || 
		$instrumento_instrumento || 
		$instrumento_recurso || 
		$instrumento_problema || 
		$instrumento_demanda || 
		$instrumento_programa || 
		$instrumento_licao || 
		$instrumento_evento || 
		$instrumento_link || 
		$instrumento_avaliacao || 
		$instrumento_tgn || 
		$instrumento_brainstorm || 
		$instrumento_gut || 
		$instrumento_causa_efeito || 
		$instrumento_arquivo || 
		$instrumento_forum || 
		$instrumento_checklist || 
		$instrumento_agenda || 
		$instrumento_agrupamento || 
		$instrumento_patrocinador || 
		$instrumento_template || 
		$instrumento_painel || 
		$instrumento_painel_odometro || 
		$instrumento_painel_composicao || 
		$instrumento_tr || 
		$instrumento_me || 
		$instrumento_acao_item || 
		$instrumento_beneficio || 
		$instrumento_painel_slideshow || 
		$instrumento_projeto_viabilidade || 
		$instrumento_projeto_abertura || 
		$instrumento_plano_gestao|| 
		$instrumento_ssti || 
		$instrumento_laudo || 
		$instrumento_trelo || 
		$instrumento_trelo_cartao || 
		$instrumento_pdcl || 
		$instrumento_pdcl_item || 
		$instrumento_os
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('instrumento_gestao');
			if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
			$sql->exec();
			$sql->limpar();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('instrumento_gestao');
		$sql->adCampo('count(instrumento_gestao_id)');
		if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
		if ($instrumento_tarefa) $sql->adOnde('instrumento_gestao_tarefa='.(int)$instrumento_tarefa);
		elseif ($instrumento_projeto) $sql->adOnde('instrumento_gestao_projeto='.(int)$instrumento_projeto);
		elseif ($instrumento_perspectiva) $sql->adOnde('instrumento_gestao_perspectiva='.(int)$instrumento_perspectiva);
		elseif ($instrumento_tema) $sql->adOnde('instrumento_gestao_tema='.(int)$instrumento_tema);
		elseif ($instrumento_objetivo) $sql->adOnde('instrumento_gestao_objetivo='.(int)$instrumento_objetivo);
		elseif ($instrumento_fator) $sql->adOnde('instrumento_gestao_fator='.(int)$instrumento_fator);
		elseif ($instrumento_estrategia) $sql->adOnde('instrumento_gestao_estrategia='.(int)$instrumento_estrategia);
		elseif ($instrumento_acao) $sql->adOnde('instrumento_gestao_acao='.(int)$instrumento_acao);
		elseif ($instrumento_pratica) $sql->adOnde('instrumento_gestao_pratica='.(int)$instrumento_pratica);
		elseif ($instrumento_meta) $sql->adOnde('instrumento_gestao_meta='.(int)$instrumento_meta);
		elseif ($instrumento_canvas) $sql->adOnde('instrumento_gestao_canvas='.(int)$instrumento_canvas);
		elseif ($instrumento_risco) $sql->adOnde('instrumento_gestao_risco='.(int)$instrumento_risco);
		elseif ($instrumento_risco_resposta) $sql->adOnde('instrumento_gestao_risco_resposta='.(int)$instrumento_risco_resposta);
		elseif ($instrumento_indicador) $sql->adOnde('instrumento_gestao_indicador='.(int)$instrumento_indicador);
		elseif ($instrumento_calendario) $sql->adOnde('instrumento_gestao_calendario='.(int)$instrumento_calendario);
		elseif ($instrumento_monitoramento) $sql->adOnde('instrumento_gestao_monitoramento='.(int)$instrumento_monitoramento);
		elseif ($instrumento_ata) $sql->adOnde('instrumento_gestao_ata='.(int)$instrumento_ata);
		elseif ($instrumento_mswot) $sql->adOnde('instrumento_gestao_mswot='.(int)$instrumento_mswot);
		elseif ($instrumento_swot) $sql->adOnde('instrumento_gestao_swot='.(int)$instrumento_swot);
		elseif ($instrumento_operativo) $sql->adOnde('instrumento_gestao_operativo='.(int)$instrumento_operativo);
		
		elseif ($instrumento_instrumento) $sql->adOnde('instrumento_gestao_semelhante='.(int)$instrumento_instrumento);
		
		elseif ($instrumento_recurso) $sql->adOnde('instrumento_gestao_recurso='.(int)$instrumento_recurso);
		elseif ($instrumento_problema) $sql->adOnde('instrumento_gestao_problema='.(int)$instrumento_problema);
		elseif ($instrumento_demanda) $sql->adOnde('instrumento_gestao_demanda='.(int)$instrumento_demanda);
		elseif ($instrumento_programa) $sql->adOnde('instrumento_gestao_programa='.(int)$instrumento_programa);
		elseif ($instrumento_licao) $sql->adOnde('instrumento_gestao_licao='.(int)$instrumento_licao);
		elseif ($instrumento_evento) $sql->adOnde('instrumento_gestao_evento='.(int)$instrumento_evento);
		elseif ($instrumento_link) $sql->adOnde('instrumento_gestao_link='.(int)$instrumento_link);
		elseif ($instrumento_avaliacao) $sql->adOnde('instrumento_gestao_avaliacao='.(int)$instrumento_avaliacao);
		elseif ($instrumento_tgn) $sql->adOnde('instrumento_gestao_tgn='.(int)$instrumento_tgn);
		elseif ($instrumento_brainstorm) $sql->adOnde('instrumento_gestao_brainstorm='.(int)$instrumento_brainstorm);
		elseif ($instrumento_gut) $sql->adOnde('instrumento_gestao_gut='.(int)$instrumento_gut);
		elseif ($instrumento_causa_efeito) $sql->adOnde('instrumento_gestao_causa_efeito='.(int)$instrumento_causa_efeito);
		elseif ($instrumento_arquivo) $sql->adOnde('instrumento_gestao_arquivo='.(int)$instrumento_arquivo);
		elseif ($instrumento_forum) $sql->adOnde('instrumento_gestao_forum='.(int)$instrumento_forum);
		elseif ($instrumento_checklist) $sql->adOnde('instrumento_gestao_checklist='.(int)$instrumento_checklist);
		elseif ($instrumento_agenda) $sql->adOnde('instrumento_gestao_agenda='.(int)$instrumento_agenda);
		elseif ($instrumento_agrupamento) $sql->adOnde('instrumento_gestao_agrupamento='.(int)$instrumento_agrupamento);
		elseif ($instrumento_patrocinador) $sql->adOnde('instrumento_gestao_patrocinador='.(int)$instrumento_patrocinador);
		elseif ($instrumento_template) $sql->adOnde('instrumento_gestao_template='.(int)$instrumento_template);
		elseif ($instrumento_painel) $sql->adOnde('instrumento_gestao_painel='.(int)$instrumento_painel);
		elseif ($instrumento_painel_odometro) $sql->adOnde('instrumento_gestao_painel_odometro='.(int)$instrumento_painel_odometro);
		elseif ($instrumento_painel_composicao) $sql->adOnde('instrumento_gestao_painel_composicao='.(int)$instrumento_painel_composicao);
		elseif ($instrumento_tr) $sql->adOnde('instrumento_gestao_tr='.(int)$instrumento_tr);
		elseif ($instrumento_me) $sql->adOnde('instrumento_gestao_me='.(int)$instrumento_me);
		elseif ($instrumento_acao_item) $sql->adOnde('instrumento_gestao_acao_item='.(int)$instrumento_acao_item);
		elseif ($instrumento_beneficio) $sql->adOnde('instrumento_gestao_beneficio='.(int)$instrumento_beneficio);
		elseif ($instrumento_painel_slideshow) $sql->adOnde('instrumento_gestao_painel_slideshow='.(int)$instrumento_painel_slideshow);
		elseif ($instrumento_projeto_viabilidade) $sql->adOnde('instrumento_gestao_projeto_viabilidade='.(int)$instrumento_projeto_viabilidade);
		elseif ($instrumento_projeto_abertura) $sql->adOnde('instrumento_gestao_projeto_abertura='.(int)$instrumento_projeto_abertura);
		elseif ($instrumento_plano_gestao) $sql->adOnde('instrumento_gestao_plano_gestao='.(int)$instrumento_plano_gestao);
		elseif ($instrumento_ssti) $sql->adOnde('instrumento_gestao_ssti='.(int)$instrumento_ssti);
		elseif ($instrumento_laudo) $sql->adOnde('instrumento_gestao_laudo='.(int)$instrumento_laudo);
		elseif ($instrumento_trelo) $sql->adOnde('instrumento_gestao_trelo='.(int)$instrumento_trelo);
		elseif ($instrumento_trelo_cartao) $sql->adOnde('instrumento_gestao_trelo_cartao='.(int)$instrumento_trelo_cartao);
		elseif ($instrumento_pdcl) $sql->adOnde('instrumento_gestao_pdcl='.(int)$instrumento_pdcl);
		elseif ($instrumento_pdcl_item) $sql->adOnde('instrumento_gestao_pdcl_item='.(int)$instrumento_pdcl_item);
		elseif ($instrumento_os) $sql->adOnde('instrumento_gestao_os='.(int)$instrumento_os);

	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('instrumento_gestao');
			$sql->adCampo('MAX(instrumento_gestao_ordem)');
			if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('instrumento_gestao');
			if ($uuid) $sql->adInserir('instrumento_gestao_uuid', $uuid);
			else $sql->adInserir('instrumento_gestao_instrumento', (int)$instrumento_id);
			
			if ($instrumento_tarefa) $sql->adInserir('instrumento_gestao_tarefa', (int)$instrumento_tarefa);
			if ($instrumento_projeto) $sql->adInserir('instrumento_gestao_projeto', (int)$instrumento_projeto);
			elseif ($instrumento_perspectiva) $sql->adInserir('instrumento_gestao_perspectiva', (int)$instrumento_perspectiva);
			elseif ($instrumento_tema) $sql->adInserir('instrumento_gestao_tema', (int)$instrumento_tema);
			elseif ($instrumento_objetivo) $sql->adInserir('instrumento_gestao_objetivo', (int)$instrumento_objetivo);
			elseif ($instrumento_fator) $sql->adInserir('instrumento_gestao_fator', (int)$instrumento_fator);
			elseif ($instrumento_estrategia) $sql->adInserir('instrumento_gestao_estrategia', (int)$instrumento_estrategia);
			elseif ($instrumento_acao) $sql->adInserir('instrumento_gestao_acao', (int)$instrumento_acao);
			elseif ($instrumento_pratica) $sql->adInserir('instrumento_gestao_pratica', (int)$instrumento_pratica);
			elseif ($instrumento_meta) $sql->adInserir('instrumento_gestao_meta', (int)$instrumento_meta);
			elseif ($instrumento_canvas) $sql->adInserir('instrumento_gestao_canvas', (int)$instrumento_canvas);
			elseif ($instrumento_risco) $sql->adInserir('instrumento_gestao_risco', (int)$instrumento_risco);
			elseif ($instrumento_risco_resposta) $sql->adInserir('instrumento_gestao_risco_resposta', (int)$instrumento_risco_resposta);
			elseif ($instrumento_indicador) $sql->adInserir('instrumento_gestao_indicador', (int)$instrumento_indicador);
			elseif ($instrumento_calendario) $sql->adInserir('instrumento_gestao_calendario', (int)$instrumento_calendario);
			elseif ($instrumento_monitoramento) $sql->adInserir('instrumento_gestao_monitoramento', (int)$instrumento_monitoramento);
			elseif ($instrumento_ata) $sql->adInserir('instrumento_gestao_ata', (int)$instrumento_ata);
			elseif ($instrumento_mswot) $sql->adInserir('instrumento_gestao_mswot', (int)$instrumento_mswot);
			elseif ($instrumento_swot) $sql->adInserir('instrumento_gestao_swot', (int)$instrumento_swot);
			elseif ($instrumento_operativo) $sql->adInserir('instrumento_gestao_operativo', (int)$instrumento_operativo);
			
			elseif ($instrumento_instrumento) $sql->adInserir('instrumento_gestao_semelhante', (int)$instrumento_instrumento);
			
			elseif ($instrumento_recurso) $sql->adInserir('instrumento_gestao_recurso', (int)$instrumento_recurso);
			elseif ($instrumento_problema) $sql->adInserir('instrumento_gestao_problema', (int)$instrumento_problema);
			elseif ($instrumento_demanda) $sql->adInserir('instrumento_gestao_demanda', (int)$instrumento_demanda);
			elseif ($instrumento_programa) $sql->adInserir('instrumento_gestao_programa', (int)$instrumento_programa);
			elseif ($instrumento_licao) $sql->adInserir('instrumento_gestao_licao', (int)$instrumento_licao);
			elseif ($instrumento_evento) $sql->adInserir('instrumento_gestao_evento', (int)$instrumento_evento);
			elseif ($instrumento_link) $sql->adInserir('instrumento_gestao_link', (int)$instrumento_link);
			elseif ($instrumento_avaliacao) $sql->adInserir('instrumento_gestao_avaliacao', (int)$instrumento_avaliacao);
			elseif ($instrumento_tgn) $sql->adInserir('instrumento_gestao_tgn', (int)$instrumento_tgn);
			elseif ($instrumento_brainstorm) $sql->adInserir('instrumento_gestao_brainstorm', (int)$instrumento_brainstorm);
			elseif ($instrumento_gut) $sql->adInserir('instrumento_gestao_gut', (int)$instrumento_gut);
			elseif ($instrumento_causa_efeito) $sql->adInserir('instrumento_gestao_causa_efeito', (int)$instrumento_causa_efeito);
			elseif ($instrumento_arquivo) $sql->adInserir('instrumento_gestao_arquivo', (int)$instrumento_arquivo);
			elseif ($instrumento_forum) $sql->adInserir('instrumento_gestao_forum', (int)$instrumento_forum);
			elseif ($instrumento_checklist) $sql->adInserir('instrumento_gestao_checklist', (int)$instrumento_checklist);
			elseif ($instrumento_agenda) $sql->adInserir('instrumento_gestao_agenda', (int)$instrumento_agenda);
			elseif ($instrumento_agrupamento) $sql->adInserir('instrumento_gestao_agrupamento', (int)$instrumento_agrupamento);
			elseif ($instrumento_patrocinador) $sql->adInserir('instrumento_gestao_patrocinador', (int)$instrumento_patrocinador);
			elseif ($instrumento_template) $sql->adInserir('instrumento_gestao_template', (int)$instrumento_template);
			elseif ($instrumento_painel) $sql->adInserir('instrumento_gestao_painel', (int)$instrumento_painel);
			elseif ($instrumento_painel_odometro) $sql->adInserir('instrumento_gestao_painel_odometro', (int)$instrumento_painel_odometro);
			elseif ($instrumento_painel_composicao) $sql->adInserir('instrumento_gestao_painel_composicao', (int)$instrumento_painel_composicao);
			elseif ($instrumento_tr) $sql->adInserir('instrumento_gestao_tr', (int)$instrumento_tr);
			elseif ($instrumento_me) $sql->adInserir('instrumento_gestao_me', (int)$instrumento_me);
			elseif ($instrumento_acao_item) $sql->adInserir('instrumento_gestao_acao_item', (int)$instrumento_acao_item);
			elseif ($instrumento_beneficio) $sql->adInserir('instrumento_gestao_beneficio', (int)$instrumento_beneficio);
			elseif ($instrumento_painel_slideshow) $sql->adInserir('instrumento_gestao_painel_slideshow', (int)$instrumento_painel_slideshow);
			elseif ($instrumento_projeto_viabilidade) $sql->adInserir('instrumento_gestao_projeto_viabilidade', (int)$instrumento_projeto_viabilidade);
			elseif ($instrumento_projeto_abertura) $sql->adInserir('instrumento_gestao_projeto_abertura', (int)$instrumento_projeto_abertura);
			elseif ($instrumento_plano_gestao) $sql->adInserir('instrumento_gestao_plano_gestao', (int)$instrumento_plano_gestao);
			elseif ($instrumento_ssti) $sql->adInserir('instrumento_gestao_ssti', (int)$instrumento_ssti);
			elseif ($instrumento_laudo) $sql->adInserir('instrumento_gestao_laudo', (int)$instrumento_laudo);
			elseif ($instrumento_trelo) $sql->adInserir('instrumento_gestao_trelo', (int)$instrumento_trelo);
			elseif ($instrumento_trelo_cartao) $sql->adInserir('instrumento_gestao_trelo_cartao', (int)$instrumento_trelo_cartao);
			elseif ($instrumento_pdcl) $sql->adInserir('instrumento_gestao_pdcl', (int)$instrumento_pdcl);
			elseif ($instrumento_pdcl_item) $sql->adInserir('instrumento_gestao_pdcl_item', (int)$instrumento_pdcl_item);
			elseif ($instrumento_os) $sql->adInserir('instrumento_gestao_os', (int)$instrumento_os);
			$sql->adInserir('instrumento_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($instrumento_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($instrumento_id=0, $uuid=null, $instrumento_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('instrumento_gestao');
	$sql->adOnde('instrumento_gestao_id='.(int)$instrumento_gestao_id);
	$sql->exec();
	$sql->limpar();
	
	$saida=atualizar_gestao($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($instrumento_id=0, $uuid=''){	
	$saida=atualizar_gestao($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($instrumento_id=0, $uuid=null){
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao.*');
	if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
	$sql->adOrdem('instrumento_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['instrumento_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']).'</td>';
		elseif ($gestao_data['instrumento_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']).'</td>';
		elseif ($gestao_data['instrumento_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['instrumento_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']).'</td>';
		elseif ($gestao_data['instrumento_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']).'</td>';
		elseif ($gestao_data['instrumento_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']).'</td>';
		elseif ($gestao_data['instrumento_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']).'</td>';
		elseif ($gestao_data['instrumento_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']).'</td>';
		elseif ($gestao_data['instrumento_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']).'</td>';
		elseif ($gestao_data['instrumento_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']).'</td>';
		elseif ($gestao_data['instrumento_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['instrumento_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']).'</td>';
		elseif ($gestao_data['instrumento_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']).'</td>';
		elseif ($gestao_data['instrumento_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['instrumento_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']).'</td>';
		elseif ($gestao_data['instrumento_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['instrumento_gestao_mswot']).'</td>';
		elseif ($gestao_data['instrumento_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']).'</td>';
		elseif ($gestao_data['instrumento_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']).'</td>';
		
		elseif ($gestao_data['instrumento_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['instrumento_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['instrumento_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']).'</td>';
		elseif ($gestao_data['instrumento_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['instrumento_gestao_problema']).'</td>';
		elseif ($gestao_data['instrumento_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']).'</td>';
		elseif ($gestao_data['instrumento_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']).'</td>';
		elseif ($gestao_data['instrumento_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']).'</td>';
		elseif ($gestao_data['instrumento_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']).'</td>';
		elseif ($gestao_data['instrumento_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']).'</td>';
		elseif ($gestao_data['instrumento_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['instrumento_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['instrumento_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['instrumento_gestao_gut']).'</td>';
		elseif ($gestao_data['instrumento_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['instrumento_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['instrumento_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']).'</td>';
		elseif ($gestao_data['instrumento_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']).'</td>';
		elseif ($gestao_data['instrumento_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']).'</td>';
		elseif ($gestao_data['instrumento_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['instrumento_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['instrumento_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['instrumento_gestao_template']).'</td>';
		elseif ($gestao_data['instrumento_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['instrumento_gestao_painel']).'</td>';
		elseif ($gestao_data['instrumento_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['instrumento_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['instrumento_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['instrumento_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['instrumento_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['instrumento_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['instrumento_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['instrumento_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['instrumento_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['instrumento_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['instrumento_gestao_ssti']).'</td>';
		elseif ($gestao_data['instrumento_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['instrumento_gestao_laudo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['instrumento_gestao_trelo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['instrumento_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['instrumento_gestao_pdcl']).'</td>';
		elseif ($gestao_data['instrumento_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['instrumento_gestao_pdcl_item']).'</td>';
		elseif ($gestao_data['instrumento_gestao_os']) $saida.= '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['instrumento_gestao_os']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['instrumento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		
function instrumento_existe($nome='', $instrumento_id=0){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adCampo('count(instrumento_id)');
	$sql->adOnde('instrumento_nome = "'.$nome.'"');
	if ($instrumento_id) $sql->adOnde('instrumento_id != '.(int)$instrumento_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_instrumento","value", (int)$existe);
	return $objResposta;
	}
	
$xajax->registerFunction("instrumento_existe");	








//processo

function mudar_posicao_processo($ordem, $instrumento_processo_id, $direcao, $instrumento_id=0, $uuid=''){
	$sql = new BDConsulta;
	if($direcao&&$instrumento_processo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_processo');
		$sql->adOnde('instrumento_processo_id != '.$instrumento_processo_id);
		if ($uuid) $sql->adOnde('instrumento_processo_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_processo_instrumento = '.$instrumento_id);
		$sql->adOrdem('instrumento_processo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();

		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			}
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			}
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			}
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('instrumento_processo');
			$sql->adAtualizar('instrumento_processo_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_processo_id = '.$instrumento_processo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_processo');
					$sql->adAtualizar('instrumento_processo_ordem', $idx);
					$sql->adOnde('instrumento_processo_id = '.$acao['instrumento_processo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('instrumento_processo');
					$sql->adAtualizar('instrumento_processo_ordem', $idx + 1);
					$sql->adOnde('instrumento_processo_id = '.$acao['instrumento_processo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}

	$saida=atualizar_processo($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_processo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("mudar_posicao_processo");

function incluir_processo($instrumento_id=0, $uuid='', $instrumento_processo_id, $instrumento_processo_processo){
	$sql = new BDConsulta;
	$instrumento_processo_processo=previnirXSS(utf8_decode($instrumento_processo_processo));

	if ($instrumento_processo_id){
		$sql->adTabela('instrumento_processo');
		$sql->adAtualizar('instrumento_processo_processo', $instrumento_processo_processo);
		$sql->adOnde('instrumento_processo_id = '.$instrumento_processo_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {
		$sql->adTabela('instrumento_processo');
		$sql->adCampo('count(instrumento_processo_id) AS soma');
		if ($uuid) $sql->adOnde('instrumento_processo_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_processo_instrumento ='.(int)$instrumento_id);
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();

		$sql->adTabela('instrumento_processo');
		if ($uuid) $sql->adInserir('instrumento_processo_uuid', $uuid);
		else $sql->adInserir('instrumento_processo_instrumento', $instrumento_id);
		$sql->adInserir('instrumento_processo_ordem', $soma_total);
		$sql->adInserir('instrumento_processo_processo', $instrumento_processo_processo);
		$sql->exec();
		}
	$saida=atualizar_processo($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_processo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_processo");

function excluir_processo($instrumento_processo_id, $instrumento_id, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('instrumento_processo');
	$sql->adOnde('instrumento_processo_id='.(int)$instrumento_processo_id);
	$sql->exec();
	$saida=atualizar_processo($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_processo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_processo");

function atualizar_processo($instrumento_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_processo');
	if ($uuid) $sql->adOnde('instrumento_processo_uuid = \''.$uuid.'\'');
	else $sql->adOnde('instrumento_processo_instrumento = '.$instrumento_id);
	$sql->adCampo('instrumento_processo_id, instrumento_processo_processo, instrumento_processo_ordem');
	$sql->adOrdem('instrumento_processo_ordem');
	$processo=$sql->ListaChave('instrumento_processo_id');
	$sql->limpar();
	$saida='';
	if (count($processo)) {
		$saida.='<table cellspacing=0 cellpadding=0 class="tbl1" align=left>';
		$saida.= '<tr><th></th><th>Processo</th><th></th></tr>';
		foreach ($processo as $processo_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$linha['instrumento_processo_ordem'].', '.$linha['instrumento_processo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$linha['instrumento_processo_ordem'].', '.$linha['instrumento_processo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$linha['instrumento_processo_ordem'].', '.$linha['instrumento_processo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$linha['instrumento_processo_ordem'].', '.$linha['instrumento_processo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.$linha['instrumento_processo_processo'].'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_processo('.$linha['instrumento_processo_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o processo d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esto processo?\')) {excluir_processo('.$linha['instrumento_processo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o processo d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}

function editar_processo($instrumento_processo_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_processo');
	$sql->adCampo('instrumento_processo_processo');
	$sql->adOnde('instrumento_processo_id = '.(int)$instrumento_processo_id);
	$instrumento_processo_processo=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("instrumento_processo_id","value", $instrumento_processo_id);
	$objResposta->assign("instrumento_processo_processo","value", utf8_encode($instrumento_processo_processo));
	return $objResposta;
	}
$xajax->registerFunction("editar_processo");










//edital

function mudar_posicao_edital($ordem, $instrumento_edital_id, $direcao, $instrumento_id=0, $uuid=''){
	$sql = new BDConsulta;
	if($direcao&&$instrumento_edital_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_edital');
		$sql->adOnde('instrumento_edital_id != '.$instrumento_edital_id);
		if ($uuid) $sql->adOnde('instrumento_edital_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_edital_instrumento = '.$instrumento_id);
		$sql->adOrdem('instrumento_edital_ordem');
		$membros = $sql->Lista();
		$sql->limpar();

		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			}
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			}
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			}
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('instrumento_edital');
			$sql->adAtualizar('instrumento_edital_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_edital_id = '.$instrumento_edital_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_edital');
					$sql->adAtualizar('instrumento_edital_ordem', $idx);
					$sql->adOnde('instrumento_edital_id = '.$acao['instrumento_edital_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('instrumento_edital');
					$sql->adAtualizar('instrumento_edital_ordem', $idx + 1);
					$sql->adOnde('instrumento_edital_id = '.$acao['instrumento_edital_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}

	$saida=atualizar_edital($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_edital","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("mudar_posicao_edital");

function incluir_edital($instrumento_id=0, $uuid='', $instrumento_edital_id, $instrumento_edital_edital){
	$sql = new BDConsulta;
	$instrumento_edital_edital=previnirXSS(utf8_decode($instrumento_edital_edital));

	if ($instrumento_edital_id){
		$sql->adTabela('instrumento_edital');
		$sql->adAtualizar('instrumento_edital_edital', $instrumento_edital_edital);
		$sql->adOnde('instrumento_edital_id = '.$instrumento_edital_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {
		$sql->adTabela('instrumento_edital');
		$sql->adCampo('count(instrumento_edital_id) AS soma');
		if ($uuid) $sql->adOnde('instrumento_edital_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_edital_instrumento ='.(int)$instrumento_id);
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();

		$sql->adTabela('instrumento_edital');
		if ($uuid) $sql->adInserir('instrumento_edital_uuid', $uuid);
		else $sql->adInserir('instrumento_edital_instrumento', $instrumento_id);
		$sql->adInserir('instrumento_edital_ordem', $soma_total);
		$sql->adInserir('instrumento_edital_edital', $instrumento_edital_edital);
		$sql->exec();
		}
	$saida=atualizar_edital($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_edital","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_edital");

function excluir_edital($instrumento_edital_id, $instrumento_id, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('instrumento_edital');
	$sql->adOnde('instrumento_edital_id='.(int)$instrumento_edital_id);
	$sql->exec();
	$saida=atualizar_edital($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_edital","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_edital");

function atualizar_edital($instrumento_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_edital');
	if ($uuid) $sql->adOnde('instrumento_edital_uuid = \''.$uuid.'\'');
	else $sql->adOnde('instrumento_edital_instrumento = '.$instrumento_id);
	$sql->adCampo('instrumento_edital_id, instrumento_edital_edital, instrumento_edital_ordem');
	$sql->adOrdem('instrumento_edital_ordem');
	$edital=$sql->ListaChave('instrumento_edital_id');
	$sql->limpar();
	$saida='';
	if (count($edital)) {
		$saida.='<table cellspacing=0 cellpadding=0 class="tbl1" align=left>';
		$saida.= '<tr><th></th><th>Edital</th><th></th></tr>';
		foreach ($edital as $edital_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$linha['instrumento_edital_ordem'].', '.$linha['instrumento_edital_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$linha['instrumento_edital_ordem'].', '.$linha['instrumento_edital_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$linha['instrumento_edital_ordem'].', '.$linha['instrumento_edital_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$linha['instrumento_edital_ordem'].', '.$linha['instrumento_edital_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.$linha['instrumento_edital_edital'].'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_edital('.$linha['instrumento_edital_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o edital d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esto edital?\')) {excluir_edital('.$linha['instrumento_edital_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o edital d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}

function editar_edital($instrumento_edital_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_edital');
	$sql->adCampo('instrumento_edital_edital');
	$sql->adOnde('instrumento_edital_id = '.(int)$instrumento_edital_id);
	$instrumento_edital_edital=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("instrumento_edital_id","value", $instrumento_edital_id);
	$objResposta->assign("instrumento_edital_edital","value", utf8_encode($instrumento_edital_edital));
	return $objResposta;
	}
$xajax->registerFunction("editar_edital");










$xajax->processRequest();

?>