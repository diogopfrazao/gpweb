SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-05-19';
UPDATE versao SET ultima_atualizacao_codigo='2020-05-19';
UPDATE versao SET versao_bd=559;
INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('trs','tr_ano','Ano',1);


