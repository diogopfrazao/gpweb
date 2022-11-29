SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-01-23';
UPDATE versao SET ultima_atualizacao_codigo='2019-01-23';
UPDATE versao SET versao_bd=543;


DROP TABLE IF EXISTS tr_entrega;

CREATE TABLE tr_entrega (
	tr_entrega_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tr_entrega_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  tr_entrega_tipo INTEGER(10) DEFAULT 0,
  tr_entrega_obs VARCHAR(255) DEFAULT NULL,
  tr_entrega_uuid VARCHAR(36) DEFAULT NULL,
  tr_entrega_ordem INTEGER(100) unsigned DEFAULT NULL,
  PRIMARY KEY (tr_entrega_id),
  KEY tr_entrega_tr (tr_entrega_tr),
  CONSTRAINT tr_entrega_tr FOREIGN KEY (tr_entrega_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;