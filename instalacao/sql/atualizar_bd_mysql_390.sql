SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.00';
UPDATE versao SET ultima_atualizacao_bd='2017-02-01';
UPDATE versao SET ultima_atualizacao_codigo='2017-02-01';
UPDATE versao SET versao_bd=390;

ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_custo DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_moeda INTEGER(100) UNSIGNED DEFAULT 1;  
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000; 
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_data_moeda DATE DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_regiao VARCHAR(255) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_nd VARCHAR(11) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_metodo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_tarefa ADD COLUMN recurso_tarefa_exercicio INTEGER(4) UNSIGNED DEFAULT NULL;



DROP TABLE IF EXISTS baseline_recurso_tarefa_custo;

CREATE TABLE baseline_recurso_tarefa_custo (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_tarefa_custo_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_tarefa_custo_recurso_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  recurso_tarefa_custo_nome VARCHAR(255) DEFAULT NULL,
  recurso_tarefa_custo_codigo VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_custo_fonte VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_custo_regiao VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	recurso_tarefa_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	recurso_tarefa_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000,
	recurso_tarefa_custo_data_moeda DATE DEFAULT NULL, 	
  recurso_tarefa_custo_data DATETIME DEFAULT NULL,
  recurso_tarefa_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  recurso_tarefa_custo_valor DECIMAL(20,5) UNSIGNED DEFAULT 0,
  recurso_tarefa_custo_percentagem TINYINT(4) DEFAULT 0,
  recurso_tarefa_custo_descricao TEXT,
  recurso_tarefa_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_nd VARCHAR(11) DEFAULT NULL,
  recurso_tarefa_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  recurso_tarefa_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  recurso_tarefa_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  recurso_tarefa_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	recurso_tarefa_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_data_limite DATE DEFAULT NULL,
  PRIMARY KEY (baseline_id, recurso_tarefa_custo_id),
  CONSTRAINT baseline_recurso_tarefa_custo FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS baseline_recurso_tarefa;

CREATE TABLE baseline_recurso_tarefa (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_tarefa_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_evento INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_insercao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  recurso_tarefa_inicio DATETIME,
  recurso_tarefa_fim DATETIME,
  recurso_tarefa_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0,
  recurso_tarefa_aprovou INTEGER(100)UNSIGNED DEFAULT NULL,
  recurso_tarefa_aprovado TINYINT(1) DEFAULT NULL,
  recurso_tarefa_data DATETIME,
  recurso_tarefa_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0.000,
	recurso_tarefa_valor_hora DECIMAL(20,5) UNSIGNED DEFAULT 0,
	recurso_tarefa_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
	recurso_tarefa_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	recurso_tarefa_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000,
	recurso_tarefa_data_moeda DATE DEFAULT NULL,
	recurso_tarefa_codigo VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_fonte VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_regiao VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	recurso_tarefa_nd VARCHAR(11) DEFAULT NULL,
  recurso_tarefa_categoria_economica VARCHAR(1) DEFAULT NULL,
  recurso_tarefa_grupo_despesa VARCHAR(1) DEFAULT NULL,
  recurso_tarefa_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  recurso_tarefa_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	recurso_tarefa_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	recurso_tarefa_obs TEXT,
	recurso_tarefa_corrido TINYINT(1) NOT NULL DEFAULT 0,
  recurso_tarefa_percentual INTEGER UNSIGNED NULL DEFAULT 100,
  recurso_tarefa_ordem INT(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (baseline_id, recurso_tarefa_id),
  CONSTRAINT baseline_recurso_tarefa FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

