SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.22';
UPDATE versao SET ultima_atualizacao_bd='2019-04-12';
UPDATE versao SET ultima_atualizacao_codigo='2019-04-12';
UPDATE versao SET versao_bd=520;

ALTER TABLE laudo ADD COLUMN plano_acao_item_responsavel_exibe tinyint(1) DEFAULT 0;
ALTER TABLE laudo ADD COLUMN plano_acao_item_usuarios_exibe tinyint(1) DEFAULT 0;

UPDATE laudo SET plano_acao_item_usuarios_exibe=1;