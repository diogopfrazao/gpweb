SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-02-14';
UPDATE versao SET ultima_atualizacao_codigo='2020-02-14';
UPDATE versao SET versao_bd=547;


INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES	
	('tr','tr_custo','Permite editar custos mesmo quando já aprovado', null, null);


ALTER TABLE instrumento DROP COLUMN instrumento_tipo;