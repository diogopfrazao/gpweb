SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-25';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-25';
UPDATE versao SET versao_bd=568;

DROP TABLE IF EXISTS numeracao;

CREATE TABLE numeracao (
  numeracao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  numeracao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
	numeracao_modulo VARCHAR(50) DEFAULT NULL,
  numeracao_ano INTEGER(4) UNSIGNED DEFAULT NULL,
  numeracao_numero INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (numeracao_id),
  KEY numeracao_cia (numeracao_cia),
  KEY numeracao_modulo (numeracao_modulo),
  KEY numeracao_ano (numeracao_ano),
  KEY numeracao_numero (numeracao_numero),
  CONSTRAINT numeracao_cia FOREIGN KEY (numeracao_cia) REFERENCES cias (cia_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
