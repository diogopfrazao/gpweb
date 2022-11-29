SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-17';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-17';
UPDATE versao SET versao_bd=499;


ALTER TABLE ssti CHANGE ssti_data ssti_data DATETIME DEFAULT NULL;
ALTER TABLE laudo CHANGE laudo_data laudo_data DATETIME DEFAULT NULL;