SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-01';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-01';
UPDATE versao SET versao_bd=529;




ALTER TABLE campo_customizado ADD COLUMN campo_customizado_habilitado TINYINT(1) DEFAULT 1;

