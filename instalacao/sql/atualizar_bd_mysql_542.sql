SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-01-19';
UPDATE versao SET ultima_atualizacao_codigo='2019-01-19';
UPDATE versao SET versao_bd=542;

ALTER TABLE alerta ADD COLUMN alerta_dias INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE alerta ADD COLUMN alerta_tem_dias TINYINT(1) DEFAULT 0;