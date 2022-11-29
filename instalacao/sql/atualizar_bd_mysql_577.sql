SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-07-30';
UPDATE versao SET ultima_atualizacao_codigo='2020-07-30';
UPDATE versao SET versao_bd=577;


DROP TABLE IF EXISTS projeto_convenio;

CREATE TABLE projeto_convenio (
	projeto_convenio_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	projeto_convenio_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_convenio_convenio varchar(50) DEFAULT NULL,
  projeto_convenio_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_convenio_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY projeto_convenio_id (projeto_convenio_id),
	KEY projeto_convenio_projeto (projeto_convenio_projeto),
	CONSTRAINT projeto_convenio_projeto FOREIGN KEY (projeto_convenio_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_projeto_convenio;

CREATE TABLE baseline_projeto_convenio (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
	projeto_convenio_id INTEGER(100) UNSIGNED NOT NULL,
	projeto_convenio_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_convenio_convenio varchar(50) DEFAULT NULL,
  projeto_convenio_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_convenio_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_convenio_id),
	KEY baseline_id (baseline_id),
  KEY projeto_convenio_id (projeto_convenio_id),
	KEY projeto_convenio_projeto (projeto_convenio_projeto),
	CONSTRAINT baseline_projeto_convenio_baseline FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_projeto_convenio_id FOREIGN KEY (projeto_convenio_id) REFERENCES projeto_convenio (projeto_convenio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_projeto_convenio_projeto FOREIGN KEY (projeto_convenio_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;