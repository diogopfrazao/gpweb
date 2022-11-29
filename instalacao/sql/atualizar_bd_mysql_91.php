<?php
global $config, $bd;


$bd->Execute("DROP FUNCTION IF EXISTS diferenca_data;");

$bd->Execute("CREATE FUNCTION diferenca_data(t1 datetime, t2 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN DATEDIFF(t1, t2);
END;");
?>
