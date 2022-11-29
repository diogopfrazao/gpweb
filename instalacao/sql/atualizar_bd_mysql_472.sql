SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-02-26';
UPDATE versao SET ultima_atualizacao_codigo='2018-02-26';
UPDATE versao SET versao_bd=472;

ALTER TABLE painel ADD COLUMN painel_legenda_largura INTEGER(10) DEFAULT NULL;