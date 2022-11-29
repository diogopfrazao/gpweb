SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-17';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-17';
UPDATE versao SET versao_bd=500;


ALTER TABLE ssti ADD COLUMN ssti_classificacao INTEGER(10) UNSIGNED DEFAULT NULL;