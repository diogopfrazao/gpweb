SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.21';
UPDATE versao SET ultima_atualizacao_bd='2018-11-05';
UPDATE versao SET ultima_atualizacao_codigo='2018-11-05';
UPDATE versao SET versao_bd=512;

ALTER TABLE plano_acao ADD COLUMN plano_acao_data_apenas TINYINT(1) DEFAULT 0;

ALTER TABLE plano_gestao ADD COLUMN pg_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE plano_gestao ADD COLUMN pg_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE plano_gestao ADD COLUMN pg_ponto_alvo DECIMAL(20,5) UNSIGNED DEFAULT 0;

DROP TABLE IF EXISTS plano_gestao_media;

CREATE TABLE plano_gestao_media (
	plano_gestao_media_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	plano_gestao_media_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_gestao_media_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_gestao_media_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_gestao_media_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_gestao_media_peso DECIMAL(20,5) DEFAULT 0,
	plano_gestao_media_ponto DECIMAL(20,5) DEFAULT 0,
	plano_gestao_media_tipo VARCHAR(40) DEFAULT NULL,
	plano_gestao_media_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_gestao_media_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (plano_gestao_media_id),
	KEY plano_gestao_media_plano_gestao (plano_gestao_media_plano_gestao),
	KEY plano_gestao_media_perspectiva (plano_gestao_media_perspectiva),
	KEY plano_gestao_media_projeto (plano_gestao_media_projeto),
	KEY plano_gestao_media_acao (plano_gestao_media_acao),
	CONSTRAINT plano_gestao_media_plano_gestao FOREIGN KEY (plano_gestao_media_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_gestao_media_perspectiva FOREIGN KEY (plano_gestao_media_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_gestao_media_projeto FOREIGN KEY (plano_gestao_media_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_gestao_media_acao FOREIGN KEY (plano_gestao_media_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
		
ALTER TABLE projeto_observador ADD COLUMN projeto_observador_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_observador ADD KEY projeto_observador_plano_gestao (projeto_observador_plano_gestao);
ALTER TABLE projeto_observador ADD CONSTRAINT projeto_observador_plano_gestao FOREIGN KEY (projeto_observador_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE SET NULL ON UPDATE CASCADE;	

ALTER TABLE plano_acao_observador ADD COLUMN plano_acao_observador_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_observador ADD KEY plano_acao_observador_plano_gestao (plano_acao_observador_plano_gestao);
ALTER TABLE plano_acao_observador ADD CONSTRAINT plano_acao_observador_plano_gestao FOREIGN KEY (plano_acao_observador_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE IF EXISTS perspectiva_observador;

CREATE TABLE perspectiva_observador (
  perspectiva_observador_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perspectiva_observador_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_observador_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_observador_acao VARCHAR(30) DEFAULT 'fisico',
  perspectiva_observador_metodo VARCHAR(255) DEFAULT NULL,
  perspectiva_observador_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (perspectiva_observador_id),
  KEY perspectiva_observador_perspectiva (perspectiva_observador_perspectiva),
  KEY perspectiva_observador_plano_gestao (perspectiva_observador_plano_gestao),
  CONSTRAINT perspectiva_observador_perspectiva FOREIGN KEY (perspectiva_observador_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT perspectiva_observador_plano_gestao FOREIGN KEY (perspectiva_observador_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;		