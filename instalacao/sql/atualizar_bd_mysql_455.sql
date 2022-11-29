SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.11';
UPDATE versao SET ultima_atualizacao_bd='2017-10-31';
UPDATE versao SET ultima_atualizacao_codigo='2017-10-31';
UPDATE versao SET versao_bd=455;

ALTER TABLE problema CHANGE problema_inicio problema_inicio DATETIME DEFAULT NULL;
ALTER TABLE problema CHANGE problema_fim problema_fim DATETIME DEFAULT NULL;
ALTER TABLE problema ADD COLUMN problema_duracao DECIMAL(20,5) UNSIGNED DEFAULT NULL;

ALTER TABLE eventos ADD COLUMN evento_duracao DECIMAL(20,5) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_eventos ADD COLUMN evento_duracao DECIMAL(20,5) UNSIGNED DEFAULT NULL;

ALTER TABLE agenda ADD COLUMN agenda_duracao DECIMAL(20,5) UNSIGNED DEFAULT NULL;