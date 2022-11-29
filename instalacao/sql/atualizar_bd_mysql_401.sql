SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.04';
UPDATE versao SET ultima_atualizacao_bd='2017-04-12';
UPDATE versao SET ultima_atualizacao_codigo='2017-04-12';
UPDATE versao SET versao_bd=401;


ALTER TABLE contatos CHANGE contato_tel contato_tel VARCHAR(20) DEFAULT NULL;
ALTER TABLE contatos CHANGE contato_tel2 contato_tel2 VARCHAR(20) DEFAULT NULL;
ALTER TABLE contatos CHANGE contato_cel contato_cel VARCHAR(20) DEFAULT NULL;

ALTER TABLE contatos DROP COLUMN contato_jabber;
ALTER TABLE contatos DROP COLUMN contato_icq;
ALTER TABLE contatos DROP COLUMN contato_yahoo;
ALTER TABLE contatos DROP COLUMN contato_msn;