<?php
global $config, $bd;


if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$resultado = $bd->Execute("SHOW COLUMNS FROM arquivo_pasta_gestao LIKE 'arquivo_pasta_gestao_semelhante'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_semelhante (arquivo_pasta_gestao_semelhante);");
    $bd->Execute("ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_semelhante FOREIGN KEY (arquivo_pasta_gestao_semelhante) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE SET NULL ON UPDATE CASCADE;");
		}
	}
?>