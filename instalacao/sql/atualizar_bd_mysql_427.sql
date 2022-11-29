SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-06-27';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-27';
UPDATE versao SET versao_bd=427;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('tarefas','tarefa_cia','Organiza��o respons�vel',0),
	('tarefas','tarefa_cias','Organiza��es envolvidas',0),
	('tarefas','tarefa_dept','Se��o respons�vel',1),
	('tarefas','tarefa_depts','Se��es envolvidas',0),
	('tarefas','tarefa_dono','Respons�vel',1),	
	('tarefas','tarefa_designados','Designados',1),		
	('tarefas','tarefa_inicio','In�cio',1),
	('tarefas','tarefa_fim','T�rmino',1),
	('tarefas','tarefa_relacionado','Relacionado',1),
	('tarefas','tarefa_projeto','projeto',1),
	('tarefas','tarefa_gantt','Gantt',1),
	('tarefas','tarefa_marco','Marco',0),
	('tarefas','tarefa_status','Status',0),
	('tarefas','tarefa_prioridade','Prioridade',0),
	('tarefas','tarefa_percentagem','F�sico executado',1),
	('tarefas','tarefa_descricao','O Que',0),
	('tarefas','tarefa_onde','Onde',0),
	('tarefas','tarefa_porque','Por Que',0),
	('tarefas','tarefa_como','Como',0),
	('tarefas','tarefa_situacao_atual','Situa��o Atual',0),
	('tarefas','tarefa_url_relacionada','Endere�o URL',0),
	('tarefas','tarefa_tipo','Categoria',0),
	('tarefas','tarefa_endereco','Endere�o',0),
	('tarefas','tarefa_cidade','Munic�pio',0),
	('tarefas','tarefa_estado','Estado',0),
	('tarefas','tarefa_georreferenciamento','Georreferenciamento',0),
	('tarefas','tarefa_previsto','Quantidade Prevista',0),
	('tarefas','tarefa_realizado','Quantidade Realizada',0),
	('tarefas','tarefa_codigo','C�digo',0),
	('tarefas','tarefa_setor','setor',0),
	('tarefas','tarefa_segmento','segmento',0),
	('tarefas','tarefa_intervencao','intervencao',0),
	('tarefas','tarefa_tipo_intervencao','tipo',0),
	('tarefas','financeiro_previsto','Cronograma financeiro previsto at� a data atual',0),
	('tarefas','total_estimado','Custo de M.O., planilhas de custo e recursos at� a data atual',0),
	('tarefas','total_estimado_total','Custo de M.O., planilhas de custo e recursos at� o final',0),
	('tarefas','mo_previsto','Custo de m�o de obra prevista at� a data atual',0),
	('tarefas','mo_previsto_total','Custo de m�o de obra prevista at� o final',0),
	('tarefas','recurso_previsto','Custo de recursos alocados at� a data atual',0),
	('tarefas','recurso_previsto_total','Custo de recursos alocados at� o final',0),
	('tarefas','custo','Custo total',1),
	('tarefas','tarefa_orcamento','Custos Estimados',0),
	('tarefas','fisico_previsto','Execu��o f�sica prevista',0),
	('tarefas','gasto','Gasto total',0),
	('tarefas','gasto_registro','Gastos extras',0),
	('tarefas','financeiro_velocidade','Velocidade do cronograma financeiro',0),
	('tarefas','fisico_velocidade','Velocidade do cronograma f�sico',0);

	
	
