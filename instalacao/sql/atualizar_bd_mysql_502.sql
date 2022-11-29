SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.17';
UPDATE versao SET ultima_atualizacao_bd='2018-07-12';
UPDATE versao SET ultima_atualizacao_codigo='2018-07-12';
UPDATE versao SET versao_bd=502;


ALTER TABLE log ADD COLUMN log_tipo INTEGER(10) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_log ADD COLUMN log_tipo INTEGER(10) UNSIGNED DEFAULT 0;


DELETE FROM sisvalores WHERE sisvalor_titulo='logTipo';		
INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('logTipo','RAP CPORT','1',NULL),
	('logTipo','PC','2',NULL);
	
	
	