<?php
global $config;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('licao');	
	$sql->adCampo('licao_projeto, licao_id');
	$sql->adOnde('licao_projeto > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('licao_gestao');
		$sql->adInserir('licao_gestao_licao', $linha['licao_id']);
		$sql->adInserir('licao_gestao_projeto', $linha['licao_projeto']);
		$sql->exec();
		$sql->limpar();
		}
	}
?>