SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-02';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-02';
UPDATE versao SET versao_bd=414;

DROP TABLE IF EXISTS baseline_projeto_custo;

CREATE TABLE baseline_projeto_custo (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_custo_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_custo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_nome VARCHAR(255) DEFAULT NULL,
  projeto_custo_codigo VARCHAR(255) DEFAULT NULL,
  projeto_custo_fonte VARCHAR(255) DEFAULT NULL,
  projeto_custo_regiao VARCHAR(255) DEFAULT NULL,
  projeto_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  projeto_custo_data DATETIME DEFAULT NULL,
  projeto_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  projeto_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  projeto_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	projeto_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	projeto_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	projeto_custo_data_moeda DATE DEFAULT NULL,
  projeto_custo_percentagem TINYINT(4) DEFAULT 0,
  projeto_custo_descricao TEXT,
  projeto_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_nd VARCHAR(11) DEFAULT NULL,
  projeto_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  projeto_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  projeto_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  projeto_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	projeto_custo_data_limite DATE DEFAULT NULL,
	projeto_custo_pi VARCHAR(100) DEFAULT NULL,
	projeto_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_custo_aprovado TINYINT(1) DEFAULT NULL,
	projeto_custo_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_custo_id),
  CONSTRAINT baseline_projeto_custo FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
	