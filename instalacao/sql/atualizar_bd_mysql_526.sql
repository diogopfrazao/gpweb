SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.26';
UPDATE versao SET ultima_atualizacao_bd='2019-09-19';
UPDATE versao SET ultima_atualizacao_codigo='2019-09-19';
UPDATE versao SET versao_bd=526;

ALTER TABLE sisvalores ADD COLUMN sisvalor_ativo TINYINT(1) DEFAULT 1;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('tr_fonte','exemplo01','exemplo01',NULL),
	('tr_fonte','exemplo02','exemplo02',NULL);
	

	
DROP TABLE IF EXISTS tr_financeiro;
	
CREATE TABLE tr_financeiro (
	tr_financeiro_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_financeiro_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_financeiro_projeto TEXT,
	tr_financeiro_fonte VARCHAR(255) DEFAULT NULL,
	tr_financeiro_regiao VARCHAR(255) DEFAULT NULL,
	tr_financeiro_classificacao VARCHAR(255) DEFAULT NULL,
	tr_financeiro_valor DECIMAL(20,5) UNSIGNED DEFAULT 0,
	tr_financeiro_ano INTEGER(4) UNSIGNED DEFAULT NULL,
	tr_financeiro_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_financeiro_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (tr_financeiro_id),
	KEY tr_financeiro_tr (tr_financeiro_tr),
	CONSTRAINT tr_financeiro_tr FOREIGN KEY (tr_financeiro_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



ALTER TABLE tr_avulso_custo ADD COLUMN tr_avulso_custo_siag	VARCHAR(100) DEFAULT NULL;
ALTER TABLE tr_avulso_custo ADD COLUMN tr_avulso_custo_meses INTEGER(4) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_avulso_custo ADD COLUMN tr_avulso_custo_servico TINYINT(1) DEFAULT 0;
ALTER TABLE tr_avulso_custo ADD COLUMN tr_avulso_custo_uuid VARCHAR(36) DEFAULT NULL;


ALTER TABLE tr_custo ADD COLUMN tr_custo_uuid VARCHAR(36) DEFAULT NULL;