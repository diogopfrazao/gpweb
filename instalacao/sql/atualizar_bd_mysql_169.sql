SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.13'; 
UPDATE versao SET ultima_atualizacao_bd='2013-07-14'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-07-14'; 
UPDATE versao SET versao_bd=169;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
 ('paginas_fundo', 'false','interface','checkbox');