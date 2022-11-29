<?php
global $config, $bd;
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
$Aplic = new CAplic();
include_once BASE_DIR.'/classes/aplic.class.php';
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/admin/admin.class.php';
require_once BASE_DIR.'/modulos/sistema/perfis/perfis.class.php';


require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

$sql = new BDConsulta;

$sql->adTabela('plano_acao_item');	
$sql->adCampo('plano_acao_item_id, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_item_cia');
$sql->adOnde('plano_acao_item_inicio IS NOT NULL');
$sql->adOnde('plano_acao_item_fim IS NOT NULL');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	$horas = horas_periodo($linha['plano_acao_item_inicio'], $linha['plano_acao_item_fim'], $linha['plano_acao_item_cia']);
	
	$duracao=$horas/(isset($config['horas_trab_diario']) && $config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_duracao', $duracao);
	$sql->adOnde('plano_acao_item_id='.(int)$linha['plano_acao_item_id']);
	$sql->exec();
	$sql->limpar();

	}
?>