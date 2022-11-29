SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.12';
UPDATE versao SET ultima_atualizacao_bd='2017-11-06';
UPDATE versao SET ultima_atualizacao_codigo='2017-11-06';
UPDATE versao SET versao_bd=458;

DROP TABLE IF EXISTS pratica_indicador_prazo;

CREATE TABLE pratica_indicador_prazo (
  pratica_indicador_prazo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_prazo_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_prazo_insercao_inicio DATE DEFAULT NULL,
  pratica_indicador_prazo_insercao_fim DATE DEFAULT NULL,
  pratica_indicador_prazo_valor_inicio DATE DEFAULT NULL,
  pratica_indicador_prazo_valor_fim DATE DEFAULT NULL,
  pratica_indicador_prazo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pratica_indicador_prazo_id),
  KEY pratica_indicador_prazo_indicador (pratica_indicador_prazo_indicador),
  CONSTRAINT pratica_indicador_prazo_indicador FOREIGN KEY (pratica_indicador_prazo_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;