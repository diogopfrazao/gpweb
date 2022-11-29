SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-04';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-04';
UPDATE versao SET versao_bd=564;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('todos','ver_todos_objetos','Ver todos os objetos', null, null);