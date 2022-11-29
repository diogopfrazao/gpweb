SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-07-11';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-11';
UPDATE versao SET versao_bd=432;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('google_map_chave','AIzaSyDRiX6LNwQVvOa7C5KIlctcXoOLksNxzKk','admin_sistema','password');
