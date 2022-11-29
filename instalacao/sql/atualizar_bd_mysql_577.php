<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('projetos');	
	$sql->adCampo('projeto_id, projeto_convenio');
	$sql->adOnde('projeto_convenio IS NOT NULL');
	$linhas=$sql->Lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		$sql->adTabela('projeto_convenio');
		$sql->adInserir('projeto_convenio_projeto', $linha['projeto_id']);
		$sql->adInserir('projeto_convenio_convenio', $linha['projeto_convenio']);
		$sql->adInserir('projeto_convenio_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}
	}
?>