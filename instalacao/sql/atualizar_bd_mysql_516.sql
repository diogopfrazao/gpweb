SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.21';
UPDATE versao SET ultima_atualizacao_bd='2018-12-05';
UPDATE versao SET ultima_atualizacao_codigo='2018-12-05';
UPDATE versao SET versao_bd=516;


ALTER TABLE tr CHANGE tr_ano tr_ano VARCHAR(255) DEFAULT NULL;
