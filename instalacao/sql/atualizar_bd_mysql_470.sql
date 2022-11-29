SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-02-19';
UPDATE versao SET ultima_atualizacao_codigo='2018-02-19';
UPDATE versao SET versao_bd=470;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_variacao INTEGER(10) DEFAULT 0;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('categoria','categoria','legenda','text'),
	('genero_categoria','a','legenda','select');
	
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_categoria','o'),
  ('genero_categoria','a');