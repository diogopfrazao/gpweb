SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-01-10';
UPDATE versao SET ultima_atualizacao_codigo='2019-01-10';
UPDATE versao SET versao_bd=540;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
  ('desabilitar_calculo_mao_obra', 'false', 'projetos', 'checkbox');