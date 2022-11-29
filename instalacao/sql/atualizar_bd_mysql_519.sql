SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.22';
UPDATE versao SET ultima_atualizacao_bd='2019-04-12';
UPDATE versao SET ultima_atualizacao_codigo='2019-04-12';
UPDATE versao SET versao_bd=519;

ALTER TABLE laudo ADD COLUMN laudo_ranking_sugestao2 INTEGER(10) DEFAULT NULL;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('cogess','laudo_ranking_sugestao2','Aceitou sugestão de ranqueamento', 1);