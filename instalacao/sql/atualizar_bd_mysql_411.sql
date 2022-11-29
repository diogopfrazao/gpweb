SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.05';
UPDATE versao SET ultima_atualizacao_bd='2017-05-20';
UPDATE versao SET ultima_atualizacao_codigo='2017-05-20';
UPDATE versao SET versao_bd=411;

CALL PROC_DROP_FOREIGN_KEY('recursos', 'recursos_fpti_fk2');
CALL PROC_DROP_FOREIGN_KEY('recursos', 'recursos_fpti_fk1');

ALTER TABLE recursos DROP KEY recurso_conta_orcamentaria;
ALTER TABLE recursos DROP COLUMN recurso_conta_orcamentaria;
ALTER TABLE recursos DROP KEY recurso_centro_custo;
ALTER TABLE recursos DROP COLUMN recurso_centro_custo;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projeto_aberturas','projeto_abertura_descricao','O Que',0),
	('projeto_aberturas','projeto_abertura_objetivos','Por Que',0),
	('projeto_aberturas','projeto_abertura_como','Como',0),
	('projeto_aberturas','projeto_abertura_localizacao','Onde',0),
	('projeto_aberturas','projeto_abertura_beneficiario','Beneficiário',0),
	('projeto_aberturas','projeto_abertura_objetivo_especifico','Objetivo Específico',0),
	('projeto_aberturas','projeto_abertura_orcamento','Custos e Recurso',0),
	('projeto_aberturas','projeto_abertura_beneficio','Benefícios',0),
	('projeto_aberturas','projeto_abertura_produto','Produtos',0),
	('projeto_aberturas','projeto_abertura_requisito','Requisitos',0),
	('projeto_aberturas','projeto_abertura_aprovacao','Justificativa da aprovação',0);