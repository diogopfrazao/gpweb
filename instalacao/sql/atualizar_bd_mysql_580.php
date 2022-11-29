<?php
global $config, $bd;

$resultado = $bd->Execute("SHOW COLUMNS FROM financeiro_estorno_ne_fiplan LIKE 'NUMR_ESTORNO_EMP'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if($existe) $bd->Execute("ALTER TABLE financeiro_estorno_ne_fiplan CHANGE NUMR_ESTORNO_EMP NUMR_EMP_ESTORNO VARCHAR(18) DEFAULT NULL;");


$resultado = $bd->Execute("SHOW COLUMNS FROM financeiro_estorno_ne_fiplan LIKE 'NOME_ORDENADOR'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if($existe) $bd->Execute("ALTER TABLE financeiro_estorno_ne_fiplan CHANGE NOME_ORDENADOR NOME_ORDENADOR_DESPESA VARCHAR(100) DEFAULT NULL;");


?>