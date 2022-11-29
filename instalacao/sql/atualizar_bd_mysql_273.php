<?php
global $config, $bd;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$resultado = $bd->Execute("SHOW COLUMNS FROM projeto_gestao LIKE 'uuid'");	
	if($resultado) $bd->Execute("ALTER TABLE projeto_gestao CHANGE uuid projeto_gestao_uuid VARCHAR(36) DEFAULT NULL;");
	}									
?>