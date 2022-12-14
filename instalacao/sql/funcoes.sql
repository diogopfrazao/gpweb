DELIMITER |
CREATE FUNCTION extrair(t1 varchar(50) CHARSET latin1, t2 datetime)
RETURNS integer(100)
DETERMINISTIC
BEGIN
IF (t1='SECOND') THEN RETURN EXTRACT(SECOND FROM t2);
ELSEIF (t1='MINUTE') THEN RETURN EXTRACT(MINUTE FROM t2);
ELSEIF (t1='HOUR') THEN RETURN EXTRACT(HOUR FROM t2);
ELSEIF (t1='DAY') THEN RETURN EXTRACT(DAY FROM t2);
ELSEIF (t1='WEEK') THEN RETURN EXTRACT(WEEK FROM t2);
ELSEIF (t1='MONTH') THEN RETURN EXTRACT(MONTH FROM t2);
ELSEIF (t1='QUARTER') THEN RETURN EXTRACT(QUARTER FROM t2);
ELSEIF (t1='YEAR') THEN RETURN EXTRACT(YEAR FROM t2);
ELSEIF (t1='HOUR_MINUTE') THEN RETURN EXTRACT(HOUR_MINUTE FROM t2);
END IF;
END|

DELIMITER |
CREATE FUNCTION tempo_unix(t1 datetime)
RETURNS integer(100)
DETERMINISTIC
BEGIN
IF (t1) THEN RETURN UNIX_TIMESTAMP(t1);
ELSE RETURN UNIX_TIMESTAMP();
END IF;
END|

DELIMITER |
CREATE FUNCTION em_dias(t1 datetime)
RETURNS integer(100)
DETERMINISTIC
BEGIN
RETURN TO_DAYS(t1);
END|

DELIMITER |
CREATE FUNCTION dia(t1 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN DAY(t1);
END|

DELIMITER |
CREATE FUNCTION semana_ano(t1 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN WEEKOFYEAR(t1);
END|

DELIMITER |
CREATE FUNCTION ano(t1 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN YEAR(t1);
END|

DELIMITER |
CREATE FUNCTION mes(t1 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN MONTH(t1);
END|

DELIMITER |
CREATE FUNCTION dia_semana(t1 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN WEEKDAY(t1);
END|

DELIMITER |
CREATE FUNCTION adiciona_data(t1 datetime, t2 float(100,3), t3 varchar(50) CHARSET latin1)
RETURNS datetime
DETERMINISTIC
BEGIN
IF (t3='SECOND') THEN RETURN TIMESTAMPADD(SECOND,t2,t1);
ELSEIF (t3='MINUTE') THEN RETURN TIMESTAMPADD(MINUTE,t2,t1);
ELSEIF (t3='HOUR') THEN RETURN TIMESTAMPADD(HOUR,t2,t1);
ELSEIF (t3='DAY') THEN RETURN TIMESTAMPADD(DAY,t2,t1);
ELSEIF (t3='WEEK') THEN RETURN TIMESTAMPADD(WEEK,t2,t1);
ELSEIF (t3='MONTH') THEN RETURN TIMESTAMPADD(MONTH,t2,t1);
ELSEIF (t3='QUARTER') THEN RETURN TIMESTAMPADD(QUARTER,t2,t1);
ELSEIF (t3='YEAR') THEN RETURN TIMESTAMPADD(YEAR,t2,t1);
END IF;
END|

DELIMITER |
CREATE FUNCTION diferenca_data(t1 datetime, t2 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN DATEDIFF(t1, t2);
END|

DELIMITER |
CREATE FUNCTION diferenca_tempo(t1 time, t2 time)
RETURNS time
DETERMINISTIC
BEGIN
RETURN TIMEDIFF(t1, t2);
END|

DELIMITER |
CREATE FUNCTION tamanho_caractere(t1 text CHARSET latin1)
RETURNS int(100)
DETERMINISTIC
BEGIN
RETURN CHAR_LENGTH(t1);
END|

DELIMITER |
CREATE FUNCTION tempo_em_segundos(t1 time)
RETURNS integer(100)
DETERMINISTIC
BEGIN
RETURN TIME_TO_SEC(t1);
END|

DELIMITER |
CREATE FUNCTION formatar_data(t1 datetime, t2 varchar(255) CHARSET latin1)
RETURNS varchar(255) CHARSET latin1
DETERMINISTIC
BEGIN
return DATE_FORMAT(t1, t2);
END|

DELIMITER |
CREATE FUNCTION concatenar_dois(t1 varchar(255) CHARSET latin1, t2 varchar(255) CHARSET latin1)
RETURNS text CHARSET latin1
DETERMINISTIC
BEGIN
RETURN CONCAT(t1, t2);
END|

DELIMITER |
CREATE FUNCTION concatenar_tres(t1 varchar(255) CHARSET latin1, t2 varchar(255) CHARSET latin1, t3 varchar(255) CHARSET latin1)
RETURNS text CHARSET latin1
DETERMINISTIC
BEGIN
RETURN CONCAT(t1, t2, t3);
END|

DELIMITER |
CREATE FUNCTION concatenar_quatro(t1 varchar(255) CHARSET latin1, t2 varchar(255) CHARSET latin1, t3 varchar(255) CHARSET latin1, t4 varchar(255)CHARSET latin1)
RETURNS text CHARSET latin1
DETERMINISTIC
BEGIN
RETURN CONCAT(t1, t2, t3, t4);
END|

DELIMITER |
CREATE FUNCTION concatenar_cinco(t1 varchar(255) CHARSET latin1, t2 varchar(255) CHARSET latin1, t3 varchar(255) CHARSET latin1, t4 varchar(255) CHARSET latin1, t5 varchar(255) CHARSET latin1)
RETURNS text CHARSET latin1
DETERMINISTIC
BEGIN
RETURN CONCAT(t1, t2, t3, t4, t5);
END|

DELIMITER |
CREATE FUNCTION strmes(t1 datetime)
RETURNS VARCHAR(2)
DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
RETURN LPAD(MONTH(t1),2,'0');
END|

DELIMITER |
CREATE PROCEDURE PROC_DROP_FOREIGN_KEY(IN tableName VARCHAR(64) CHARSET latin1, IN constraintName VARCHAR(64) CHARSET latin1)
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
END|


DELIMITER |
CREATE PROCEDURE PROC_DROP_KEY(IN tableName VARCHAR(64) CHARSET latin1, IN constraintName VARCHAR(64) CHARSET latin1)
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
END|


DELIMITER |
CREATE PROCEDURE PROC_DROP_COLUMN(IN tableName VARCHAR(64) CHARSET latin1, IN columnname VARCHAR(64) CHARSET latin1)
BEGIN
  IF EXISTS(
    SELECT * FROM information_schema.columns
    WHERE 
        table_schema    = DATABASE()     AND
        table_name      = tableName      AND
        column_name = columnname)
  THEN
    SET @query = CONCAT('ALTER TABLE ', tableName, ' DROP COLUMN ', columnname, ';');
    PREPARE stmt FROM @query; 
    EXECUTE stmt; 
    DEALLOCATE PREPARE stmt; 
  END IF; 
END|
