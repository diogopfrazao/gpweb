<?php
global $config, $bd;

$resultado = $bd->Execute("SHOW COLUMNS FROM projeto_gestao LIKE 'projeto_gestao_tarefa'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) {
	$bd->Execute("ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL;");
	$bd->Execute("ALTER TABLE projeto_gestao ADD KEY projeto_gestao_tarefa (projeto_gestao_tarefa);");
	$bd->Execute("ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_tarefa FOREIGN KEY (projeto_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;");
	}

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_projeto_gestao LIKE 'projeto_gestao_tarefa'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL;");


?>