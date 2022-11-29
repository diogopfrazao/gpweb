SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.28';
UPDATE versao SET ultima_atualizacao_bd='2020-10-26';
UPDATE versao SET ultima_atualizacao_codigo='2020-10-26';
UPDATE versao SET versao_bd=590;

DROP TABLE IF EXISTS tarefa_bioma;

CREATE TABLE tarefa_bioma (
	tarefa_bioma_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tarefa_bioma_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_bioma_bioma varchar(50) DEFAULT NULL,
  tarefa_bioma_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_bioma_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY tarefa_bioma_id (tarefa_bioma_id),
	KEY tarefa_bioma_tarefa (tarefa_bioma_tarefa),
	CONSTRAINT tarefa_bioma_tarefa FOREIGN KEY (tarefa_bioma_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS log_bioma;

CREATE TABLE log_bioma (
	log_bioma_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	log_bioma_log INTEGER(100) UNSIGNED DEFAULT NULL,
	log_bioma_bioma varchar(50) DEFAULT NULL,
  log_bioma_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  log_bioma_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY log_bioma_id (log_bioma_id),
	KEY log_bioma_log (log_bioma_log),
	CONSTRAINT log_bioma_log FOREIGN KEY (log_bioma_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS tarefa_comunidade;

CREATE TABLE tarefa_comunidade (
	tarefa_comunidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tarefa_comunidade_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_comunidade_comunidade varchar(50) DEFAULT NULL,
  tarefa_comunidade_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_comunidade_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY tarefa_comunidade_id (tarefa_comunidade_id),
	KEY tarefa_comunidade_tarefa (tarefa_comunidade_tarefa),
	CONSTRAINT tarefa_comunidade_tarefa FOREIGN KEY (tarefa_comunidade_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS log_comunidade;

CREATE TABLE log_comunidade (
	log_comunidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	log_comunidade_log INTEGER(100) UNSIGNED DEFAULT NULL,
	log_comunidade_comunidade varchar(50) DEFAULT NULL,
  log_comunidade_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  log_comunidade_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY log_comunidade_id (log_comunidade_id),
	KEY log_comunidade_log (log_comunidade_log),
	CONSTRAINT log_comunidade_log FOREIGN KEY (log_comunidade_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE log ADD COLUMN log_tipo_oorrencia INTEGER(10) DEFAULT NULL;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('tarefa','bioma','Biomas',0),
	('tarefa','comunidade','Comunidades',0),
	('log','bioma','Biomas',0),
	('log','comunidade','Comunidades',0),
	('log','tipo_oorrencia','Tipo de ocorrência',0);
	

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('tarefa_bioma','bioma','legenda','text'),
	('tarefa_biomas','biomas','legenda','text'),
	('genero_tarefa_bioma','o','legenda','select'),
	('tarefa_comunidade','comunidade','legenda','text'),
	('tarefa_comunidades','comunidades','legenda','text'),
	('genero_tarefa_comunidade','o','legenda','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
	('genero_tarefa_bioma','a'),
	('genero_tarefa_bioma','o'),
	('genero_tarefa_comunidade','a'),
	('genero_tarefa_comunidade','o');
	
	
INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('tarefa_bioma','Bioma 1','1',NULL),	
	('tarefa_bioma','Bioma 2','2',NULL),	
	('tarefa_comunidade','Comunidade 1','1',NULL),	
	('tarefa_comunidade','Comunidade 2','2',NULL),
	('log_tipo_oorrencia','Ocorrência Tipo 1','1',NULL),	
	('log_tipo_oorrencia','Ocorrência Tipo 2','2',NULL);