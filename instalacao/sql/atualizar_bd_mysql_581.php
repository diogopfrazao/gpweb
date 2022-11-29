<?php
global $config, $bd;

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_folha_ponto_arquivo LIKE 'folha_ponto_arquivo_nome_real'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_folha_ponto_arquivo ADD COLUMN folha_ponto_arquivo_nome_real VARCHAR(255) DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_folha_ponto_arquivo LIKE 'folha_ponto_arquivo_local'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_folha_ponto_arquivo ADD COLUMN folha_ponto_arquivo_local VARCHAR(255) DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_folha_ponto_arquivo LIKE 'folha_ponto_arquivo_tamanho'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_folha_ponto_arquivo ADD COLUMN folha_ponto_arquivo_tamanho INT(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_priorizacao LIKE 'priorizacao_os'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_priorizacao ADD COLUMN priorizacao_os INT(100) UNSIGNED DEFAULT NULL;");

$resultado = $bd->Execute("SHOW COLUMNS FROM baseline_eventos LIKE 'evento_superior'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE baseline_eventos ADD COLUMN evento_superior INT(100) UNSIGNED DEFAULT NULL;");

?>