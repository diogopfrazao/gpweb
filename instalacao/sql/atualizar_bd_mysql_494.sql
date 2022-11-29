SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-07';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-07';
UPDATE versao SET versao_bd=494;

ALTER TABLE ssti CHANGE ssti_paralisado_justificativa ssti_paralisado_justificativa VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti CHANGE ssti_cancelada_justificativa ssti_cancelada_justificativa VARCHAR(255) DEFAULT NULL;