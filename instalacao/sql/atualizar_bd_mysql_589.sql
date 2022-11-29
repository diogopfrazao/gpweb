SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.28';
UPDATE versao SET ultima_atualizacao_bd='2020-10-26';
UPDATE versao SET ultima_atualizacao_codigo='2020-10-26';
UPDATE versao SET versao_bd=589;



DROP TABLE IF EXISTS alteracao;

CREATE TABLE alteracao (
  alteracao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  alteracao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  alteracao_identificador INTEGER(100) UNSIGNED DEFAULT NULL,
	alteracao_modulo VARCHAR(30),
	alteracao_nome VARCHAR(255) DEFAULT NULL,
  alteracao_data DATETIME,
  alteracao_tipo VARCHAR(7) DEFAULT NULL,
  PRIMARY KEY (alteracao_id),
  KEY alteracao_usuario (alteracao_usuario),
  CONSTRAINT alteracao_usuario FOREIGN KEY (alteracao_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE instrumento MODIFY instrumento_garantia_contratual_percentual DECIMAL(20,10) DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_valor DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_valor_atual DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_valor_contrapartida DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_valor_repasse DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_acrescimo DECIMAL(20,10) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento MODIFY instrumento_supressao DECIMAL(20,10) UNSIGNED DEFAULT NULL;


ALTER TABLE instrumento_financeiro MODIFY instrumento_financeiro_valor DECIMAL(20,10) UNSIGNED DEFAULT 0;

ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_quantidade DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_custo DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_custo_atual DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_bdi DECIMAL(20,10) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_cotacao DECIMAL(20,10) UNSIGNED DEFAULT 1;
ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_acrescimo DECIMAL(20,10) DEFAULT 0;

ALTER TABLE instrumento_custo MODIFY instrumento_custo_quantidade DECIMAL(20,10) UNSIGNED DEFAULT 0;
