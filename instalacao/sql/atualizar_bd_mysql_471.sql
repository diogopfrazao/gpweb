SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-02-22';
UPDATE versao SET ultima_atualizacao_codigo='2018-02-22';
UPDATE versao SET versao_bd=471;


ALTER TABLE projeto_observador ADD COLUMN projeto_observador_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_observador ADD KEY projeto_observador_pdcl_item (projeto_observador_pdcl_item);
ALTER TABLE projeto_observador ADD CONSTRAINT projeto_observador_pdcl_item FOREIGN KEY (projeto_observador_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE plano_acao_observador ADD COLUMN plano_acao_observador_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_observador ADD KEY plano_acao_observador_pdcl_item (plano_acao_observador_pdcl_item);
ALTER TABLE plano_acao_observador ADD CONSTRAINT plano_acao_observador_pdcl_item FOREIGN KEY (plano_acao_observador_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS pdcl_item_observador;

CREATE TABLE pdcl_item_observador (
  pdcl_item_observador_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	pdcl_item_observador_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_observador_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_observador_acao VARCHAR(30) DEFAULT 'fisico',
  pdcl_item_observador_metodo VARCHAR(255) DEFAULT NULL,
  pdcl_item_observador_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pdcl_item_observador_id),
 	KEY pdcl_item_observador_pdcl_item (pdcl_item_observador_pdcl_item),
 	KEY pdcl_item_observador_pdcl (pdcl_item_observador_pdcl),
	CONSTRAINT pdcl_item_observador_pdcl_item FOREIGN KEY (pdcl_item_observador_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_observador_pdcl FOREIGN KEY (pdcl_item_observador_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;	

DROP TABLE IF EXISTS pdcl_item_media;

CREATE TABLE pdcl_item_media (
	pdcl_item_media_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	pdcl_item_media_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_media_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_media_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_media_peso DECIMAL(20,5) DEFAULT 0,
	pdcl_item_media_ponto DECIMAL(20,5) DEFAULT 0,
	pdcl_item_media_tipo VARCHAR(40) DEFAULT NULL,
	pdcl_item_media_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_media_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (pdcl_item_media_id),
	KEY pdcl_item_media_pdcl_item (pdcl_item_media_pdcl_item),
	KEY pdcl_item_media_projeto (pdcl_item_media_projeto),
	KEY pdcl_item_media_acao (pdcl_item_media_acao),
	CONSTRAINT pdcl_item_media_pdcl_item FOREIGN KEY (pdcl_item_media_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_media_projeto FOREIGN KEY (pdcl_item_media_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_media_acao FOREIGN KEY (pdcl_item_media_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;		
	
	
ALTER TABLE pdcl_item ADD COLUMN pdcl_item_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE pdcl_item ADD COLUMN  pdcl_item_ponto_alvo DECIMAL(20,5) UNSIGNED DEFAULT 0;