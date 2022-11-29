SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.17';
UPDATE versao SET ultima_atualizacao_bd='2018-08-02';
UPDATE versao SET ultima_atualizacao_codigo='2018-08-02';
UPDATE versao SET versao_bd=504;

DELETE FROM sisvalores WHERE sisvalor_titulo='logTipo';		
INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('logTipo','RAP','1',NULL),
	('logTipo','PC','2',NULL);
	
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 	
	('grava_indicador_projeto','true','projetos','checkbox');