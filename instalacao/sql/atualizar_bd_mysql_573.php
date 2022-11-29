<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('projetos');	
	$sql->adCampo('projeto_id, projeto_programa_financeiro');
	$sql->adOnde('projeto_programa_financeiro IS NOT NULL');
	$linhas=$sql->Lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		$sql->adTabela('projeto_programa');
		$sql->adInserir('projeto_programa_projeto', $linha['projeto_id']);
		$sql->adInserir('projeto_programa_programa', $linha['projeto_programa_financeiro']);
		$sql->adInserir('projeto_programa_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}
	}
?>