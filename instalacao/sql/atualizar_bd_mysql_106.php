<?php
global $config, $bd;


$bd->Execute("DROP FUNCTION IF EXISTS strmes;");

$bd->Execute("CREATE FUNCTION strmes(t1 datetime)
RETURNS VARCHAR(2)
DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
RETURN LPAD(MONTH(t1),2,'0');
END;");
?>
