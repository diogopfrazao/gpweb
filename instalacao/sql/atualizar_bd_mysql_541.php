<?php
global $config, $bd;

$resultado = $bd->Execute("SHOW COLUMNS FROM pratica_indicador_gestao LIKE 'pratica_indicador_gestao_swot'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) {
	$bd->Execute("ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
	}
$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_pratica_indicador_gestao LIKE 'pratica_indicador_gestao_swot'");

$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) {
	$bd->Execute("ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
	}

?>