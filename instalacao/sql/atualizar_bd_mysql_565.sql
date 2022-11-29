SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-10';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-10';
UPDATE versao SET versao_bd=565;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('todos','editar_aprovado','Permitir editar objeto aprovado', null, null);