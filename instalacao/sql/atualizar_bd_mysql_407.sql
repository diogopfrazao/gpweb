SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.04';
UPDATE versao SET ultima_atualizacao_bd='2017-05-09';
UPDATE versao SET ultima_atualizacao_codigo='2017-05-09';
UPDATE versao SET versao_bd=407;

ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca_servidores;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca_paraquem;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca_data;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca_expectativa;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca_descricao;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca_plano;

ALTER TABLE tarefa_log DROP COLUMN tarefa_log_cia;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_metodo;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_exercicio;
ALTER TABLE tarefa_log DROP COLUMN tarefa_log_reg_mudanca;

ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca_servidores;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca_paraquem;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca_data;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca_expectativa;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca_descricao;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca_plano;

ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_cia;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_metodo;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_exercicio;
ALTER TABLE baseline_tarefa_log DROP COLUMN tarefa_log_reg_mudanca;