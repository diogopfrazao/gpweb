<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
			
	$sql->adTabela('instrumento');	
	$sql->adCampo('instrumento_id, instrumento_processo');
	$sql->adOnde('instrumento_processo IS NOT NULL');
	$linhas=$sql->Lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		$sql->adTabela('instrumento_processo');
		$sql->adInserir('instrumento_processo_instrumento', $linha['instrumento_id']);
		$sql->adInserir('instrumento_processo_processo', $linha['instrumento_processo']);
		$sql->adInserir('instrumento_processo_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}	
		
	$sql->adTabela('instrumento');	
	$sql->adCampo('instrumento_id, instrumento_edital_nr');
	$sql->adOnde('instrumento_edital_nr IS NOT NULL');
	$linhas=$sql->Lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		$sql->adTabela('instrumento_edital');
		$sql->adInserir('instrumento_edital_instrumento', $linha['instrumento_id']);
		$sql->adInserir('instrumento_edital_edital', $linha['instrumento_edital_nr']);
		$sql->adInserir('instrumento_edital_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}	
		
	
	
	$sql->adTabela('os');	
	$sql->adCampo('os_id, os_processo');
	$sql->adOnde('os_processo IS NOT NULL');
	$linhas=$sql->Lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		$sql->adTabela('os_processo');
		$sql->adInserir('os_processo_os', $linha['os_id']);
		$sql->adInserir('os_processo_processo', $linha['os_processo']);
		$sql->adInserir('os_processo_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}		
	}
?>