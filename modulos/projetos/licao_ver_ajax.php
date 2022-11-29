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

function criar_licao($nome='', $licao_id_antigo=null){
	global $bd;
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	
	 //checar se tem assinatura que aprova
  $sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_licao = '.(int)$licao_id_antigo);
	$sql->adOnde('assinatura_aprova=1');
  $tem_aprovacao=$sql->resultado();
  $sql->limpar();
	
	
	$sql->adTabela('licao');
	$sql->adCampo('licao.*');
	$sql->adOnde('licao_id = '.(int)$licao_id_antigo);
  $antigo = $sql->linha();
  $sql->limpar();
	$sql->adTabela('licao');
	foreach($antigo as $campo => $valor)	if ($campo!='licao_id' && $campo!='licao_nome' && $campo!='licao_aprovado') $sql->adInserir($campo, $valor);
	$sql->adInserir('licao_nome', $nome);
	$sql->adInserir('licao_aprovado', ($tem_aprovacao ? 0 : 1));
	$sql->exec();
	$licao_id=$bd->Insert_ID('licao','licao_id');
	$sql->limpar();
	
	$sql->adTabela('licao_cia');
	$sql->adCampo('licao_cia_cia');
	$sql->adOnde('licao_cia_licao = '.(int)$licao_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
	foreach($lista as $valor){
		$sql->adTabela('licao_cia');
		$sql->adInserir('licao_cia_cia', $valor);
		$sql->adInserir('licao_cia_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}	
		
	$sql->adTabela('licao_dept');
	$sql->adCampo('licao_dept_dept');
	$sql->adOnde('licao_dept_licao = '.(int)$licao_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
	foreach($lista as $valor){
		$sql->adTabela('licao_dept');
		$sql->adInserir('licao_dept_dept', $valor);
		$sql->adInserir('licao_dept_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}	
	
	$sql->adTabela('licao_gestao');
	$sql->adCampo('licao_gestao.*');
	$sql->adOnde('licao_gestao_licao = '.(int)$licao_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
	foreach($lista as $linha){
		$sql->adTabela('licao_gestao');
		foreach($linha as $campo => $valor)	if ($campo!='licao_gestao_id' && $campo!='licao_gestao_licao') $sql->adInserir($campo, $valor);
		$sql->adInserir('licao_gestao_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$sql->adTabela('licao_usuario');
	$sql->adCampo('licao_usuario_usuario');
	$sql->adOnde('licao_usuario_licao = '.(int)$licao_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
	foreach($lista as $valor){
		$sql->adTabela('licao_usuario');
		$sql->adInserir('licao_usuario_usuario', $valor);
		$sql->adInserir('licao_usuario_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}	
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura.*');
	$sql->adOnde('assinatura_licao = '.(int)$licao_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
	foreach($lista as $linha){
		$sql->adTabela('assinatura');
		foreach($linha as $campo => $valor)	if ($campo!='assinatura_id' && $campo!='assinatura_licao' && $campo!='assinatura_data' && $campo!='assinatura_aprovou' && $campo!='assinatura_observacao' && $campo!='assinatura_atesta_opcao') $sql->adInserir($campo, $valor);
		$sql->adInserir('assinatura_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$sql->adTabela('priorizacao');
	$sql->adCampo('priorizacao.*');
	$sql->adOnde('priorizacao_licao = '.(int)$licao_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
	foreach($lista as $linha){
		$sql->adTabela('priorizacao');
		foreach($linha as $campo => $valor)	if ($campo!='priorizacao_id' && $campo!='priorizacao_licao') $sql->adInserir($campo, $valor);
		$sql->adInserir('priorizacao_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("licao_criado","value", $licao_id);
	return $objResposta;
	
	}
$xajax->registerFunction("criar_licao");


function licao_existe($nome='', $licao_id=null, $cia_id=null){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('licao');
	$sql->adCampo('count(licao_id)');
	$sql->adOnde('licao_nome = \''.$nome.'\'');
	if ($cia_id) $sql->adOnde('licao_cia='.(int)$cia_id);
	if ($licao_id) $sql->adOnde('licao_id!='.(int)$licao_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_licao","value", $existe);
	return $objResposta;
	}
$xajax->registerFunction("licao_existe");


$xajax->processRequest();

?>