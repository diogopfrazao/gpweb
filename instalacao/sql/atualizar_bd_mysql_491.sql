SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-05-30';
UPDATE versao SET ultima_atualizacao_codigo='2018-05-30';
UPDATE versao SET versao_bd=491;

ALTER TABLE ssti CHANGE ssti_estimativa_lucro ssti_estimativa_lucro INTEGER(10) UNSIGNED DEFAULT NULL;















	