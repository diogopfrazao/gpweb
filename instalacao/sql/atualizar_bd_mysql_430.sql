SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-07-07';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-07';
UPDATE versao SET versao_bd=430;

ALTER TABLE risco ADD COLUMN risco_positivo TINYINT(1) DEFAULT 0;

UPDATE campo_formulario SET campo_formulario_descricao='Observação' WHERE campo_formulario_campo='swot_descricao';
UPDATE campo_formulario SET campo_formulario_descricao='Observação' WHERE campo_formulario_campo='mswot_descricao';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('riscos','risco_positivo','Tipo',1);

DELETE FROM sisvalores WHERE sisvalor_titulo='RiscoGravidade';
DELETE FROM sisvalores WHERE sisvalor_titulo='RiscoUrgencia';
DELETE FROM sisvalores WHERE sisvalor_titulo='RiscoTendencia';
