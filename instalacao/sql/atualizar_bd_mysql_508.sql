SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.19';
UPDATE versao SET ultima_atualizacao_bd='2018-08-27';
UPDATE versao SET ultima_atualizacao_codigo='2018-08-27';
UPDATE versao SET versao_bd=508;

ALTER TABLE projeto_resumo ADD COLUMN municipio VARCHAR(255);