SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-08-15';
UPDATE versao SET ultima_atualizacao_codigo='2017-08-15';
UPDATE versao SET versao_bd=446;

DROP TABLE IF EXISTS modelo_despacho;