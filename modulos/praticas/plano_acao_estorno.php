<?php 
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic,$bd;
$Aplic->carregarCKEditorJS();

$plano_acao_id = intval(getParam($_REQUEST, 'plano_acao', null));
$tipo = getParam($_REQUEST, 'tipo', 'ne');

$os_id=0;
$tr_id=0;
$instrumento_id=0;
$projeto_id=0;


if ($tipo=='ne') $tiponome='Nota de Empenho';
elseif ($tipo=='ns') $tiponome='Nota de Liquidação';
else $tiponome='Ordem Bancária';

if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');

require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
$obj = new CplanoAcao();
$obj->load($plano_acao_id);

	
if($plano_acao_id && !($podeEditar && permiteEditarPlanoAcao($obj->plano_acao_acesso, $plano_acao_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

$desfazer=getParam($_REQUEST, 'desfazer', null);

if ($desfazer){
	
	
	if ($tipo=='ne'){
		$sql->adTabela('financeiro_estorno_ne');
		$sql->adCampo('financeiro_estorno_ne.*');
		$sql->adOnde('financeiro_estorno_ne_id='.(int)$desfazer);	
		$linha=$sql->linha();
		$sql->limpar();	
		
		if ($linha['financeiro_estorno_ne_valor'] < $linha['financeiro_estorno_ne_valor_original']){
			//alterar valorea	
			$sql->adTabela('financeiro_rel_ne');
			$sql->adAtualizar('financeiro_rel_ne_valor', $linha['financeiro_estorno_ne_valor_original']);
			$sql->adOnde('financeiro_rel_ne_id='.(int)$linha['financeiro_estorno_ne_relacionado']);
			$sql->exec();
			$sql->limpar();	
			}
		else {
			//recriar
			$sql->adTabela('financeiro_rel_ne');
			$sql->adInserir('financeiro_rel_ne_id', $linha['financeiro_estorno_ne_relacionado']);
			$sql->adInserir('financeiro_rel_ne_acao', $plano_acao_id);
			$sql->adInserir('financeiro_rel_ne_ne', $linha['financeiro_estorno_ne_ne']);
			$sql->adInserir('financeiro_rel_ne_valor', $linha['financeiro_estorno_ne_valor_original']);
			$sql->exec();
			$sql->limpar();
			}
			
		$sql->setExcluir('financeiro_estorno_ne');
		$sql->adOnde('financeiro_estorno_ne_id = '.(int)$desfazer);
		$sql->exec();
		$sql->limpar();	
			
		}
	elseif ($tipo=='ns'){
		$sql->adTabela('financeiro_estorno_ns');
		$sql->adCampo('financeiro_estorno_ns.*');
		$sql->adOnde('financeiro_estorno_ns_id='.(int)$desfazer);	
		$linha=$sql->linha();
		$sql->limpar();	
		
		if ($linha['financeiro_estorno_ns_valor'] < $linha['financeiro_estorno_ns_valor_original']){
			//alterar valorea	
			$sql->adTabela('financeiro_rel_ns');
			$sql->adAtualizar('financeiro_rel_ns_valor', $linha['financeiro_estorno_ns_valor_original']);
			$sql->adOnde('financeiro_rel_ns_id='.(int)$linha['financeiro_estorno_ns_relacionado']);
			$sql->exec();
			$sql->limpar();	
			}
		else {
			//recriar
			$sql->adTabela('financeiro_rel_ns');
			$sql->adInserir('financeiro_rel_ns_id', $linha['financeiro_estorno_ns_relacionado']);
			$sql->adInserir('financeiro_rel_ns_acao', $plano_acao_id);
			$sql->adInserir('financeiro_rel_ns_ns', $linha['financeiro_estorno_ns_ns']);
			$sql->adInserir('financeiro_rel_ns_valor', $linha['financeiro_estorno_ns_valor_original']);
			$sql->exec();
			$sql->limpar();
			}
			
		$sql->setExcluir('financeiro_estorno_ns');
		$sql->adOnde('financeiro_estorno_ns_id = '.(int)$desfazer);
		$sql->exec();
		$sql->limpar();	
		}
	else {
		$sql->adTabela('financeiro_estorno_ob');
		$sql->adCampo('financeiro_estorno_ob.*');
		$sql->adOnde('financeiro_estorno_ob_id='.(int)$desfazer);	
		$linha=$sql->linha();
		$sql->limpar();	
		
		if ($linha['financeiro_estorno_ob_valor'] < $linha['financeiro_estorno_ob_valor_original']){
			//alterar valorea	
			$sql->adTabela('financeiro_rel_ob');
			$sql->adAtualizar('financeiro_rel_ob_valor', $linha['financeiro_estorno_ob_valor_original']);
			$sql->adOnde('financeiro_rel_ob_id='.(int)$linha['financeiro_estorno_ob_relacionado']);
			$sql->exec();
			$sql->limpar();	
			}
		else {
			//recriar
			$sql->adTabela('financeiro_rel_ob');
			$sql->adInserir('financeiro_rel_ob_id', $linha['financeiro_estorno_ob_relacionado']);
			$sql->adInserir('financeiro_rel_ob_acao', $plano_acao_id);
			$sql->adInserir('financeiro_rel_ob_ob', $linha['financeiro_estorno_ob_ob']);
			$sql->adInserir('financeiro_rel_ob_valor', $linha['financeiro_estorno_ob_valor_original']);
			$sql->exec();
			$sql->limpar();
			}
			
		$sql->setExcluir('financeiro_estorno_ob');
		$sql->adOnde('financeiro_estorno_ob_id = '.(int)$desfazer);
		$sql->exec();
		$sql->limpar();	
		
		}	
	
	
	
	
	
	
	
	
	
	}


if (getParam($_REQUEST, 'processar', null)){
	

	$retorno_id = getParam($_REQUEST, 'retorno_id', null);
	$retorno_objeto = getParam($_REQUEST, 'retorno_objeto', null);
	

	$retorno_valor = getParam($_REQUEST, 'retorno_valor', null);
	$retorno_antigo = getParam($_REQUEST, 'retorno_antigo', null);
	$retorno_id = explode(':', $retorno_id);
	$retorno_objeto = explode(':', $retorno_objeto);
	$retorno_valor = explode(':', $retorno_valor);
	$retorno_antigo = explode(':', $retorno_antigo);
	//criar a linha de estorno
	
	$sql->adTabela('financeiro_estorno');
	$sql->adInserir('financeiro_estorno_acao', $plano_acao_id);
	$sql->adInserir('financeiro_estorno_responsavel', $Aplic->usuario_id);
	$sql->adInserir('financeiro_estorno_data', date('Y-m-d H:i:s'));
	$sql->adInserir('financeiro_estorno_justificativa', getParam($_REQUEST, 'justificativa', null));
	$sql->exec();
	$financeiro_estorno_id=$bd->Insert_ID('financeiro_estorno','financeiro_estorno_id');
	$sql->limpar();
	
	
	foreach ($retorno_id as $chave => $id) {
		
		//abatou só parte do valor
		if ($retorno_valor[$chave]!=$retorno_antigo[$chave]){
			if ($tipo=='ne'){
				$sql->adTabela('financeiro_rel_ne');
				$sql->adAtualizar('financeiro_rel_ne_valor', $retorno_antigo[$chave]-$retorno_valor[$chave]);
				$sql->adAtualizar('financeiro_rel_ne_aprovou', null);
				$sql->adAtualizar('financeiro_rel_ne_data_aprovou', null);
				$sql->adOnde('financeiro_rel_ne_id='.(int)$id);	
				$sql->exec();
				$sql->limpar();	
				}
			elseif ($tipo=='ns'){
				$sql->adTabela('financeiro_rel_ns');
				$sql->adAtualizar('financeiro_rel_ns_valor', $retorno_antigo[$chave]-$retorno_valor[$chave]);
				$sql->adAtualizar('financeiro_rel_ns_aprovou', null);
				$sql->adAtualizar('financeiro_rel_ns_data_aprovou', null);
				$sql->adOnde('financeiro_rel_ns_id='.(int)$id);
				$sql->exec();
				$sql->limpar();	
				}
			else {
				$sql->adTabela('financeiro_rel_ob');
				$sql->adAtualizar('financeiro_rel_ob_valor', $retorno_antigo[$chave]-$retorno_valor[$chave]);
				$sql->adAtualizar('financeiro_rel_ob_aprovou', null);
				$sql->adAtualizar('financeiro_rel_ob_data_aprovou', null);
				$sql->adOnde('financeiro_rel_ob_id='.(int)$id);
				$sql->exec();
				$sql->limpar();	
				}	
			}
		else {
			//valor identico elimina da tabela de relacionamento
			
			if ($tipo=='ne'){
				
				$sql->setExcluir('financeiro_rel_ne');
				$sql->adOnde('financeiro_rel_ne_id = '.(int)$id);
				$sql->exec();
				$sql->limpar();
				}
			elseif ($tipo=='ns'){
				$sql->setExcluir('financeiro_rel_ns');
				$sql->adOnde('financeiro_rel_ns_id = '.(int)$id);
				$sql->exec();
				$sql->limpar();
				}
			else {
				$sql->setExcluir('financeiro_rel_ob');
				$sql->adOnde('financeiro_rel_ob_id = '.(int)$id);
				$sql->exec();
				$sql->limpar();
				}	
			}
		
		if ($tipo=='ne'){
			$sql->adTabela('financeiro_estorno_ne');
			$sql->adInserir('financeiro_estorno_ne_estorno', $financeiro_estorno_id);
			$sql->adInserir('financeiro_estorno_ne_ne', $retorno_objeto[$chave]);
			$sql->adInserir('financeiro_estorno_ne_valor', $retorno_valor[$chave]);
			$sql->adInserir('financeiro_estorno_ne_valor_original', $retorno_antigo[$chave]);
			$sql->adInserir('financeiro_estorno_ne_relacionado', $id);
			$sql->exec();	
			$sql->limpar();
			}
		elseif ($tipo=='ns'){
			$sql->adTabela('financeiro_estorno_ns');
			$sql->adInserir('financeiro_estorno_ns_estorno', $financeiro_estorno_id);
			$sql->adInserir('financeiro_estorno_ns_ns', $retorno_objeto[$chave]);
			$sql->adInserir('financeiro_estorno_ns_valor', $retorno_valor[$chave]);
			$sql->adInserir('financeiro_estorno_ns_valor_original', $retorno_antigo[$chave]);
			$sql->adInserir('financeiro_estorno_ns_relacionado', $id);
			$sql->exec();	
			$sql->limpar();
			}
		else {
			$sql->adTabela('financeiro_estorno_ob');
			$sql->adInserir('financeiro_estorno_ob_estorno', $financeiro_estorno_id);
			$sql->adInserir('financeiro_estorno_ob_ob', $retorno_objeto[$chave]);
			$sql->adInserir('financeiro_estorno_ob_valor', $retorno_valor[$chave]);
			$sql->adInserir('financeiro_estorno_ob_valor_original', $retorno_antigo[$chave]);
			$sql->adInserir('financeiro_estorno_ob_relacionado', $id);
			$sql->exec();	
			$sql->limpar();
			}	
		}
	}





echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="plano_acao_id" id ="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="tipo" id ="tipo" value="'.$tipo.'" />';
echo '<input type="hidden" name="processar" id="processar" value="" />';
echo '<input type="hidden" name="retorno_id" id="retorno_id" value="" />';
echo '<input type="hidden" name="retorno_objeto" id="retorno_objeto" value="" />';
echo '<input type="hidden" name="retorno_valor" id="retorno_valor" value="" />';
echo '<input type="hidden" name="retorno_antigo" id="retorno_antigo" value="" />';
echo '<input type="hidden" name="desfazer" id="desfazer" value="" />';






$botoesTitulo = new CBlocoTitulo('Estornar '.$tiponome.' n'.$config['genero_acao'].' '.ucfirst($config['acao']), '../../../modulos/praticas/imagens/plano_acao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();





if ($tipo=='ne'){
	$sql->adTabela('financeiro_rel_ne', 'financeiro_rel_ne');
	$sql->esqUnir('financeiro_ne', 'financeiro_ne', 'financeiro_rel_ne_ne=financeiro_ne_id');
	$sql->adOnde('financeiro_rel_ne_acao ='.(int)$plano_acao_id);
	$sql->adCampo('financeiro_rel_ne.*, financeiro_ne.*');
	$sql->adCampo('formatar_data(DATA_EMP, \'%d/%m/%Y\') AS data');
	$sql->adOrdem('financeiro_rel_ne_ordem');
	$financeiro_rel=$sql->ListaChave('financeiro_rel_ne_id');
	$sql->limpar();
	}
elseif ($tipo=='ns'){
	$sql->adTabela('financeiro_rel_ns', 'financeiro_rel_ns');
	$sql->esqUnir('financeiro_ns', 'financeiro_ns', 'financeiro_rel_ns_ns=financeiro_ns_id');
	$sql->adOnde('financeiro_rel_ns_acao ='.(int)$plano_acao_id);
	$sql->adCampo('financeiro_rel_ns.*, financeiro_ns.*');
	$sql->adCampo('formatar_data(DATA_LIQ, \'%d/%m/%Y\') AS data');
	$sql->adOrdem('financeiro_rel_ns_ordem');
	$financeiro_rel=$sql->ListaChave('financeiro_rel_ns_id');
	$sql->limpar();	
	}
else {
	$sql->adTabela('financeiro_rel_ob', 'financeiro_rel_ob');
	$sql->esqUnir('financeiro_ob', 'financeiro_ob', 'financeiro_rel_ob_ob=financeiro_ob_id');
	$sql->adOnde('financeiro_rel_ob_acao = '.(int)$plano_acao_id);
	$sql->adCampo('financeiro_rel_ob.*, financeiro_ob.*');
	$sql->adCampo('formatar_data(DATA_EMISSAO, \'%d/%m/%Y\') AS data');
	$sql->adOrdem('financeiro_rel_ob_ordem');
	$financeiro_rel=$sql->ListaChave('financeiro_rel_ob_id');
	$sql->limpar();
	}	

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';

	
if (count($financeiro_rel)) {
	$total=0;
	
	echo '<tr><td width=50>Justificativa:</td><td align="left"><textarea data-gpweb-cmp="ckeditor" name="justificativa" id="justificativa" style="width:750px;" class="textarea"></textarea></td></tr>';
	echo '<tr><td colspan=20 align=center><b>'.$tiponome.'</b></td></tr>';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 class="tbl1" align=left width="100%">';
	
	echo '<tr>
	<th width=90>Número</th>
	<th width=70>Data</th>
	<th width=120>Favorecido</th>';
	if ($tipo=='ns') echo '<th width=50>ND</th>';
	echo '<th>Observação</th>
	<th width=74>Valor</th>
	<th width=74>Alocado</th>
	<th width=74>Estornar</th>
	</tr>';
		
	foreach ($financeiro_rel as $financeiro_rel_id => $linha) {
		echo '<tr align="center">';
		if ($tipo=='ne'){
			echo '<td width=50 style="white-space: nowrap"><a href="javascript: void(0);" onclick="popNE('.$linha['financeiro_ne_id'].')">'.substr($linha['NUMR_EMP'], 0, 5).'.'.substr($linha['NUMR_EMP'], 5, 4).'.'.substr($linha['NUMR_EMP'], 9, 2).'.'.substr($linha['NUMR_EMP'], 11, 6).'-'.substr($linha['NUMR_EMP'], 17, 1).'</a></td>';
			echo '<td width=50 style="white-space: nowrap">'.$linha['data'].'</td>';
			echo '<td width=50 style="white-space: nowrap">'.cpf_cnpj(isset($linha['CNPJ']) ? $linha['CNPJ'] : $linha['CPF']).'</td>';	
			echo '<td align=left>'.$linha['SITUACAO_EMP'].'</td>';
			echo '<td align="right" width=50 style="white-space: nowrap">'.number_format($linha['VALR_EMP'], 2, ',', '.').'</td>';
			echo '<td align="right" width=74>'.number_format($linha['financeiro_rel_ne_valor'], 2, ',', '.').'</td>';
			echo '<td align="right" width=74><input type="hidden" name="id_objeto[]" id="id_objeto_'.$financeiro_rel_id.'" value="'.$linha['financeiro_ne_id'].'" /><input type="hidden" name="valor[]" id="valor_'.$financeiro_rel_id.'" value="'.(float)$linha['financeiro_rel_ne_valor'].'" /><input type="text" name="estorno_valor[]" id="estorno_valor_'.$financeiro_rel_id.'" value="" style="width:150px;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" /></td>';
			$total+=$linha['financeiro_rel_'.$tipo.'_valor'];
			}
		elseif ($tipo=='ns'){
			echo '<td width=50 style="white-space: nowrap"><a href="javascript: void(0);" onclick="popNS('.$linha['financeiro_ns_id'].')">'.substr($linha['NUMR_LIQ'], 0, 5).'.'.substr($linha['NUMR_LIQ'], 5, 4).'.'.substr($linha['NUMR_LIQ'], 9, 2).'.'.substr($linha['NUMR_LIQ'], 11, 6).'-'.substr($linha['NUMR_LIQ'], 17, 1).'</a></td>';
			echo '<td width=50 style="white-space: nowrap">'.$linha['data'].'</td>';
			echo '<td width=50 style="white-space: nowrap">'.cpf_cnpj(isset($linha['CNPJ']) ? $linha['CNPJ'] : $linha['CPF']).'</td>';
			echo '<td width=50 style="white-space: nowrap">'.$linha['CD_ELEMENTO_DESPESA'].'</td>';
			echo '<td align=left>'.$linha['SITUACAO_LIQ'].'</td>';
			echo '<td align="right" width=50 style="white-space: nowrap">'.number_format((float)$linha['VALR_LIQ'], 2, ',', '.').'</td>';
			echo '<td align="right" width=74>'.number_format($linha['financeiro_rel_ns_valor'], 2, ',', '.').'</td>';
			echo '<td align="right" width=74><input type="hidden" name="id_objeto[]" id="id_objeto_'.$financeiro_rel_id.'" value="'.$linha['financeiro_ns_id'].'" /><input type="hidden" name="valor[]" id="valor_'.$financeiro_rel_id.'" value="'.(float)$linha['financeiro_rel_ns_valor'].'" /><input type="text" name="estorno_valor[]" id="estorno_valor_'.$financeiro_rel_id.'" value="" style="width:150px;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" /></td>';
			$total+=$linha['financeiro_rel_ns_valor'];
			}	
		else {
			echo '<td width=50 style="white-space: nowrap"><a href="javascript: void(0);" onclick="popOB('.$linha['financeiro_ob_id'].')">'.substr($linha['NUMR_NOB'], 0, 5).'.'.substr($linha['NUMR_NOB'], 5, 4).'.'.substr($linha['NUMR_NOB'], 9, 2).'.'.substr($linha['NUMR_NOB'], 11, 6).'-'.substr($linha['NUMR_NOB'], 17, 1).'</a></td>';
			echo '<td width=50 style="white-space: nowrap">'.$linha['data'].'</td>';
			echo '<td width=200>'.(isset($linha['NOME_CREDOR']) ? $linha['NOME_CREDOR'] : '').'</td>';
			echo '<td align=left>'.$linha['HISTORICO_OBSERVACAO'].'</td>';
			echo '<td align="right" width=50 style="white-space: nowrap">'.number_format((float)$linha['VALR_NOB'], 2, ',', '.').'</td>';
			echo '<td align="right" width=74>'.number_format($linha['financeiro_rel_ob_valor'], 2, ',', '.').'</td>';
			echo '<td align="right" width=74><input type="hidden" name="id_objeto[]" id="id_objeto_'.$financeiro_rel_id.'" value="'.$linha['financeiro_ob_id'].'" /><input type="hidden" name="valor[]" id="valor_'.$financeiro_rel_id.'" value="'.(float)$linha['financeiro_rel_ob_valor'].'" /><input type="text" name="estorno_valor[]" id="estorno_valor_'.$financeiro_rel_id.'" value="" style="width:150px;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" /></td>';
			$total+=$linha['financeiro_rel_ob_valor'];
			}	
		echo '</tr>';

		}
	echo '<tr><td colspan='.($tipo=='ns' ? 6 : 5).' align="right" >Total</td><td align="right" ><b>'.number_format($total, 2, ',', '.').'</b></td><td></td></tr>';
	echo '</table></td></tr>';
	
	}
	





if ($tipo=='ne'){
	$sql->adTabela('financeiro_estorno_ne');
	$sql->esqUnir('financeiro_ne', 'financeiro_ne', 'financeiro_estorno_ne_ne=financeiro_ne_id');
	$sql->esqUnir('financeiro_estorno', 'financeiro_estorno', 'financeiro_estorno_id=financeiro_estorno_ne_estorno');
	$sql->adOnde('financeiro_estorno_acao ='.(int)$plano_acao_id);
	$sql->adCampo('financeiro_estorno_ne.*, financeiro_ne.*, financeiro_estorno.*');
	$sql->adCampo('formatar_data(financeiro_estorno_data, \'%d/%m/%Y\') AS data');
	$sql->adOrdem('financeiro_estorno_data');
	$estornos=$sql->ListaChave('financeiro_estorno_ne_id');
	$sql->limpar();
	}
elseif ($tipo=='ns'){
	$sql->adTabela('financeiro_estorno_ns');
	$sql->esqUnir('financeiro_ns', 'financeiro_ns', 'financeiro_estorno_ns_ns=financeiro_ns_id');
	$sql->esqUnir('financeiro_estorno', 'financeiro_estorno', 'financeiro_estorno_id=financeiro_estorno_ns_estorno');
	$sql->adOnde('financeiro_estorno_acao ='.(int)$plano_acao_id);
	$sql->adCampo('financeiro_estorno_ns.*, financeiro_ns.*, financeiro_estorno.*');
	$sql->adCampo('formatar_data(financeiro_estorno_data, \'%d/%m/%Y\') AS data');
	$sql->adOrdem('financeiro_estorno_data');
	$estornos=$sql->ListaChave('financeiro_estorno_ns_id');
	$sql->limpar();
	}
else {
	$sql->adTabela('financeiro_estorno_ob');
	$sql->esqUnir('financeiro_ob', 'financeiro_ob', 'financeiro_estorno_ob_ob=financeiro_ob_id');
	$sql->esqUnir('financeiro_estorno', 'financeiro_estorno', 'financeiro_estorno_id=financeiro_estorno_ob_estorno');
	$sql->adOnde('financeiro_estorno_acao ='.(int)$plano_acao_id);
	$sql->adCampo('financeiro_estorno_ob.*, financeiro_ob.*, financeiro_estorno.*');
	$sql->adCampo('formatar_data(financeiro_estorno_data, \'%d/%m/%Y\') AS data');
	$sql->adOrdem('financeiro_estorno_data');
	$estornos=$sql->ListaChave('financeiro_estorno_ob_id');
	$sql->limpar();
	}	


echo '<tr><td colspan=20 align=center><b>Estornos</b></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 class="tbl1" align=left width="100%">';

echo '<tr>
<th width=90>Número</th>
<th width=70>Data</th>
<th width=120>Responsável</th>
<th>Justificativa</th>
<th width=74>Valor</th>
<th width=16></th>
</tr>';
$total=0;	

$icone_cancelar=imagem('icones/retroceder.gif','Cancelar','Clique neste ícone '.imagem('icones/retroceder.gif').' para cancelar o estorno.');

$qnt=0;

foreach ($estornos as $estorno_rel_id => $linha) {
	echo '<tr align="center">';
	if ($tipo=='ne'){
		echo '<td width=50 style="white-space: nowrap"><a href="javascript: void(0);" onclick="popNE('.$linha['financeiro_ne_id'].')">'.substr($linha['NUMR_EMP'], 0, 5).'.'.substr($linha['NUMR_EMP'], 5, 4).'.'.substr($linha['NUMR_EMP'], 9, 2).'.'.substr($linha['NUMR_EMP'], 11, 6).'-'.substr($linha['NUMR_EMP'], 17, 1).'</a></td>';
		echo '<td width=50 style="white-space: nowrap">'.$linha['data'].'</td>';
		echo '<td td width=50 align=left>'.link_usuario($linha['financeiro_estorno_responsavel'], '','','esquerda').'</td>';	
		echo '<td align=left>'.$linha['financeiro_estorno_justificativa'].'</td>';
		echo '<td align="right" width=50 style="white-space: nowrap">'.number_format($linha['financeiro_estorno_ne_valor'], 2, ',', '.').'</td>';
		$total+=$linha['financeiro_estorno_ne_valor'];
		}
	elseif ($tipo=='ns'){
		echo '<td width=50 style="white-space: nowrap"><a href="javascript: void(0);" onclick="popNS('.$linha['financeiro_ns_id'].')">'.substr($linha['NUMR_EMP'], 0, 5).'.'.substr($linha['NUMR_EMP'], 5, 4).'.'.substr($linha['NUMR_EMP'], 9, 2).'.'.substr($linha['NUMR_EMP'], 11, 6).'-'.substr($linha['NUMR_EMP'], 17, 1).'</a></td>';
		echo '<td width=50 style="white-space: nowrap">'.$linha['data'].'</td>';
		echo '<td td width=50 align=left>'.link_usuario($linha['financeiro_estorno_responsavel'], '','','esquerda').'</td>';	
		echo '<td align=left>'.$linha['financeiro_estorno_justificativa'].'</td>';
		echo '<td align="right" width=50 style="white-space: nowrap">'.number_format($linha['financeiro_estorno_ns_valor'], 2, ',', '.').'</td>';
		$total+=$linha['financeiro_estorno_ns_valor'];
		}	
	else {
		echo '<td width=50 style="white-space: nowrap"><a href="javascript: void(0);" onclick="popOB('.$linha['financeiro_ob_id'].')">'.substr($linha['NUMR_EMP'], 0, 5).'.'.substr($linha['NUMR_EMP'], 5, 4).'.'.substr($linha['NUMR_EMP'], 9, 2).'.'.substr($linha['NUMR_EMP'], 11, 6).'-'.substr($linha['NUMR_EMP'], 17, 1).'</a></td>';
		echo '<td width=50 style="white-space: nowrap">'.$linha['data'].'</td>';
		echo '<td width=50 align=left>'.link_usuario($linha['financeiro_estorno_responsavel'], '','','esquerda').'</td>';	
		echo '<td align=left>'.$linha['financeiro_estorno_justificativa'].'</td>';
		echo '<td align="right" width=50 style="white-space: nowrap">'.number_format($linha['financeiro_estorno_ob_valor'], 2, ',', '.').'</td>';
		$total+=$linha['financeiro_estorno_ob_valor'];
		}	
	echo '<td align=center><a href="javascript:void(0);" onclick="desfazer('.$estorno_rel_id.')">'.$icone_cancelar.'</a></td>';	
	echo '</tr>';
	$qnt++;
	}
if ($qnt) echo '<tr><td colspan=4 align="right" >Total</td><td align="right" ><b>'.number_format($total, 2, ',', '.').'</b></td><td></td></tr>';
else echo '<tr><td colspan=20 align="left">Não há nenhum estorno</td></tr>';
echo '</table></td></tr>';


if (count($financeiro_rel)) echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('estornar', 'Estornar', 'Estornar os valores definidos.','','salvarDados();').'</td><td align="right">'.botao('retornar', 'Retornar', 'Retornar aos detalhes d'.$config['genero_acao'].' '.$config['acao'].'.','','if(confirm(\'Tem certeza que deseja retornar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';	
else echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 class="tbl1" align=left width="100%"><tr><td colspan=20 align="right">'.botao('retornar', 'Retornar', 'Retornar aos detalhes d'.$config['genero_acao'].' '.$config['acao'].'.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';	


echo '</table>';


echo estiloFundoCaixa();


echo '</form>'; 
?>
<script type="text/javascript">

function desfazer(estorno_rel_id){
	document.getElementById('desfazer').value=estorno_rel_id;
  env.submit();
	
	}


function popNE(financeiro_ne_id){
	window.parent.gpwebApp.popUp("Nota de Empenho", 950, 700, 'm=financeiro&a=siafi_ne_detalhe_pro&dialogo=1&projeto_id='+<?php echo $projeto_id?>+'&plano_acao_id='+<?php echo $plano_acao_id?>+'&tr_id='+<?php echo $tr_id?>+'&instrumento_id='+<?php echo $instrumento_id?>+'&os_id='+<?php echo $os_id?>+'&financeiro_ne_id='+financeiro_ne_id, null, window);
	}

function popNS(financeiro_ns_id){
	window.parent.gpwebApp.popUp("Nota de Liquidação", 950, 700, 'm=financeiro&a=siafi_ns_detalhe_pro&dialogo=1&projeto_id='+<?php echo $projeto_id?>+'&plano_acao_id='+<?php echo $plano_acao_id?>+'&tr_id='+<?php echo $tr_id?>+'&instrumento_id='+<?php echo $instrumento_id?>+'&os_id='+<?php echo $os_id?>+'&financeiro_ns_id='+financeiro_ns_id, null, window);
	}	
	
function popOB(financeiro_ob_id){
	window.parent.gpwebApp.popUp("Ordem Bancária", 950, 700, 'm=financeiro&a=siafi_ob_detalhe_pro&dialogo=1&projeto_id='+<?php echo $projeto_id?>+'&plano_acao_id='+<?php echo $plano_acao_id?>+'&tr_id='+<?php echo $tr_id?>+'&instrumento_id='+<?php echo $instrumento_id?>+'&os_id='+<?php echo $os_id?>+'&financeiro_ob_id='+financeiro_ob_id, null, window);
	}	
	
function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 39 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}	
	
function salvarDados(){
	//verificar se tem valores superiores
	
	

  with(document.getElementById('env')) {
  	var qnt=0;
  /*
  	var vetor_id[];
  	var vetor_antigo[];
  	var vetor_novo[];
  	*/
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			if (thiselm.name=='estorno_valor[]') {
				
				//pegar id
				var str=thiselm.id;
				var ident = str.substring(14);

				//checar se valor não é maior
				if (document.getElementById('estorno_valor_'+ident).value!='' && (moeda2float(document.getElementById('estorno_valor_'+ident).value) > document.getElementById('valor_'+ident).value)){
					alert('Não pode ser um valor ('+document.getElementById('estorno_valor_'+ident).value+') superior ao alocado!');
					document.getElementById('estorno_valor_'+ident).focus();
					return
					} 
	
				//acrescentar num vetor as alterações	
				if (document.getElementById('estorno_valor_'+ident).value!='') {
					/*
					vetor_id.push(ident);
					vetor_antigo.push(document.getElementById('valor_'+ident).value);
					vetor_novo.push(moeda2float(document.getElementById('estorno_valor_'+ident).value));
					*/
					
					document.getElementById('retorno_id').value=document.getElementById('retorno_id').value+(qnt ? ':' : '')+ident;
					document.getElementById('retorno_valor').value=document.getElementById('retorno_valor').value+(qnt ? ':' : '')+moeda2float(document.getElementById('estorno_valor_'+ident).value);
					document.getElementById('retorno_antigo').value=document.getElementById('retorno_antigo').value+(qnt ? ':' : '')+document.getElementById('valor_'+ident).value;
					document.getElementById('retorno_objeto').value=document.getElementById('retorno_objeto').value+(qnt ? ':' : '')+document.getElementById('id_objeto_'+ident).value;
					qnt++;
					}
				}	
      }
    
    if (qnt) {
    	document.getElementById('processar').value=1;
    	env.submit();
    	}
    else alert('Nenhum item processado');	
    }

	}	
</script>                