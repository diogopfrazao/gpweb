SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.04';
UPDATE versao SET ultima_atualizacao_bd='2017-05-04';
UPDATE versao SET ultima_atualizacao_codigo='2017-05-04';
UPDATE versao SET versao_bd=404;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='indicadores';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('indicadores','data_alteracao','A data da �ltima altera��o',1),
	('indicadores','pratica_indicador_acumulacao','Acumula��o',1),
	('indicadores','pratica_indicador_aprovado','Aprovado',1),
	('indicadores','pratica_indicador_codigo','C�digo',0),
	('indicadores','pratica_indicador_requisito_como','Como Fazer',0),
	('indicadores','pratica_indicador_cor','Cor',1),
	('indicadores','pratica_indicador_data_meta','Data da Meta',1),
	('indicadores','pratica_indicador_data_alteracao','Data da �ltima altera��o',1),
	('indicadores','pratica_indicador_requisito_descricao','Descri��o',0),
	('indicadores','pratica_indicador_designados','Designados',0),
	('indicadores','pratica_indicador_requisito_melhorias','Melhorias',0),
	('indicadores','pratica_indicador_meta','Meta',1),
	('indicadores','pratica_indicador_requisito_oque','O Que Fazer',0),
	('indicadores','pratica_indicador_requisito_onde','Onde Fazer',0),
	('indicadores','pratica_indicador_cia','Organiza��o respons�vel',0),
	('indicadores','pratica_indicador_cias','Organiza��es envolvidas',0),
	('indicadores','pratica_indicador_agrupar','Periodicidade',1),
	('indicadores','pratica_indicador_sentido','Polaridade',0),
	('indicadores','pratica_indicador_periodo_anterior','Pontua��o do per�odo anterior',0),
	('indicadores','pratica_indicador_pontuacao','Pontua��o',1),
	('indicadores','pratica_indicador_requisito_porque','Por Que Fazer',0),
	('indicadores','pratica_indicador_requisito_quando','Quando Fazer',0),
	('indicadores','pratica_indicador_marcadores','Quantidade de marcadores',1),
	('indicadores','pratica_indicador_requisito_quanto','Quanto Custa',0),
	('indicadores','pratica_indicador_requisito_quem','Quem Faz',0),
	('indicadores','pratica_indicador_relacionado','Relacionado',1),
	('indicadores','pratica_indicador_responsavel','Respons�vel',1),
	('indicadores','pratica_indicador_dept','Se��o respons�vel',1),
	('indicadores','pratica_indicador_depts','Se��es envolvidas',0),
	('indicadores','pratica_indicador_tendencia','Tend�ncia',1),
	('indicadores','pratica_indicador_acumulacao','Tipo de acumula��o',0),
	('indicadores','pratica_indicador_tolerancia','Toler�ncia',0),
	('indicadores','pratica_indicador_unidade','Unidade de medida',1),
	('indicadores','pratica_indicador_valor','Valor',1);
	