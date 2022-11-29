SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-06-27';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-27';
UPDATE versao SET versao_bd=427;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('tarefas','tarefa_cia','Organização responsável',0),
	('tarefas','tarefa_cias','Organizações envolvidas',0),
	('tarefas','tarefa_dept','Seção responsável',1),
	('tarefas','tarefa_depts','Seções envolvidas',0),
	('tarefas','tarefa_dono','Responsável',1),	
	('tarefas','tarefa_designados','Designados',1),		
	('tarefas','tarefa_inicio','Início',1),
	('tarefas','tarefa_fim','Término',1),
	('tarefas','tarefa_relacionado','Relacionado',1),
	('tarefas','tarefa_projeto','projeto',1),
	('tarefas','tarefa_gantt','Gantt',1),
	('tarefas','tarefa_marco','Marco',0),
	('tarefas','tarefa_status','Status',0),
	('tarefas','tarefa_prioridade','Prioridade',0),
	('tarefas','tarefa_percentagem','Físico executado',1),
	('tarefas','tarefa_descricao','O Que',0),
	('tarefas','tarefa_onde','Onde',0),
	('tarefas','tarefa_porque','Por Que',0),
	('tarefas','tarefa_como','Como',0),
	('tarefas','tarefa_situacao_atual','Situação Atual',0),
	('tarefas','tarefa_url_relacionada','Endereço URL',0),
	('tarefas','tarefa_tipo','Categoria',0),
	('tarefas','tarefa_endereco','Endereço',0),
	('tarefas','tarefa_cidade','Município',0),
	('tarefas','tarefa_estado','Estado',0),
	('tarefas','tarefa_georreferenciamento','Georreferenciamento',0),
	('tarefas','tarefa_previsto','Quantidade Prevista',0),
	('tarefas','tarefa_realizado','Quantidade Realizada',0),
	('tarefas','tarefa_codigo','Código',0),
	('tarefas','tarefa_setor','setor',0),
	('tarefas','tarefa_segmento','segmento',0),
	('tarefas','tarefa_intervencao','intervencao',0),
	('tarefas','tarefa_tipo_intervencao','tipo',0),
	('tarefas','financeiro_previsto','Cronograma financeiro previsto até a data atual',0),
	('tarefas','total_estimado','Custo de M.O., planilhas de custo e recursos até a data atual',0),
	('tarefas','total_estimado_total','Custo de M.O., planilhas de custo e recursos até o final',0),
	('tarefas','mo_previsto','Custo de mão de obra prevista até a data atual',0),
	('tarefas','mo_previsto_total','Custo de mão de obra prevista até o final',0),
	('tarefas','recurso_previsto','Custo de recursos alocados até a data atual',0),
	('tarefas','recurso_previsto_total','Custo de recursos alocados até o final',0),
	('tarefas','custo','Custo total',1),
	('tarefas','tarefa_orcamento','Custos Estimados',0),
	('tarefas','fisico_previsto','Execução física prevista',0),
	('tarefas','gasto','Gasto total',0),
	('tarefas','gasto_registro','Gastos extras',0),
	('tarefas','financeiro_velocidade','Velocidade do cronograma financeiro',0),
	('tarefas','fisico_velocidade','Velocidade do cronograma físico',0);

	
	
