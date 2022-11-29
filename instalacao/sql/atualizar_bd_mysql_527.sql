SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-09-22';
UPDATE versao SET ultima_atualizacao_codigo='2019-09-22';
UPDATE versao SET versao_bd=527;


ALTER TABLE instrumento ADD COLUMN instrumento_fiscal int(100) unsigned DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_fiscal_substituto int(100) unsigned DEFAULT NULL;

ALTER TABLE instrumento ADD KEY instrumento_fiscal (instrumento_fiscal);
ALTER TABLE instrumento ADD KEY instrumento_fiscal_substituto (instrumento_fiscal_substituto);

ALTER TABLE instrumento ADD CONSTRAINT instrumento_fiscal FOREIGN KEY (instrumento_fiscal) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE instrumento ADD CONSTRAINT instrumento_fiscal_substituto FOREIGN KEY (instrumento_fiscal_substituto) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE instrumento ADD COLUMN instrumento_prorrogavel TINYINT(1) DEFAULT 0;

ALTER TABLE instrumento ADD COLUMN instrumento_prazo_prorrogacao DATE DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_acrescimo DECIMAL(20,5) DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_local_entrega TEXT;
ALTER TABLE instrumento ADD COLUMN instrumento_resultado_esperado TEXT;
ALTER TABLE instrumento ADD COLUMN instrumento_vantagem_economica TEXT;
ALTER TABLE instrumento ADD COLUMN instrumento_data_requerimento DATE NULL;

DROP TABLE IF EXISTS instrumento_financeiro;
	
CREATE TABLE instrumento_financeiro (
	instrumento_financeiro_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	instrumento_financeiro_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_financeiro_projeto TEXT,
	instrumento_financeiro_tarefa TEXT,
	instrumento_financeiro_fonte VARCHAR(255) DEFAULT NULL,
	instrumento_financeiro_regiao VARCHAR(255) DEFAULT NULL,
	instrumento_financeiro_classificacao VARCHAR(255) DEFAULT NULL,
	instrumento_financeiro_valor DECIMAL(20,5) UNSIGNED DEFAULT 0,
	instrumento_financeiro_ano INTEGER(4) UNSIGNED DEFAULT NULL,
	instrumento_financeiro_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_financeiro_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (instrumento_financeiro_id),
	KEY instrumento_financeiro_instrumento (instrumento_financeiro_instrumento),
	CONSTRAINT instrumento_financeiro_instrumento FOREIGN KEY (instrumento_financeiro_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS instrumento_avulso_custo;

CREATE TABLE instrumento_avulso_custo (
  instrumento_avulso_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_avulso_custo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_avulso_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_avulso_custo_nome VARCHAR(255) DEFAULT NULL,
  instrumento_avulso_custo_codigo VARCHAR(255) DEFAULT NULL,
  instrumento_avulso_custo_fonte VARCHAR(255) DEFAULT NULL,
  instrumento_avulso_custo_regiao VARCHAR(255) DEFAULT NULL,
  instrumento_avulso_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  instrumento_avulso_custo_data DATETIME DEFAULT NULL,
  instrumento_avulso_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  instrumento_avulso_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  instrumento_avulso_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	instrumento_avulso_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	instrumento_avulso_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	instrumento_avulso_custo_data_moeda DATE DEFAULT NULL,
  instrumento_avulso_custo_percentagem TINYINT(4) DEFAULT 0,
  instrumento_avulso_custo_descricao MEDIUMTEXT,
  instrumento_avulso_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_avulso_custo_nd VARCHAR(11) DEFAULT NULL,
  instrumento_avulso_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  instrumento_avulso_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  instrumento_avulso_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  instrumento_avulso_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_avulso_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	instrumento_avulso_custo_data_limite DATE DEFAULT NULL,
	instrumento_avulso_custo_pi VARCHAR(100) DEFAULT NULL,
	instrumento_avulso_custo_ptres VARCHAR(100) DEFAULT NULL,
	instrumento_avulso_custo_siag VARCHAR(100) DEFAULT NULL,
	instrumento_avulso_custo_meses INTEGER(4) UNSIGNED DEFAULT NULL,	
	instrumento_avulso_custo_servico TINYINT(1) DEFAULT 0,
	instrumento_avulso_custo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (instrumento_avulso_custo_id),
  KEY instrumento_avulso_custo_instrumento (instrumento_avulso_custo_instrumento),
  KEY instrumento_avulso_custo_usuario_inicio (instrumento_avulso_custo_usuario),
  KEY instrumento_avulso_custo_ordem (instrumento_avulso_custo_ordem),
  KEY instrumento_avulso_custo_data_inicio (instrumento_avulso_custo_data),
  KEY instrumento_avulso_custo_nome (instrumento_avulso_custo_nome),
  KEY instrumento_avulso_custo_moeda (instrumento_avulso_custo_moeda),
  CONSTRAINT instrumento_avulso_custo_usuario FOREIGN KEY (instrumento_avulso_custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_avulso_custo_instrumento FOREIGN KEY (instrumento_avulso_custo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_avulso_custo_moeda FOREIGN KEY (instrumento_avulso_custo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_custo;

CREATE TABLE instrumento_custo (
  instrumento_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_custo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_custo_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_custo_avulso INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_custo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_custo_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
	instrumento_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_custo_aprovado TINYINT(1) DEFAULT NULL,
	instrumento_custo_data_aprovado DATETIME DEFAULT NULL,
	instrumento_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_custo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (instrumento_custo_id),
  KEY instrumento_custo_instrumento (instrumento_custo_instrumento),
  KEY instrumento_custo_tarefa (instrumento_custo_tarefa),
  KEY instrumento_custo_avulso (instrumento_custo_avulso),
  KEY instrumento_custo_acao (instrumento_custo_acao),
  KEY instrumento_custo_demanda (instrumento_custo_demanda),
  KEY instrumento_custo_aprovou (instrumento_custo_aprovou),
  CONSTRAINT instrumento_custo_instrumento FOREIGN KEY (instrumento_custo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_custo_tarefa FOREIGN KEY (instrumento_custo_tarefa) REFERENCES tarefa_custos (tarefa_custos_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_custo_avulso FOREIGN KEY (instrumento_custo_avulso) REFERENCES instrumento_avulso_custo (instrumento_avulso_custo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT instrumento_custo_acao FOREIGN KEY (instrumento_custo_acao) REFERENCES plano_acao_item_custos (plano_acao_item_custos_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT instrumento_custo_demanda FOREIGN KEY (instrumento_custo_demanda) REFERENCES demanda_custo (demanda_custo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_custo_aprovou FOREIGN KEY (instrumento_custo_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('instrumento_fonte','exemplo01','exemplo01',NULL),
	('instrumento_fonte','exemplo02','exemplo02',NULL);