SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.62';
UPDATE versao SET ultima_atualizacao_bd='2016-12-05';
UPDATE versao SET ultima_atualizacao_codigo='2016-12-05';
UPDATE versao SET versao_bd=387;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('StatusAcaoPlanoCor','c5eff4','0',NULL),
	('StatusAcaoPlanoCor','e4ee8b','1',NULL),
	('StatusAcaoPlanoCor','a0cc9d','2',NULL),
	('StatusAcaoPlanoCor','da908c','3',NULL),
	('StatusAcaoPlanoCor','e3c383','4',NULL),
	('StatusAcaoPlano','Não Iniciada','0',NULL),
	('StatusAcaoPlano','Pendente','1',NULL),
	('StatusAcaoPlano','Concluída','2',NULL),
	('StatusAcaoPlano','Não Realizada','3',NULL),
	('StatusAcaoPlano','Em Andamento','4',NULL);
	
	
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_uuid VARCHAR(36) DEFAULT NULL;
	
