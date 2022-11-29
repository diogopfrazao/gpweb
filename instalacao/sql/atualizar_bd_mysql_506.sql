SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.18';
UPDATE versao SET ultima_atualizacao_bd='2018-08-16';
UPDATE versao SET ultima_atualizacao_codigo='2018-08-16';
UPDATE versao SET versao_bd=506;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projetos','projeto_cidade','Município',0);


UPDATE campo_formulario SET campo_formulario_descricao='Onde' WHERE campo_formulario_campo='projeto_localizacao';