SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.05';
UPDATE versao SET ultima_atualizacao_bd='2017-05-11';
UPDATE versao SET ultima_atualizacao_codigo='2017-05-11';
UPDATE versao SET versao_bd=409;

DROP TABLE IF EXISTS tarefa_log_arquivo;
DROP TABLE IF EXISTS baseline_tarefa_log_arquivo;
DROP TABLE IF EXISTS tarefa_log;
DROP TABLE IF EXISTS baseline_tarefa_log;

ALTER TABLE custo DROP FOREIGN KEY custo_tarefa_log;
ALTER TABLE custo DROP KEY custo_tarefa_log;
ALTER TABLE custo DROP COLUMN custo_tarefa_log;

UPDATE perfil_acesso SET perfil_acesso_modulo='log' WHERE perfil_acesso_modulo='tarefa_log';