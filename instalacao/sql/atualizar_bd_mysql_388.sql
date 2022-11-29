SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.63';
UPDATE versao SET ultima_atualizacao_bd='2017-01-02';
UPDATE versao SET ultima_atualizacao_codigo='2017-01-02';
UPDATE versao SET versao_bd=388;


ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_status INTEGER(10) DEFAULT 0;

ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_observacao MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_quando plano_acao_item_quando MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_oque plano_acao_item_oque MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_como plano_acao_item_como MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_onde plano_acao_item_onde MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_quanto plano_acao_item_quanto MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_porque plano_acao_item_porque MEDIUMTEXT;
ALTER TABLE plano_acao_item CHANGE plano_acao_item_quem plano_acao_item_quem MEDIUMTEXT;
	
