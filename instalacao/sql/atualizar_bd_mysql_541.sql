SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-01-11';
UPDATE versao SET ultima_atualizacao_codigo='2019-01-11';
UPDATE versao SET versao_bd=541;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_sem_meta TINYINT(1) DEFAULT 0;