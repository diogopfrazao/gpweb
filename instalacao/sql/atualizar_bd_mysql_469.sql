SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-02-13';
UPDATE versao SET ultima_atualizacao_codigo='2018-02-13';
UPDATE versao SET versao_bd=469;


DROP TABLE IF EXISTS pdcl;

CREATE TABLE pdcl (
  pdcl_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pdcl_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_nome VARCHAR(255) DEFAULT NULL,
  pdcl_descricao MEDIUMTEXT,
  pdcl_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pdcl_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pdcl_inicio DATETIME DEFAULT NULL,
	pdcl_fim DATETIME DEFAULT NULL,
	pdcl_duracao DECIMAL(20,5) UNSIGNED DEFAULT NULL,
	pdcl_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
	pdcl_calculo_porcentagem TINYINT(1) DEFAULT 0,
	pdcl_ano VARCHAR(4) DEFAULT NULL,
	pdcl_codigo VARCHAR(50) DEFAULT NULL,
	pdcl_setor VARCHAR(2) DEFAULT NULL,
	pdcl_segmento VARCHAR(4) DEFAULT NULL,
	pdcl_intervencao VARCHAR(6) DEFAULT NULL,
	pdcl_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	pdcl_sequencial INTEGER(100) DEFAULT NULL,
	pdcl_aprovado TINYINT(1) DEFAULT 0,
	pdcl_moeda INTEGER(100) UNSIGNED DEFAULT 1,
	pdcl_ativo TINYINT(1) DEFAULT 1,
	pdcl_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pdcl_id),
  KEY pdcl_cia (pdcl_cia),
  KEY pdcl_dept (pdcl_dept),
  KEY pdcl_responsavel (pdcl_responsavel),
  KEY pdcl_principal_indicador (pdcl_principal_indicador),
  KEY pdcl_moeda (pdcl_moeda),
  CONSTRAINT pdcl_cia FOREIGN KEY (pdcl_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_responsavel FOREIGN KEY (pdcl_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_principal_indicador FOREIGN KEY (pdcl_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_dept FOREIGN KEY (pdcl_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_moeda FOREIGN KEY (pdcl_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_cia;

CREATE TABLE pdcl_cia (
  pdcl_cia_pdcl INTEGER(100) UNSIGNED NOT NULL,
  pdcl_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_cia_pdcl, pdcl_cia_cia),
  KEY pdcl_cia_pdcl (pdcl_cia_pdcl),
  KEY pdcl_cia_cia (pdcl_cia_cia),
  CONSTRAINT pdcl_cia_cia FOREIGN KEY (pdcl_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_cia_pdcl FOREIGN KEY (pdcl_cia_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_contato;

CREATE TABLE pdcl_contato (
  pdcl_contato_pdcl INTEGER(100) UNSIGNED NOT NULL,
  pdcl_contato_contato INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_contato_pdcl, pdcl_contato_contato),
  KEY pdcl_contato_pdcl (pdcl_contato_pdcl),
  KEY pdcl_contato_contato (pdcl_contato_contato),
  CONSTRAINT pdcl_contato_pdcl FOREIGN KEY (pdcl_contato_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_contato_contato FOREIGN KEY (pdcl_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_dept;
CREATE TABLE pdcl_dept (
  pdcl_dept_pdcl int(100) unsigned NOT NULL,
  pdcl_dept_dept int(100) unsigned NOT NULL,
  PRIMARY KEY (pdcl_dept_pdcl, pdcl_dept_dept),
  KEY pdcl_dept_pdcl (pdcl_dept_pdcl),
  KEY pdcl_dept_dept (pdcl_dept_dept),
  CONSTRAINT pdcl_dept_pdcl FOREIGN KEY (pdcl_dept_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_dept_dept FOREIGN KEY (pdcl_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_usuario;

CREATE TABLE pdcl_usuario (
  pdcl_usuario_pdcl int(100) unsigned NOT NULL,
  pdcl_usuario_usuario int(100) unsigned NOT NULL,
  PRIMARY KEY (pdcl_usuario_pdcl, pdcl_usuario_usuario),
  KEY pdcl_usuario_pdcl (pdcl_usuario_pdcl),
  KEY pdcl_usuario_usuario (pdcl_usuario_usuario),
  CONSTRAINT pdcl_usuario_pdcl FOREIGN KEY (pdcl_usuario_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_usuario_usuario FOREIGN KEY (pdcl_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pdcl_item;

CREATE TABLE pdcl_item (
  pdcl_item_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pdcl_item_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_uuid VARCHAR(36) DEFAULT NULL,
  pdcl_item_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_nome VARCHAR(255) DEFAULT NULL,
  pdcl_item_fase INTEGER(10) DEFAULT 0,
  pdcl_item_descricao MEDIUMTEXT,
  pdcl_item_observacao MEDIUMTEXT,
  pdcl_item_inicio DATETIME DEFAULT NULL,
  pdcl_item_fim DATETIME DEFAULT NULL,
  pdcl_item_duracao DECIMAL(20,5) UNSIGNED DEFAULT NULL,
  pdcl_item_status INTEGER(10) DEFAULT 0,
  pdcl_item_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pdcl_item_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
	pdcl_item_peso DECIMAL(20,5) UNSIGNED DEFAULT 1,
	pdcl_item_moeda INTEGER(100) UNSIGNED DEFAULT 1,
	pdcl_item_cor VARCHAR(6) DEFAULT 'FFFFFF',
	pdcl_item_aprovado TINYINT(1) DEFAULT 0,
	pdcl_item_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (pdcl_item_id),
  KEY pdcl_item_pdcl (pdcl_item_pdcl),
  KEY pdcl_item_responsavel (pdcl_item_responsavel),
  KEY pdcl_item_cia (pdcl_item_cia),
  KEY pdcl_item_dept (pdcl_item_dept),
	KEY pdcl_item_principal_indicador (pdcl_item_principal_indicador),
	KEY pdcl_item_moeda (pdcl_item_moeda),
  CONSTRAINT pdcl_item_pdcl FOREIGN KEY (pdcl_item_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_responsavel FOREIGN KEY (pdcl_item_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_principal_indicador FOREIGN KEY (pdcl_item_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_cia FOREIGN KEY (pdcl_item_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_dept FOREIGN KEY (pdcl_item_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_moeda FOREIGN KEY (pdcl_item_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_item_contato;

CREATE TABLE pdcl_item_contato (
  pdcl_item_contato_pdcl_item INTEGER(100) UNSIGNED NOT NULL,
  pdcl_item_contato_contato INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_item_contato_pdcl_item, pdcl_item_contato_contato),
  KEY pdcl_item_contato_pdcl_item (pdcl_item_contato_pdcl_item),
  KEY pdcl_item_contato_contato (pdcl_item_contato_contato),
  CONSTRAINT pdcl_item_contato_contato FOREIGN KEY (pdcl_item_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_contato_pdcl_item FOREIGN KEY (pdcl_item_contato_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_item_dept;

CREATE TABLE pdcl_item_dept (
  pdcl_item_dept_pdcl_item INTEGER(100) UNSIGNED NOT NULL,
  pdcl_item_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_item_dept_pdcl_item, pdcl_item_dept_dept),
  KEY pdcl_item_dept_pdcl_item USING BTREE (pdcl_item_dept_pdcl_item),
  KEY pdcl_item_dept_dept USING BTREE (pdcl_item_dept_dept),
  CONSTRAINT pdcl_item_dept_dept FOREIGN KEY (pdcl_item_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_dept_pdcl_item FOREIGN KEY (pdcl_item_dept_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pdcl_item_cia;

CREATE TABLE pdcl_item_cia (
  pdcl_item_cia_pdcl_item INTEGER(100) UNSIGNED NOT NULL,
  pdcl_item_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_item_cia_pdcl_item, pdcl_item_cia_cia),
  KEY pdcl_item_cia_pdcl_item (pdcl_item_cia_pdcl_item),
  KEY pdcl_item_cia_cia (pdcl_item_cia_cia),
  CONSTRAINT pdcl_item_cia_cia FOREIGN KEY (pdcl_item_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_cia_pdcl_item FOREIGN KEY (pdcl_item_cia_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_item_custos;

CREATE TABLE pdcl_item_custos (
  pdcl_item_custos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pdcl_item_custos_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_custos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_custos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  pdcl_item_custos_nome VARCHAR(255) DEFAULT NULL,
  pdcl_item_custos_codigo VARCHAR(255) DEFAULT NULL,
  pdcl_item_custos_fonte VARCHAR(255) DEFAULT NULL,
  pdcl_item_custos_regiao VARCHAR(255) DEFAULT NULL,
  pdcl_item_custos_data DATETIME DEFAULT NULL,
  pdcl_item_custos_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_custos_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_custos_percentagem TINYINT(4) DEFAULT 0,
  pdcl_item_custos_descricao TEXT,
  pdcl_item_custos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_custos_nd VARCHAR(11) DEFAULT NULL,
  pdcl_item_custos_categoria_economica VARCHAR(1) DEFAULT NULL,
  pdcl_item_custos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pdcl_item_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pdcl_item_custos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_custos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pdcl_item_custos_data_limite DATE DEFAULT NULL,
  pdcl_item_custos_bdi  DECIMAL(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_custos_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	pdcl_item_custos_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000,
	pdcl_item_custos_data_moeda DATE DEFAULT NULL,
  pdcl_item_custos_uuid VARCHAR(36) DEFAULT NULL,
	pdcl_item_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_custos_aprovado TINYINT(1) DEFAULT NULL,
	pdcl_item_custos_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (pdcl_item_custos_id),
  KEY idxpdcl_item_custos_pdcl_item (pdcl_item_custos_pdcl_item),
  KEY idxpdcl_item_custos_usuario_inicio (pdcl_item_custos_usuario),
  KEY pdcl_item_custos_tr (pdcl_item_custos_tr),
  KEY idxpdcl_item_custos_ordem (pdcl_item_custos_ordem),
  KEY idxpdcl_item_custos_data_inicio (pdcl_item_custos_data),
  KEY idxpdcl_item_custos_nome (pdcl_item_custos_nome),
  KEY pdcl_item_custos_aprovou (pdcl_item_custos_aprovou),
  KEY pdcl_item_custos_moeda (pdcl_item_custos_moeda),
  CONSTRAINT pdcl_item_custos_pdcl_item FOREIGN KEY (pdcl_item_custos_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_custos_usuario FOREIGN KEY (pdcl_item_custos_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_custos_aprovou FOREIGN KEY (pdcl_item_custos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_custos_tr FOREIGN KEY (pdcl_item_custos_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_custos_moeda FOREIGN KEY (pdcl_item_custos_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_item_usuario;

CREATE TABLE pdcl_item_usuario (
  pdcl_item_usuario_item int(100) unsigned NOT NULL,
  pdcl_item_usuario_usuario int(100) unsigned NOT NULL,
  KEY pdcl_item_usuario_item (pdcl_item_usuario_item),
  KEY pdcl_item_usuario_usuario (pdcl_item_usuario_usuario),
  CONSTRAINT pdcl_item_usuario_item FOREIGN KEY (pdcl_item_usuario_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_usuario_usuario FOREIGN KEY (pdcl_item_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS pdcl_item_gastos;

CREATE TABLE pdcl_item_gastos (
  pdcl_item_gastos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pdcl_item_gastos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_gastos_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_gastos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  pdcl_item_gastos_nome VARCHAR(255) DEFAULT NULL,
  pdcl_item_gastos_codigo VARCHAR(255) DEFAULT NULL,
  pdcl_item_gastos_fonte VARCHAR(255) DEFAULT NULL,
  pdcl_item_gastos_regiao VARCHAR(255) DEFAULT NULL,
  pdcl_item_gastos_data DATETIME DEFAULT NULL,
  pdcl_item_gastos_data_recebido DATETIME DEFAULT NULL,
  pdcl_item_gastos_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_gastos_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_gastos_percentagem TINYINT(4) DEFAULT 0,
  pdcl_item_gastos_descricao TEXT,
  pdcl_item_gastos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_gastos_nd VARCHAR(11) DEFAULT NULL,
  pdcl_item_gastos_categoria_economica VARCHAR(1) DEFAULT NULL,
  pdcl_item_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pdcl_item_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pdcl_item_gastos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gastos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pdcl_item_custos_data_recebido DATE DEFAULT NULL,
  pdcl_item_gastos_empenhado DECIMAL(20,5) UNSIGNED DEFAULT 0,
	pdcl_item_gastos_entregue DECIMAL(20,5) UNSIGNED DEFAULT 0,
	pdcl_item_gastos_liquidado DECIMAL(20,5) UNSIGNED DEFAULT 0,
	pdcl_item_gastos_pago DECIMAL(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_gastos_bdi  DECIMAL(20,5) UNSIGNED DEFAULT 0,
	pdcl_item_gastos_moeda INTEGER(100) UNSIGNED DEFAULT 1,  
	pdcl_item_gastos_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	pdcl_item_gastos_data_moeda DATE DEFAULT NULL,  
  pdcl_item_gastos_uuid VARCHAR(36) DEFAULT NULL,
	pdcl_item_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gastos_aprovado TINYINT(1) DEFAULT NULL,
	pdcl_item_gastos_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (pdcl_item_gastos_id),
  KEY idxpdcl_item_gastos_pdcl_item (pdcl_item_gastos_pdcl_item),
  KEY idxpdcl_item_gastos_usuario_inicio (pdcl_item_gastos_usuario),
  KEY idxpdcl_item_gastos_ordem (pdcl_item_gastos_ordem),
  KEY idxpdcl_item_gastos_data_inicio (pdcl_item_gastos_data),
  KEY idxpdcl_item_gastos_nome (pdcl_item_gastos_nome),
  KEY pdcl_item_gastos_aprovou (pdcl_item_gastos_aprovou),
  KEY pdcl_item_gastos_moeda (pdcl_item_gastos_moeda),
  CONSTRAINT pdcl_item_gastos_pdcl_item FOREIGN KEY (pdcl_item_gastos_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_gastos_usuario FOREIGN KEY (pdcl_item_gastos_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_gastos_aprovou FOREIGN KEY (pdcl_item_gastos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_gastos_moeda FOREIGN KEY (pdcl_item_gastos_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE ata_config ADD COLUMN ata_config_numeracao_automatica TINYINT DEFAULT 0;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('PDCLItemStatusCor','c5eff4','0',NULL),
	('PDCLItemStatusCor','e4ee8b','1',NULL),
	('PDCLItemStatusCor','a0cc9d','2',NULL),
	('PDCLItemStatusCor','da908c','3',NULL),
	('PDCLItemStatusCor','e3c383','4',NULL),
	('PDCLItemStatusCor','f297f1','5',NULL),
	('PDCLItemStatus','Não Iniciado','0',NULL),
	('PDCLItemStatus','Pendente','1',NULL),
	('PDCLItemStatus','Concluído','2',NULL),
	('PDCLItemStatus','Não Realizado','3',NULL),
	('PDCLItemStatus','Em Andamento','4',NULL),
	('PDCLItemStatus','Em Atraso','5',NULL);