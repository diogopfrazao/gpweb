SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-11';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-11';
UPDATE versao SET versao_bd=497;
 
 
ALTER TABLE laudo_paralisado ADD COLUMN laudo_paralisado_uuid VARCHAR(36) DEFAULT NULL;