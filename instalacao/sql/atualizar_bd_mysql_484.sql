SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-04-22';
UPDATE versao SET ultima_atualizacao_codigo='2018-04-22';
UPDATE versao SET versao_bd=484;

ALTER TABLE usuarios ADD COLUMN usuario_observador TINYINT(1) DEFAULT 0;