SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-14';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-14';
UPDATE versao SET versao_bd=498;
 
ALTER TABLE painel CHANGE painel_agrupar painel_agrupar VARCHAR(20) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_data_obrigatorio TINYINT(1) DEFAULT 0;