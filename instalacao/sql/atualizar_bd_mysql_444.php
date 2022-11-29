<?php
global $config, $bd;


if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
  $resultado = $bd->Execute("SHOW COLUMNS FROM problema_gestao LIKE 'problema_gestao_patrocinador'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD KEY problema_gestao_patrocinador (problema_gestao_patrocinador);");
    $bd->Execute("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_patrocinador FOREIGN KEY (problema_gestao_patrocinador) REFERENCES patrocinador (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM problema_gestao LIKE 'problema_gestao_agenda'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD KEY problema_gestao_agenda (problema_gestao_agenda);");
    $bd->Execute("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_agenda FOREIGN KEY (problema_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM problema_gestao LIKE 'problema_gestao_agrupamento'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD KEY problema_gestao_agrupamento (problema_gestao_agrupamento);");
    $bd->Execute("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_agrupamento FOREIGN KEY (problema_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}	
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM problema_gestao LIKE 'problema_gestao_template'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD KEY problema_gestao_template (problema_gestao_template);");
    $bd->Execute("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_template FOREIGN KEY (problema_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}		
		
	}
?>