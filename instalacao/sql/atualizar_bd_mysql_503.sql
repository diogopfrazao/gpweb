SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.17';
UPDATE versao SET ultima_atualizacao_bd='2018-07-12';
UPDATE versao SET ultima_atualizacao_codigo='2018-07-12';
UPDATE versao SET versao_bd=503;

CALL PROC_DROP_FOREIGN_KEY('tarefas','idx_tarefa_ordem');
ALTER TABLE tarefas DROP COLUMN tarefa_ordem;
ALTER TABLE baseline_tarefas DROP COLUMN tarefa_ordem;