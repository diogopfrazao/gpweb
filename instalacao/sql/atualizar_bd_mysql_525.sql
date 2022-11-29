SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.26';
UPDATE versao SET ultima_atualizacao_bd='2019-08-27';
UPDATE versao SET ultima_atualizacao_codigo='2019-08-27';
UPDATE versao SET versao_bd=525;

ALTER TABLE log CHANGE log_data log_data DATETIME DEFAULT NULL;
ALTER TABLE baseline_log CHANGE log_data log_data DATETIME DEFAULT NULL;