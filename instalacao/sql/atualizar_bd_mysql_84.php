<?php
global $config, $bd;


$bd->Execute("CREATE FUNCTION diferenca_tempo(t1 time, t2 time)
RETURNS time
DETERMINISTIC
BEGIN
RETURN TIMEDIFF(t1, t2);
END;");

?>
