<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	require_once BASE_DIR.'/incluir/funcoes_principais.php';
	require_once BASE_DIR.'/incluir/db_adodb.php';
	require_once BASE_DIR.'/classes/BDConsulta.class.php';
	require_once BASE_DIR.'/classes/ui.class.php';
	
	
	include_once BASE_DIR.'/classes/aplic.class.php';
	
	$Aplic = new CAplic();
	
	require_once BASE_DIR.'/classes/data.class.php';
	require_once BASE_DIR.'/modulos/admin/admin.class.php';
	require_once BASE_DIR.'/modulos/sistema/perfis/perfis.class.php';
	
	
	require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
	require_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';

	$sql = new BDConsulta;
	$sql->adTabela('projeto_portfolio');
	$sql->adCampo('DISTINCT projeto_portfolio_pai');
	$lista = $sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $portfolio_atual){
		if ($portfolio_atual){
			portfolio_porcentagem($portfolio_atual);
			$portfolio = new CProjeto($portfolio_atual);
			$portfolio->projeto_id=$portfolio_atual;
		  $portfolio->disparo_observador('fisico', false);
			}	
		}
	
	}
?>