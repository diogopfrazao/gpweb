SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-07-21';
UPDATE versao SET ultima_atualizacao_codigo='2020-07-21';
UPDATE versao SET versao_bd=575;


ALTER TABLE tarefa_entrega ADD COLUMN tarefa_entrega_responsavel INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefa_entrega ADD KEY tarefa_entrega_responsavel (tarefa_entrega_responsavel);
ALTER TABLE tarefa_entrega ADD CONSTRAINT tarefa_entrega_responsavel FOREIGN KEY (tarefa_entrega_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE tarefa_entrega ADD COLUMN tarefa_entrega_prazo DATE DEFAULT NULL;

ALTER TABLE baseline_tarefa_entrega ADD COLUMN tarefa_entrega_responsavel INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefa_entrega ADD COLUMN tarefa_entrega_prazo DATE DEFAULT NULL;


DROP TABLE IF EXISTS log_entrega;

CREATE TABLE log_entrega (
  log_entrega_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  log_entrega_log INTEGER(100) UNSIGNED DEFAULT NULL,
  log_entrega_entrega INTEGER(100) UNSIGNED DEFAULT NULL,
  log_entrega_realizado INTEGER(100) UNSIGNED DEFAULT NULL,
  log_entrega_observacao MEDIUMTEXT,
	log_entrega_data DATE DEFAULT NULL,  
  log_entrega_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (log_entrega_id),
  KEY log_entrega_log (log_entrega_log),
  KEY log_entrega_entrega (log_entrega_entrega),
	CONSTRAINT log_entrega_log FOREIGN KEY (log_entrega_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_entrega_entrega FOREIGN KEY (log_entrega_entrega) REFERENCES tarefa_entrega (tarefa_entrega_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_log_entrega;

CREATE TABLE baseline_log_entrega (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  log_entrega_id INTEGER(100) UNSIGNED NOT NULL,
  log_entrega_log INTEGER(100) UNSIGNED DEFAULT NULL,
  log_entrega_entrega INTEGER(100) UNSIGNED DEFAULT NULL,
  log_entrega_realizado INTEGER(100) UNSIGNED DEFAULT NULL,
  log_entrega_observacao MEDIUMTEXT,
	log_entrega_data DATE DEFAULT NULL,  
  log_entrega_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, log_entrega_id),
  CONSTRAINT baseline_log_entrega FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;