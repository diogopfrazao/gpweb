SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.01';
UPDATE versao SET ultima_atualizacao_bd='2017-02-04';
UPDATE versao SET ultima_atualizacao_codigo='2017-02-04';
UPDATE versao SET versao_bd=391;

ALTER TABLE recursos ADD COLUMN recurso_cor VARCHAR(6) DEFAULT 'FFFFFF';

ALTER TABLE links ADD COLUMN link_cor VARCHAR(6) DEFAULT 'FFFFFF';

DELETE FROM campo_formulario WHERE campo_formulario_campo='projeto_fonte' OR campo_formulario_campo='projeto_regiao';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('recursos','recurso_cia','Organização responsável',1),
	('recursos','recurso_cias','Organizações envolvidas',0),
	('recursos','recurso_dept','Seção responsável',1),
	('recursos','recurso_depts','Seções envolvidas',0),
	('recursos','recurso_responsavel','Responsável',1),
	('recursos','recurso_designados','Designados',0),
	('recursos','recurso_relacionamento','Relacionamentos',1),
	('recursos','recurso_tipo','Tipo',1),
	('recursos','recurso_nota','Nota',0),
	('recursos','recurso_nota_icone','Nota (ícone)',1),
	('recursos','recurso_aprovado','Aprovado',1),
	('recursos','recurso_quantidade','Quantidade',1),
	('recursos','recurso_custo','Valor unitário',1),
	('recursos','recurso_hora_custo','Valor por hora',1),
	('recursos','recurso_chave','Código',0),
	('recursos','recurso_nd','Natureza da Despesa',0),
	('recursos','recurso_cor','Cor',1);
	