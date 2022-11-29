<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);
	
	

function marcar($id, $valor=0){
	$sql = new BDConsulta;
	$sql->adTabela('campo_customizado');
	$sql->adAtualizar('campo_customizado_habilitado', ($valor ? 1 : 0));
 	$sql->adOnde('campo_customizado_id = '.(int)$id);
	$sql->exec();
  $sql->limpar();
	}
$xajax->registerFunction("marcar");	

$xajax->processRequest();
?>