SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.03';
UPDATE versao SET ultima_atualizacao_bd='2017-04-01';
UPDATE versao SET ultima_atualizacao_codigo='2017-04-01';
UPDATE versao SET versao_bd=398;

ALTER TABLE cias CHANGE cia_ug cia_ug VARCHAR(6) DEFAULT NULL;
ALTER TABLE cias CHANGE cia_ug2 cia_ug2 VARCHAR(6) DEFAULT NULL;

