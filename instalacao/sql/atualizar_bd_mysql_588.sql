SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-10-12';
UPDATE versao SET ultima_atualizacao_codigo='2020-10-12';
UPDATE versao SET versao_bd=588;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('projeto_fonte_sem_convenio','100 - Recursos Ordin�rios do Tesouro Estadual','100',NULL),
	('projeto_fonte_sem_convenio','195 - Recursos de Transfer�ncias da Uni�o','195',NULL),
	('projeto_fonte_sem_convenio','217 - Recursos Pr�prios com Finalidades Espec�ficas','217',NULL),
	('projeto_fonte_sem_convenio','240 - Recursos Pr�prios','240',NULL),
	('projeto_fonte_sem_convenio','395 - Superavit�ria de recursos de transfer�ncias da Uni�o','395',NULL),
	('projeto_fonte_sem_convenio','617 - Superavit�ria de recursos pr�prios com finalidades especificas','617',NULL),
	('projeto_fonte_sem_convenio','640 - Superavit�ria  de recursos pr�prios','640',NULL);