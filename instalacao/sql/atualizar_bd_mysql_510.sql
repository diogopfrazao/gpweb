SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.19';
UPDATE versao SET ultima_atualizacao_bd='2018-09-21';
UPDATE versao SET ultima_atualizacao_codigo='2018-09-21';
UPDATE versao SET versao_bd=510;

DELETE FROM config_lista WHERE config_nome='nivel_acesso_padrao';	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES	
	('nivel_acesso_padrao','0'),
	('nivel_acesso_padrao','1'),
	('nivel_acesso_padrao','5'),
	('nivel_acesso_padrao','4'),
	('nivel_acesso_padrao','2'),
	('nivel_acesso_padrao','6'),
	('nivel_acesso_padrao','3');