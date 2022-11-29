SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-09-27';
UPDATE versao SET ultima_atualizacao_codigo='2019-09-27';
UPDATE versao SET versao_bd=528;



DROP TABLE IF EXISTS tr_fornecedor;

CREATE TABLE tr_fornecedor (
  tr_fornecedor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tr_fornecedor_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  tr_fornecedor_empresa VARCHAR(255) DEFAULT NULL,
  tr_fornecedor_cnpj VARCHAR(18) DEFAULT NULL,
  tr_fornecedor_vencimento_vigente DATE DEFAULT NULL,
  tr_fornecedor_nr_contrato VARCHAR(255) DEFAULT NULL,
  tr_fornecedor_valor DECIMAL(20,5) UNSIGNED DEFAULT 0,
  tr_fornecedor_data_encerramento DATE DEFAULT null,
  tr_fornecedor_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_fornecedor_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (tr_fornecedor_id),
  KEY tr_fornecedor_tr (tr_fornecedor_tr),
 CONSTRAINT tr_fornecedor_tr FOREIGN KEY (tr_fornecedor_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE tr ADD COLUMN tr_protocolo_data DATE DEFAULT null;

