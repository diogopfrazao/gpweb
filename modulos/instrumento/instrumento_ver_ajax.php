<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);


function mudar_tipo($instrumento_id=null, $instrumento_campo=null){
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_campo', $instrumento_campo);
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$sql->exec();
	$sql->limpar();
	}
$xajax->registerFunction("mudar_tipo");


function mudar_percentagem($instrumento_id=null, $instrumento_porcentagem=null){
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_porcentagem', $instrumento_porcentagem);
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$sql->exec();
	$sql->limpar();
	}
$xajax->registerFunction("mudar_percentagem");

$cor = getSisValor('SituacaoInstrumentoCor');

function mudar_status($instrumento_id=null, $instrumento_situacao=null){
	global $cor;
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_situacao', $instrumento_situacao);
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$sql->exec();
	$sql->limpar();
	if (isset($cor[$instrumento_situacao])){
		$objResposta = new xajaxResponse();
		$objResposta->assign('status_'.$instrumento_id,'style.backgroundColor', '#'.$cor[$instrumento_situacao]);
		return $objResposta;
		}
	}
$xajax->registerFunction("mudar_status");


function criar_instrumento($nome='', $instrumento_id_antigo=null){
	global $bd;
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	
	$sql->adTabela('instrumento');
	$sql->adCampo('instrumento.*');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id_antigo);
  $antigo = $sql->linha();
  $sql->limpar();
  
  
  //checar se tem assinatura que aprova
  $sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_instrumento = '.(int)$instrumento_id_antigo);
	$sql->adOnde('assinatura_aprova=1');
  $tem_aprovacao=$sql->resultado();
  $sql->limpar();
  
	$sql->adTabela('instrumento');
	foreach($antigo as $campo => $valor)	if ($campo!='instrumento_id' && $campo!='instrumento_nome' && $campo!='instrumento_porcentagem' && $campo!='instrumento_aprovado' && $campo!='instrumento_situacao') $sql->adInserir($campo, $valor);
	$sql->adInserir('instrumento_nome', $nome);
	$sql->adInserir('instrumento_situacao', 0);
	$sql->adInserir('instrumento_porcentagem', 0);
	$sql->adInserir('instrumento_aprovado', ($tem_aprovacao ? 0 : 1));
	$sql->exec();
	$instrumento_id=$bd->Insert_ID('instrumento','instrumento_id');
	$sql->limpar();
	
	$sql->adTabela('instrumento_avulso_custo');
	$sql->adCampo('instrumento_avulso_custo.*');
	$sql->adOnde('instrumento_avulso_custo_instrumento = '.(int)$instrumento_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
  
	foreach($lista as $linha){
		$sql->adTabela('instrumento_avulso_custo');
		foreach($linha as $campo => $valor)	if ($campo!='instrumento_avulso_custo_id' && $campo!='instrumento_avulso_custo_instrumento') $sql->adInserir($campo, $valor);
		$sql->adInserir('instrumento_avulso_custo_instrumento', $instrumento_id);
		$sql->exec();
		$instrumento_avulso_custo_id=$bd->Insert_ID('instrumento_avulso_custo','instrumento_avulso_custo_id');
		$sql->limpar();
		
		$sql->adTabela('instrumento_custo');
		$sql->adInserir('instrumento_custo_instrumento', $instrumento_id);
		$sql->adInserir('instrumento_custo_avulso', $instrumento_avulso_custo_id);
		$sql->adInserir('instrumento_custo_quantidade', $linha['instrumento_avulso_custo_quantidade']);
		$sql->adInserir('instrumento_custo_ordem', $linha['instrumento_avulso_custo_ordem']);
		$sql->exec();
		$sql->limpar();
		}
		
		
	$sql->adTabela('instrumento_cia');
	$sql->adCampo('instrumento_cia_cia');
	$sql->adOnde('instrumento_cia_instrumento = '.(int)$instrumento_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
  
	foreach($lista as $valor){
		$sql->adTabela('instrumento_cia');
		$sql->adInserir('instrumento_cia_cia', $valor);
		$sql->adInserir('instrumento_cia_instrumento', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}	
		
	$sql->adTabela('instrumento_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
  
	foreach($lista as $valor){
		$sql->adTabela('instrumento_depts');
		$sql->adInserir('dept_id', $valor);
		$sql->adInserir('instrumento_id', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}	

	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao.*');
	$sql->adOnde('instrumento_gestao_instrumento = '.(int)$instrumento_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
  
	foreach($lista as $linha){
		$sql->adTabela('instrumento_gestao');
		foreach($linha as $campo => $valor)	if ($campo!='instrumento_gestao_id' && $campo!='instrumento_gestao_instrumento') $sql->adInserir($campo, $valor);
		$sql->adInserir('instrumento_gestao_instrumento', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}


	$sql->adTabela('instrumento_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
  
	foreach($lista as $valor){
		$sql->adTabela('instrumento_designados');
		$sql->adInserir('usuario_id', $valor);
		$sql->adInserir('instrumento_id', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}	


	$sql->adTabela('instrumento_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
  
	foreach($lista as $valor){
		$sql->adTabela('instrumento_contatos');
		$sql->adInserir('contato_id', $valor);
		$sql->adInserir('instrumento_id', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}	

	$sql->adTabela('instrumento_recursos');
	$sql->adCampo('recurso_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
  
	foreach($lista as $valor){
		$sql->adTabela('instrumento_recursos');
		$sql->adInserir('recurso_id', $valor);
		$sql->adInserir('instrumento_id', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}	



	$sql->adTabela('instrumento_financeiro');
	$sql->adCampo('instrumento_financeiro.*');
	$sql->adOnde('instrumento_financeiro_instrumento = '.(int)$instrumento_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
  
	foreach($lista as $linha){
		$sql->adTabela('instrumento_financeiro');
		foreach($linha as $campo => $valor)	if ($campo!='instrumento_financeiro_id' && $campo!='instrumento_financeiro_instrumento') $sql->adInserir($campo, $valor);
		$sql->adInserir('instrumento_financeiro_instrumento', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}

	
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura.*');
	$sql->adOnde('assinatura_instrumento = '.(int)$instrumento_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
  
	foreach($lista as $linha){
		$sql->adTabela('assinatura');
		foreach($linha as $campo => $valor)	if ($campo!='assinatura_id' && $campo!='assinatura_instrumento' && $campo!='assinatura_data' && $campo!='assinatura_aprovou' && $campo!='assinatura_observacao' && $campo!='assinatura_atesta_opcao') $sql->adInserir($campo, $valor);
		$sql->adInserir('assinatura_instrumento', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$sql->adTabela('priorizacao');
	$sql->adCampo('priorizacao.*');
	$sql->adOnde('priorizacao_instrumento = '.(int)$instrumento_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
  
	foreach($lista as $linha){
		$sql->adTabela('priorizacao');
		foreach($linha as $campo => $valor)	if ($campo!='priorizacao_id' && $campo!='priorizacao_instrumento') $sql->adInserir($campo, $valor);
		$sql->adInserir('priorizacao_instrumento', $instrumento_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("instrumento_criado","value", $instrumento_id);
	return $objResposta;
	}
$xajax->registerFunction("criar_instrumento");

function instrumento_existe($nome='', $instrumento_id=null, $cia_id=null){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adCampo('count(instrumento_id)');
	$sql->adOnde('instrumento_nome = \''.$nome.'\'');
	if ($cia_id) $sql->adOnde('instrumento_cia='.(int)$cia_id);
	if ($instrumento_id) $sql->adOnde('instrumento_id!='.(int)$instrumento_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_instrumento","value", $existe);
	return $objResposta;
	}
$xajax->registerFunction("instrumento_existe");


$xajax->processRequest();
?>