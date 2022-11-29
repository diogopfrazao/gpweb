SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-07-05';
UPDATE versao SET ultima_atualizacao_codigo='2020-07-05';
UPDATE versao SET versao_bd=571;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('esqueceu_senha','true','admin_usuarios','checkbox');