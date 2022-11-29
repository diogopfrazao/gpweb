SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-10-12';
UPDATE versao SET ultima_atualizacao_codigo='2020-10-12';
UPDATE versao SET versao_bd=588;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('projeto_fonte_sem_convenio','100 - Recursos Ordinários do Tesouro Estadual','100',NULL),
	('projeto_fonte_sem_convenio','195 - Recursos de Transferências da União','195',NULL),
	('projeto_fonte_sem_convenio','217 - Recursos Próprios com Finalidades Específicas','217',NULL),
	('projeto_fonte_sem_convenio','240 - Recursos Próprios','240',NULL),
	('projeto_fonte_sem_convenio','395 - Superavitária de recursos de transferências da União','395',NULL),
	('projeto_fonte_sem_convenio','617 - Superavitária de recursos próprios com finalidades especificas','617',NULL),
	('projeto_fonte_sem_convenio','640 - Superavitária  de recursos próprios','640',NULL);