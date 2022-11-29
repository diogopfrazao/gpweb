SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-02-26';
UPDATE versao SET ultima_atualizacao_codigo='2020-02-26';
UPDATE versao SET versao_bd=549;

DELETE FROM sisvalores WHERE sisvalor_titulo='StatusOS' OR sisvalor_titulo='StatusOSCor';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('StatusOSCor','fd473e','0',NULL),
	('StatusOSCor','fd783e','1',NULL),
	('StatusOSCor','fdc03e','2',NULL),
	('StatusOSCor','fdfb3e','3',NULL),
	('StatusOSCor','d2fd3e','4',NULL),
	('StatusOSCor','b3fd3e','5',NULL),
	('StatusOSCor','59fd3e','6',NULL),
	('StatusOS','solicitada','0',NULL),
	('StatusOS','a empenhar','1',NULL),
	('StatusOS','prevista','2',NULL),
	('StatusOS','empenhada','3',NULL),
	('StatusOS','liquidada','4',NULL),
	('StatusOS','paga','5',NULL),
	('StatusOS','finalizada','6',NULL);
	
ALTER TABLE os MODIFY os_valor decimal(20,5) DEFAULT 0;

ALTER TABLE os ADD COLUMN os_local_entrega TEXT AFTER os_prazo_entrega;