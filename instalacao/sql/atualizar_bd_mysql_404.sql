SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.04';
UPDATE versao SET ultima_atualizacao_bd='2017-05-04';
UPDATE versao SET ultima_atualizacao_codigo='2017-05-04';
UPDATE versao SET versao_bd=404;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='indicadores';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('indicadores','data_alteracao','A data da última alteração',1),
	('indicadores','pratica_indicador_acumulacao','Acumulação',1),
	('indicadores','pratica_indicador_aprovado','Aprovado',1),
	('indicadores','pratica_indicador_codigo','Código',0),
	('indicadores','pratica_indicador_requisito_como','Como Fazer',0),
	('indicadores','pratica_indicador_cor','Cor',1),
	('indicadores','pratica_indicador_data_meta','Data da Meta',1),
	('indicadores','pratica_indicador_data_alteracao','Data da última alteração',1),
	('indicadores','pratica_indicador_requisito_descricao','Descrição',0),
	('indicadores','pratica_indicador_designados','Designados',0),
	('indicadores','pratica_indicador_requisito_melhorias','Melhorias',0),
	('indicadores','pratica_indicador_meta','Meta',1),
	('indicadores','pratica_indicador_requisito_oque','O Que Fazer',0),
	('indicadores','pratica_indicador_requisito_onde','Onde Fazer',0),
	('indicadores','pratica_indicador_cia','Organização responsável',0),
	('indicadores','pratica_indicador_cias','Organizações envolvidas',0),
	('indicadores','pratica_indicador_agrupar','Periodicidade',1),
	('indicadores','pratica_indicador_sentido','Polaridade',0),
	('indicadores','pratica_indicador_periodo_anterior','Pontuação do período anterior',0),
	('indicadores','pratica_indicador_pontuacao','Pontuação',1),
	('indicadores','pratica_indicador_requisito_porque','Por Que Fazer',0),
	('indicadores','pratica_indicador_requisito_quando','Quando Fazer',0),
	('indicadores','pratica_indicador_marcadores','Quantidade de marcadores',1),
	('indicadores','pratica_indicador_requisito_quanto','Quanto Custa',0),
	('indicadores','pratica_indicador_requisito_quem','Quem Faz',0),
	('indicadores','pratica_indicador_relacionado','Relacionado',1),
	('indicadores','pratica_indicador_responsavel','Responsável',1),
	('indicadores','pratica_indicador_dept','Seção responsável',1),
	('indicadores','pratica_indicador_depts','Seções envolvidas',0),
	('indicadores','pratica_indicador_tendencia','Tendência',1),
	('indicadores','pratica_indicador_acumulacao','Tipo de acumulação',0),
	('indicadores','pratica_indicador_tolerancia','Tolerância',0),
	('indicadores','pratica_indicador_unidade','Unidade de medida',1),
	('indicadores','pratica_indicador_valor','Valor',1);
	