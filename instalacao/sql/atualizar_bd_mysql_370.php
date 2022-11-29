<?php
global $config, $bd;


$bd->Execute("DROP PROCEDURE IF EXISTS PROC_DROP_FOREIGN_KEY;");

$bd->Execute("CREATE PROCEDURE PROC_DROP_FOREIGN_KEY(IN tableName VARCHAR(64) CHARSET latin1, IN constraintName VARCHAR(64) CHARSET latin1)
BEGIN
  IF EXISTS(
    SELECT * FROM information_schema.table_constraints
    WHERE
        table_schema    = DATABASE()     AND
        table_name      = tableName      AND
        constraint_name = constraintName AND
        constraint_type = 'FOREIGN KEY')
  THEN
    SET @query = CONCAT('ALTER TABLE ', tableName, ' DROP FOREIGN KEY ', constraintName, ';');
    PREPARE stmt FROM @query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END;");


$bd->Execute("DROP PROCEDURE IF EXISTS PROC_DROP_KEY;");

$bd->Execute("CREATE PROCEDURE PROC_DROP_KEY(IN tableName VARCHAR(64) CHARSET latin1, IN constraintName VARCHAR(64) CHARSET latin1)
BEGIN
  IF EXISTS(
    SELECT * FROM information_schema.table_constraints
    WHERE
        table_schema    = DATABASE()     AND
        table_name      = tableName      AND
        constraint_name = constraintName AND
        constraint_type = 'FOREIGN KEY')
  THEN
    SET @query = CONCAT('ALTER TABLE ', tableName, ' DROP KEY ', constraintName, ';');
    PREPARE stmt FROM @query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END;");