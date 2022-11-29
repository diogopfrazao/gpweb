SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.12';
UPDATE versao SET ultima_atualizacao_bd='2017-11-06';
UPDATE versao SET ultima_atualizacao_codigo='2017-11-06';
UPDATE versao SET versao_bd=457;


INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('praticas','indicador_valor','Valor de Indicador', null, null);