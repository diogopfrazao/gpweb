<?php
global $config, $bd;


if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
  $resultado = $bd->Execute("SHOW COLUMNS FROM problema_gestao LIKE 'problema_gestao_arquivo'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD KEY problema_gestao_arquivo (problema_gestao_arquivo);");
    $bd->Execute("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_arquivo FOREIGN KEY (problema_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM problema_gestao LIKE 'problema_gestao_agenda'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
    $bd->Execute("ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL;");
    $bd->Execute("ALTER TABLE problema_gestao ADD KEY problema_gestao_agenda (problema_gestao_agenda);");
    $bd->Execute("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_agenda FOREIGN KEY (problema_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}
	}
?>