SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-01-24';
UPDATE versao SET ultima_atualizacao_codigo='2019-01-24';
UPDATE versao SET versao_bd=545;


DROP TABLE IF EXISTS os_avulso_custo;

CREATE TABLE os_avulso_custo (
  os_avulso_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  os_avulso_custo_os INTEGER(100) UNSIGNED DEFAULT NULL,
  os_avulso_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  os_avulso_custo_nome VARCHAR(255) DEFAULT NULL,
  os_avulso_custo_codigo VARCHAR(255) DEFAULT NULL,
  os_avulso_custo_fonte VARCHAR(255) DEFAULT NULL,
  os_avulso_custo_regiao VARCHAR(255) DEFAULT NULL,
  os_avulso_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  os_avulso_custo_data DATETIME DEFAULT NULL,
  os_avulso_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  os_avulso_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  os_avulso_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	os_avulso_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	os_avulso_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	os_avulso_custo_data_moeda DATE DEFAULT NULL,
  os_avulso_custo_percentagem TINYINT(1) DEFAULT 0,
  os_avulso_custo_descricao MEDIUMTEXT,
  os_avulso_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  os_avulso_custo_nd VARCHAR(11) DEFAULT NULL,
  os_avulso_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  os_avulso_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  os_avulso_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  os_avulso_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	os_avulso_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	os_avulso_custo_data_limite DATE DEFAULT NULL,
	os_avulso_custo_pi VARCHAR(100) DEFAULT NULL,
	os_avulso_custo_ptres VARCHAR(100) DEFAULT NULL,
	os_avulso_custo_siag VARCHAR(100) DEFAULT NULL,
	os_avulso_custo_meses INTEGER(4) UNSIGNED DEFAULT NULL,	
	os_avulso_custo_servico TINYINT(1) DEFAULT 0,
	os_avulso_custo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (os_avulso_custo_id),
  KEY os_avulso_custo_os (os_avulso_custo_os),
  KEY os_avulso_custo_usuario_inicio (os_avulso_custo_usuario),
  KEY os_avulso_custo_ordem (os_avulso_custo_ordem),
  KEY os_avulso_custo_data_inicio (os_avulso_custo_data),
  KEY os_avulso_custo_nome (os_avulso_custo_nome),
  KEY os_avulso_custo_moeda (os_avulso_custo_moeda),
  CONSTRAINT os_avulso_custo_usuario FOREIGN KEY (os_avulso_custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_avulso_custo_os FOREIGN KEY (os_avulso_custo_os) REFERENCES os (os_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_avulso_custo_moeda FOREIGN KEY (os_avulso_custo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS os_custo;

CREATE TABLE os_custo (
  os_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  os_custo_os INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_avulso INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  os_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
	os_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	os_custo_aprovado TINYINT(1) DEFAULT NULL,
	os_custo_data_aprovado DATETIME DEFAULT NULL,
	os_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	os_custo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (os_custo_id),
  KEY os_custo_os (os_custo_os),
  KEY os_custo_tarefa (os_custo_tarefa),
  KEY os_custo_avulso (os_custo_avulso),
  KEY os_custo_acao (os_custo_acao),
  KEY os_custo_demanda (os_custo_demanda),
  KEY os_custo_tr (os_custo_tr),
  KEY os_custo_instrumento (os_custo_instrumento),
  KEY os_custo_aprovou (os_custo_aprovou),
  CONSTRAINT os_custo_os FOREIGN KEY (os_custo_os) REFERENCES os (os_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_custo_tarefa FOREIGN KEY (os_custo_tarefa) REFERENCES tarefa_custos (tarefa_custos_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT os_custo_avulso FOREIGN KEY (os_custo_avulso) REFERENCES os_avulso_custo (os_avulso_custo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_custo_acao FOREIGN KEY (os_custo_acao) REFERENCES plano_acao_item_custos (plano_acao_item_custos_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_custo_demanda FOREIGN KEY (os_custo_demanda) REFERENCES demanda_custo (demanda_custo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_custo_tr FOREIGN KEY (os_custo_tr) REFERENCES tr_custo (tr_custo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_custo_instrumento FOREIGN KEY (os_custo_instrumento) REFERENCES instrumento_custo (instrumento_custo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT os_custo_aprovou FOREIGN KEY (os_custo_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



