<?php
global $config, $bd;



$resultado = $bd->Execute("SHOW COLUMNS FROM ssti LIKE 'ssti_classificacao'");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if(!$existe) $bd->Execute("ALTER TABLE ssti DROP COLUMN ssti_classificacao;");

?>