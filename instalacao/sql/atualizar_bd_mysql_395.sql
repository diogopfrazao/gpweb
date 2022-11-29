SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.01';
UPDATE versao SET ultima_atualizacao_bd='2017-03-06';
UPDATE versao SET ultima_atualizacao_codigo='2017-03-06';
UPDATE versao SET versao_bd=395;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('demandas','demanda_cor','Cor',1),
	('demandas','demanda_cia','Organiza��o respons�vel',0),
	('demandas','demanda_cias','Organiza��es envolvidas',0),
	('demandas','demanda_dept','Se��o respons�vel',1),
	('demandas','demanda_depts','Se��es envolvidas',0),
	('demandas','demanda_usuario','Respons�vel',1),	
	('demandas','demanda_designados','Designados',0),		
	('demandas','demanda_aprovado','Aprovado',1),
	('demandas','demanda_relacionado','Relacionado',1),
	('demandas','demanda_data','Data',1),
	('demandas','demanda_identificacao','Identifica��o',0),
	('demandas','demanda_justificativa','Justificativa',0),
	('demandas','demanda_resultados','Resultados a Serem Alcan�ados',0),
	('demandas','demanda_alinhamento','Alinhamento Estrat�gico',0),
	('demandas','demanda_fonte_recurso','Fonte de Recurso',0),
	('demandas','demanda_prazo','Prazo',0),
	('demandas','demanda_custos','Custos',0),
	('demandas','demanda_codigo','C�digo',0),
	('demandas','demanda_observacao','Observa��es',0),
	('demandas','demanda_supervisor','supervisor',0),
	('demandas','demanda_autoridade','autoridade',0),
	('demandas','demanda_cliente','cliente',0),
	('projeto_viabilidades','projeto_viabilidade_cor','Cor',1),
	('projeto_viabilidades','projeto_viabilidade_cia','Organiza��o respons�vel',0),
	('projeto_viabilidades','projeto_viabilidade_cias','Organiza��es envolvidas',0),
	('projeto_viabilidades','projeto_viabilidade_dept','Se��o respons�vel',1),
	('projeto_viabilidades','projeto_viabilidade_depts','Se��es envolvidas',0),
	('projeto_viabilidades','projeto_viabilidade_responsavel','Respons�vel',1),	
	('projeto_viabilidades','projeto_viabilidade_designados','Designados',0),		
	('projeto_viabilidades','projeto_viabilidade_aprovado','Aprovado',1),
	('projeto_viabilidades','projeto_viabilidade_data','Data',1),
	('projeto_viabilidades','projeto_viabilidade_necessidade','Necessidade',0),
	('projeto_viabilidades','projeto_viabilidade_alinhamento','Alinhamento Estrat�gico',0),
	('projeto_viabilidades','projeto_viabilidade_requisitos','Requisitos B�sicos',0),
	('projeto_viabilidades','projeto_viabilidade_solucoes','Solu��es Poss�veis',0),
	('projeto_viabilidades','projeto_viabilidade_viabilidade_tecnica','Viabilidade T�cnica',0),
	('projeto_viabilidades','projeto_viabilidade_financeira','Viabilidade Financeira',0),
	('projeto_viabilidades','projeto_viabilidade_institucional','Viabilidade Institucional',0),
	('projeto_viabilidades','projeto_viabilidade_solucao','Indica��o de Solu��o',0),
	('projeto_viabilidades','projeto_viabilidade_continuidade','Parecer Sobre a Continuidade',0),
	('projeto_viabilidades','projeto_viabilidade_tempo','Parecer Sobre o Tempo',0),
	('projeto_viabilidades','projeto_viabilidade_custo','Parecer Sobre o Custo',0),
	('projeto_viabilidades','projeto_viabilidade_observacao','Observa��o',0),
	('projeto_viabilidades','projeto_viabilidade_codigo','C�digo',0),
	('projeto_aberturas','projeto_abertura_cor','Cor',1),
	('projeto_aberturas','projeto_abertura_cia','Organiza��o respons�vel',0),
	('projeto_aberturas','projeto_abertura_cias','Organiza��es envolvidas',0),
	('projeto_aberturas','projeto_abertura_dept','Se��o respons�vel',1),
	('projeto_aberturas','projeto_abertura_depts','Se��es envolvidas',0),
	('projeto_aberturas','projeto_abertura_responsavel','Respons�vel',1),	
	('projeto_aberturas','projeto_abertura_designados','Designados',0),		
	('projeto_aberturas','projeto_abertura_aprovado','Aprovado',1),
	('projeto_aberturas','projeto_abertura_data','Data',1),
	('projeto_aberturas','projeto_abertura_codigo','C�digo',0),
	('projeto_aberturas','projeto_abertura_justificativa','Justificativa',0),
  ('projeto_aberturas','projeto_abertura_objetivo','Objetivo',0),
  ('projeto_aberturas','projeto_abertura_escopo','Declara��o de Escopo',0),
  ('projeto_aberturas','projeto_abertura_nao_escopo','N�o Escopo',0),
  ('projeto_aberturas','projeto_abertura_tempo','Tempo Estimado',0),
  ('projeto_aberturas','projeto_abertura_custo','Custos Estimado e Fonte de Recurso',0),
  ('projeto_aberturas','projeto_abertura_premissas','Premissas',0),
  ('projeto_aberturas','projeto_abertura_restricoes','Restri��es',0),
  ('projeto_aberturas','projeto_abertura_riscos','Riscos Previamente Identificados',0),
  ('projeto_aberturas','projeto_abertura_infraestrutura','Infraestrutura',0),
  ('projeto_aberturas','projeto_abertura_observacao','Observa��es',0),
 	('projeto_aberturas','projeto_abertura_recusa','Justificativa da N�o Aprova��o',0);


	
	

	
	
	
	
