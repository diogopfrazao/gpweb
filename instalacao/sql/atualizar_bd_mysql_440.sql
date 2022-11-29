SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-08-07';
UPDATE versao SET ultima_atualizacao_codigo='2017-08-07';
UPDATE versao SET versao_bd=440;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('patrocinador','patrocinador','legenda','text'),
	('patrocinadores','patrocinadores','legenda','text'),
	('genero_patrocinador','o','legenda','select');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_patrocinador','a'),
	('genero_patrocinador','o');