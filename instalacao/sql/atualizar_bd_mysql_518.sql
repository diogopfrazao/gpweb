SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.22';
UPDATE versao SET ultima_atualizacao_bd='2019-03-12';
UPDATE versao SET ultima_atualizacao_codigo='2019-03-12';
UPDATE versao SET versao_bd=518;

ALTER TABLE usuarios CHANGE usuario_login usuario_login VARCHAR(255) DEFAULT NULL;
ALTER TABLE usuarios CHANGE usuario_login2 usuario_login2 VARCHAR(255) DEFAULT NULL;