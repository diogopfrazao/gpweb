<?php
global $config, $bd;



$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_ssti'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_laudo'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_trelo'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_trelo_cartao'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_pdcl'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_pdcl_item'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;");
?>