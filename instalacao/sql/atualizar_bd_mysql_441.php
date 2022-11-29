<?php
global $config, $bd;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('preferencia_modulo');	
	$sql->adCampo('preferencia_modulo_id');
	$sql->adOnde('preferencia_modulo_arquivo=\'swot_lista\'');
	$existe = $sql->resultado();
	$sql->limpar();
	if($existe) {
		$sql->adTabela('preferencia_modulo');
		$sql->adInserir('preferencia_modulo_modulo', 'swot');
		$sql->adInserir('preferencia_modulo_arquivo', 'mswot_lista');
		$sql->adInserir('preferencia_modulo_descricao', 'Matrizes SWOT');
		$sql->exec();
		$sql->limpar();
		}			
	}
?>