SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-03-23';
UPDATE versao SET ultima_atualizacao_codigo='2020-03-23';
UPDATE versao SET versao_bd=552;

ALTER TABLE instrumento_campo CHANGE COLUMN instrumento_financero_projeto_leg instrumento_financeiro_projeto_leg VARCHAR(50) COLLATE latin1_swedish_ci DEFAULT NULL;