SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-07-10';
UPDATE versao SET ultima_atualizacao_codigo='2020-07-10';
UPDATE versao SET versao_bd=572;


ALTER TABLE projetos ADD COLUMN projeto_programa_financeiro VARCHAR(255) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_convenio VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_programa_financeiro VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_convenio VARCHAR(255) DEFAULT NULL;


DROP TABLE IF EXISTS projeto_atividade;

CREATE TABLE projeto_atividade (
	projeto_atividade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	projeto_atividade_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_atividade_atividade varchar(50) DEFAULT NULL,
  projeto_atividade_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_atividade_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY projeto_atividade_id (projeto_atividade_id),
	KEY projeto_atividade_projeto (projeto_atividade_projeto),
	CONSTRAINT projeto_atividade_projeto FOREIGN KEY (projeto_atividade_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_projeto_atividade;

CREATE TABLE baseline_projeto_atividade (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
	projeto_atividade_id INTEGER(100) UNSIGNED NOT NULL,
	projeto_atividade_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_atividade_atividade varchar(50) DEFAULT NULL,
  projeto_atividade_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_atividade_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_atividade_id),
	KEY baseline_id (baseline_id),
  KEY projeto_atividade_id (projeto_atividade_id),
	KEY projeto_atividade_projeto (projeto_atividade_projeto),
	CONSTRAINT baseline_projeto_atividade_baseline FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_projeto_atividade_id FOREIGN KEY (projeto_atividade_id) REFERENCES projeto_atividade (projeto_atividade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_projeto_atividade_projeto FOREIGN KEY (projeto_atividade_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS projeto_regiao;

CREATE TABLE projeto_regiao (
	projeto_regiao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	projeto_regiao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_regiao_regiao varchar(50) DEFAULT NULL,
  projeto_regiao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_regiao_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY projeto_regiao_id (projeto_regiao_id),
	KEY projeto_regiao_projeto (projeto_regiao_projeto),
	CONSTRAINT projeto_regiao_projeto FOREIGN KEY (projeto_regiao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_projeto_regiao;

CREATE TABLE baseline_projeto_regiao (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
	projeto_regiao_id INTEGER(100) UNSIGNED NOT NULL,
	projeto_regiao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_regiao_regiao varchar(50) DEFAULT NULL,
  projeto_regiao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_regiao_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_regiao_id),
	KEY baseline_id (baseline_id),
  KEY projeto_regiao_id (projeto_regiao_id),
	KEY projeto_regiao_projeto (projeto_regiao_projeto),
	CONSTRAINT baseline_projeto_regiao_baseline FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_projeto_regiao_id FOREIGN KEY (projeto_regiao_id) REFERENCES projeto_regiao (projeto_regiao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_projeto_regiao_projeto FOREIGN KEY (projeto_regiao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('projeto','projeto_programa_financeiro','Programa', 0),
	('projeto','projeto_convenio','Convênio', 0),
	('projeto','projeto_atividade','Atividade', 0),
	('projeto','projeto_regiao','Região', 0),
	('projetos','projeto_programa_financeiro','Programa', 0),
	('projetos','projeto_convenio','Convênio', 0),
	('projetos','projeto_atividade','Atividade', 0),
	('projetos','projeto_regiao','Região', 0);
	
	
ALTER TABLE projetos DROP COLUMN projeto_regiao;	
ALTER TABLE baseline_projetos DROP COLUMN projeto_regiao;		
	
