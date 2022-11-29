SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.10';
UPDATE versao SET ultima_atualizacao_bd='2017-10-10';
UPDATE versao SET ultima_atualizacao_codigo='2017-10-10';
UPDATE versao SET versao_bd=451;

DROP TABLE IF EXISTS pg_swot;

CREATE TABLE pg_swot (
  pg_swot_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_swot_pg INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_swot_swot INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_swot_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_swot_nome TEXT,
 	pg_swot_tipo VARCHAR(1) DEFAULT NULL,
  pg_swot_data DATETIME DEFAULT NULL,
  pg_swot_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_swot_id),
  KEY pg_swot_pg (pg_swot_pg),
  KEY pg_swot_swot (pg_swot_swot),
  KEY pg_swot_usuario (pg_swot_usuario),
  CONSTRAINT pg_swot_pg FOREIGN KEY (pg_swot_pg) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pg_swot_usuario FOREIGN KEY (pg_swot_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pg_swot_swot FOREIGN KEY (pg_swot_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;