SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.62';
UPDATE versao SET ultima_atualizacao_bd='2016-12-05';
UPDATE versao SET ultima_atualizacao_codigo='2016-12-05';
UPDATE versao SET versao_bd=386;


ALTER TABLE preferencia ADD COLUMN informa_aberto SMALLINT(1) DEFAULT 1;
