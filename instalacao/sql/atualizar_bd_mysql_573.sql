SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-07-15';
UPDATE versao SET ultima_atualizacao_codigo='2020-07-15';
UPDATE versao SET versao_bd=573;


DROP TABLE IF EXISTS financeiro_estorno_ne_fiplan;

CREATE TABLE financeiro_estorno_ne_fiplan(
	financeiro_estorno_ne_fiplan_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	financeiro_estorno_ne_fiplan_cia INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ne_fiplan_dept INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ne_fiplan_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ne_fiplan_cor VARCHAR(6) DEFAULT 'FFFFFF',
	financeiro_estorno_ne_fiplan_acesso INTEGER(100) UNSIGNED DEFAULT 0,
	financeiro_estorno_ne_fiplan_ativo TINYINT(1) DEFAULT 1,
	CD_EXERCICIO VARCHAR(4) DEFAULT NULL,
	CD_UNIDADE_ORCAMENTARIA VARCHAR(5) DEFAULT NULL,
	DS_UNIDADE_ORCAMENTARIA VARCHAR(100) DEFAULT NULL,
	CD_UNIDADE_GESTORA VARCHAR(4) DEFAULT NULL,
	DS_UNIDADE_GESTORA VARCHAR(100) DEFAULT NULL,
	NUMR_EMP_ESTORNO VARCHAR(18) DEFAULT NULL,
	DATA_EMISSAO_ESTORNO DATE DEFAULT NULL,
	NUMR_PED VARCHAR(18) DEFAULT NULL,
	NUMR_NOBLIST VARCHAR(18) DEFAULT NULL,
	NUMR_DOTLIST VARCHAR(18) DEFAULT NULL,
	CD_PAOE VARCHAR(4) DEFAULT NULL,
	DS_PAOE VARCHAR(100) DEFAULT NULL,
	TIPO_RECURSO VARCHAR(100) DEFAULT NULL,
	TIPO_EMP VARCHAR(100) DEFAULT NULL,
	MODALIDADE_LICITACAO VARCHAR(100) DEFAULT NULL,
	NUMR_REF_LICITACAO VARCHAR(20) DEFAULT NULL,
	MOTIVO_DISPENSA VARCHAR(100) DEFAULT NULL,
	NUMR_CONVENIO VARCHAR(35) DEFAULT NULL,
	TRANSF_RESTOS_PAGAR VARCHAR(3) DEFAULT NULL,
	NUMR_PROTOCOLO VARCHAR(8) DEFAULT NULL,
	CD_EXERCICIO_PROTOCOLO VARCHAR(4) DEFAULT NULL,
	CBA_IRP VARCHAR(10) DEFAULT NULL,
	DESCRICAO_CBA_IRP VARCHAR(100) DEFAULT NULL,
	IDEN_CREDOR VARCHAR(10) DEFAULT NULL,
	NOME_CREDOR VARCHAR(100) DEFAULT NULL,
	CNPJ VARCHAR(14) DEFAULT NULL,
	CPF VARCHAR(11) DEFAULT NULL,
	INSCRICAO_ESTADUAL VARCHAR(13) DEFAULT NULL,
	NUMR_OS VARCHAR(18) DEFAULT NULL,
	DATA_INICIO_VIAGEM DATE DEFAULT NULL,
	DATA_RETORNO_VIAGEM DATE DEFAULT NULL,
	NUMR_PAD VARCHAR(18) DEFAULT NULL,
	DATA_PAD DATE DEFAULT NULL,
	CODG_DOTACAO_ORCAMENTARIA VARCHAR(48) DEFAULT NULL,
	CODG_ELEMENTO_DESPESA VARCHAR(03) DEFAULT NULL,
	DS_ELEMENTO_DESPESA VARCHAR(100) DEFAULT NULL,
	VALR_ESTORNO DECIMAL(15,2) DEFAULT NULL,
	MOTIVO_ESTORNO VARCHAR(400) DEFAULT NULL,
	DATA_AUTORIZACAO DATE DEFAULT NULL,
	NOME_ORDENADOR_DESPESA VARCHAR(100) DEFAULT NULL,
	NUMR_DOCUMENTO_ESTORNADO VARCHAR(18) DEFAULT NULL,
	VALR_EMP DECIMAL(15,2) DEFAULT NULL,
	SITUACAO_EMP VARCHAR(100) DEFAULT NULL,
 	PRIMARY KEY (financeiro_estorno_ne_fiplan_id),
  KEY financeiro_estorno_ne_fiplan_cia (financeiro_estorno_ne_fiplan_cia),
  KEY financeiro_estorno_ne_fiplan_dept (financeiro_estorno_ne_fiplan_dept),
  KEY financeiro_estorno_ne_fiplan_responsavel (financeiro_estorno_ne_fiplan_responsavel),
  CONSTRAINT financeiro_estorno_ne_fiplan_dept FOREIGN KEY (financeiro_estorno_ne_fiplan_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_ne_fiplan_cia FOREIGN KEY (financeiro_estorno_ne_fiplan_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_ne_fiplan_responsavel FOREIGN KEY (financeiro_estorno_ne_fiplan_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;	
	
	
	
DROP TABLE IF EXISTS financeiro_estorno_ns_fiplan;

CREATE TABLE financeiro_estorno_ns_fiplan(
	financeiro_estorno_ns_fiplan_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	financeiro_estorno_ns_fiplan_cia INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ns_fiplan_dept INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ns_fiplan_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ns_fiplan_cor VARCHAR(6) DEFAULT 'FFFFFF',
	financeiro_estorno_ns_fiplan_acesso INTEGER(100) UNSIGNED DEFAULT 0,
	financeiro_estorno_ns_fiplan_ativo TINYINT(1) DEFAULT 1,
	CD_EXERCICIO VARCHAR(4) DEFAULT NULL,
	CD_ORGAO VARCHAR(2) DEFAULT NULL,
	DS_ORGAO VARCHAR(100) DEFAULT NULL,
	CD_UNIDADE_ORCAMENTARIA VARCHAR(5) DEFAULT NULL,
	DS_UNIDADE_ORCAMENTARIA VARCHAR(100) DEFAULT NULL,
	CD_UNIDADE_GESTORA VARCHAR(4) DEFAULT NULL,
	DS_UNIDADE_GESTORA VARCHAR(100) DEFAULT NULL,
	NUMR_LIQ VARCHAR(18) DEFAULT NULL,
	DATA_LIQ DATE DEFAULT NULL,
	DATA_VENCIMENTO DATE DEFAULT NULL,
	NUMR_EMP VARCHAR(18) DEFAULT NULL,
	NUMR_PED VARCHAR(18) DEFAULT NULL,
	NUMR_PAD VARCHAR(18) DEFAULT NULL,
	NUMR_NOBLIST VARCHAR(18) DEFAULT NULL,
	NUMR_DOTLIST VARCHAR(18) DEFAULT NULL,
	DATA_LIBERACAO DATE DEFAULT NULL,
	NOME_LIBERADOR VARCHAR(100) DEFAULT NULL,
	LIQUIDACAO_ESCRITURAL VARCHAR(3) DEFAULT NULL,
	REGULARIZACAO_PAGAMENTO VARCHAR(100) DEFAULT NULL,
	CODG_DOTACAO_ORCAMENTARIA VARCHAR(48) DEFAULT NULL,
	CD_ELEMENTO_DESPESA VARCHAR(2) DEFAULT NULL,
	DS_ELEMENTO_DESPESA VARCHAR(100) DEFAULT NULL,
	CD_ELEMENTO_DESPESA_EXE_ANT VARCHAR(2) DEFAULT NULL,
	DS_ELEMENTO_DESPESA_EXE_ANT VARCHAR(100) DEFAULT NULL,
	NUMR_NEX VARCHAR(18) DEFAULT NULL,
	FORMA_PGTO VARCHAR(100) DEFAULT NULL,
	CODG_BANCARIO VARCHAR(5) DEFAULT NULL,
	CODG_BANCO VARCHAR(3) DEFAULT NULL,
	CODG_AGENCIA VARCHAR(4) DEFAULT NULL,
	NUMR_CONTA_CORRENTE VARCHAR(15) DEFAULT NULL,
	DV_CONTA_CORRENTE VARCHAR(2) DEFAULT NULL,
	DISPONIBILIDADE_RP VARCHAR(8) DEFAULT NULL,
	VALR_LIQ DECIMAL(15,2) DEFAULT NULL,
	HISTORICO_LIQ VARCHAR(400) DEFAULT NULL,
	IDEN_CREDOR VARCHAR(10) DEFAULT NULL,
	NOME_CREDOR VARCHAR(100) DEFAULT NULL,
	CNPJ VARCHAR(14) DEFAULT NULL,
	CPF VARCHAR(11) DEFAULT NULL,
	NUMR_PROTOCOLO VARCHAR(8) DEFAULT NULL,
	CD_EXERCICIO_PROTOCOLO VARCHAR(4) DEFAULT NULL,
	FORMA_RCTO VARCHAR(100) DEFAULT NULL,
	IDEN_CONTA_CORRENTE_CREDOR VARCHAR(8) DEFAULT NULL,
	NUMR_OS VARCHAR(18) DEFAULT NULL,
	DATA_INICIO_VIAGEM DATE DEFAULT NULL,
	DATA_RETORNO_VIAGEM DATE DEFAULT NULL,
	NUMR_CONVENIO VARCHAR(20) DEFAULT NULL,
	NUMR_CONTRATO VARCHAR(18) DEFAULT NULL,
	DATA_VIGENCIA DATE DEFAULT NULL,
	NUMR_CAC VARCHAR(18) DEFAULT NULL,
	TIPO_EMP VARCHAR(15) DEFAULT NULL,
	VALR_EMP DECIMAL(15,2) DEFAULT NULL,
	VALR_SALDO_LIQUIDAR DECIMAL(15,2) DEFAULT NULL,
	VALR_PAGAMENTO DECIMAL(15,2) DEFAULT NULL,
	VALR_TOTAL_CONSIGNACOES DECIMAL(15,2) DEFAULT NULL,
	SITUACAO_LIQ VARCHAR(100) DEFAULT NULL,
	NUMR_ESTORNO_LIQ VARCHAR(18) DEFAULT NULL,
	VALOR_ESTORNO DECIMAL(15,2) DEFAULT NULL,
	DATA_ESTORNO DATE DEFAULT NULL,
	MOTIVO_ESTORNO VARCHAR(150) DEFAULT NULL,
	DATA_CRIACAO_DOCTO DATE DEFAULT NULL,
	USUARIO VARCHAR(8) DEFAULT NULL,
 	PRIMARY KEY (financeiro_estorno_ns_fiplan_id),
  KEY financeiro_estorno_ns_fiplan_cia (financeiro_estorno_ns_fiplan_cia),
  KEY financeiro_estorno_ns_fiplan_dept (financeiro_estorno_ns_fiplan_dept),
  KEY financeiro_estorno_ns_fiplan_responsavel (financeiro_estorno_ns_fiplan_responsavel),
  CONSTRAINT financeiro_estorno_ns_fiplan_dept FOREIGN KEY (financeiro_estorno_ns_fiplan_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_ns_fiplan_cia FOREIGN KEY (financeiro_estorno_ns_fiplan_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_ns_fiplan_responsavel FOREIGN KEY (financeiro_estorno_ns_fiplan_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS financeiro_estorno_ob_fiplan;

CREATE TABLE financeiro_estorno_ob_fiplan(
	financeiro_estorno_ob_fiplan_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	financeiro_estorno_ob_fiplan_cia INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ob_fiplan_dept INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ob_fiplan_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_estorno_ob_fiplan_cor VARCHAR(6) DEFAULT 'FFFFFF',
	financeiro_estorno_ob_fiplan_acesso INTEGER(100) UNSIGNED DEFAULT 0,
	financeiro_estorno_ob_fiplan_ativo TINYINT(1) DEFAULT 1,
	CD_EXERCICIO VARCHAR(4) DEFAULT NULL,
	NUMR_NOB VARCHAR(18) DEFAULT NULL,
	DATA_EMISSAO DATE DEFAULT NULL,
	NUMR_NOBLIST VARCHAR(18) DEFAULT NULL,
	NUMR_DOTLIST VARCHAR(18) DEFAULT NULL,
	CD_UNIDADE_ORCAMENTARIA VARCHAR(5) DEFAULT NULL,
	DS_UNIDADE_ORCAMENTARIA VARCHAR(100) DEFAULT NULL,
	CD_UNIDADE_GESTORA VARCHAR(4) DEFAULT NULL,
	DS_UNIDADE_GESTORA VARCHAR(100) DEFAULT NULL,
	CODG_BANCARIO VARCHAR(5) DEFAULT NULL,
	CODG_BANCO VARCHAR(3) DEFAULT NULL,
	CODG_AGENCIA VARCHAR(4) DEFAULT NULL,
	NUMR_CONTA_CORRENTE VARCHAR(15) DEFAULT NULL,
	DV_CONTA_CORRENTE VARCHAR(2) DEFAULT NULL,
	REGULARIZACAO_PAGAMENTO VARCHAR(100) DEFAULT NULL,
	NUMR_NEX VARCHAR(18) DEFAULT NULL,
	HISTORICO VARCHAR(400) DEFAULT NULL,
	TIPO_OB VARCHAR(8) DEFAULT NULL,
	NOME_TIPO_OB VARCHAR(100) DEFAULT NULL,
	FLAG_NOB_FATURA_FATO54 VARCHAR(3) DEFAULT NULL,
	IDEN_CREDOR VARCHAR(10) DEFAULT NULL,
	NOME_CREDOR VARCHAR(100) DEFAULT NULL,
	CNPJ VARCHAR(14) DEFAULT NULL,
	CPF VARCHAR(11) DEFAULT NULL,
	NUMR_EMP VARCHAR(18) DEFAULT NULL,
	NUMR_LIQ VARCHAR(18) DEFAULT NULL,
	FONTE_RECURSO VARCHAR(8) DEFAULT NULL,
	NUMR_PROTOCOLO VARCHAR(8) DEFAULT NULL,
	CD_EXERCICIO_PROTOCOLO VARCHAR(4) DEFAULT NULL,
	IDEN_CONTA_CORRENTE_CREDOR VARCHAR(8) DEFAULT NULL,
	CODG_BANCO_CREDOR VARCHAR(3) DEFAULT NULL,
	CODG_AGENCIA_CREDOR VARCHAR(4) DEFAULT NULL,
	NUMR_CONTA_CORRENTE_CREDOR VARCHAR(15) DEFAULT NULL,
	DV_CONTA_CORRENTE_CREDOR VARCHAR(2) DEFAULT NULL,
	VALR_NOB DECIMAL(15,2) DEFAULT NULL,
	IDEN_ORDENADOR_DESPESA VARCHAR(8) DEFAULT NULL,
	NOME_ORDENADOR VARCHAR(100) DEFAULT NULL,
	OBSERVACAO VARCHAR(150) DEFAULT NULL,
	SITUACAO_NOB VARCHAR(8) DEFAULT NULL,
	FLAG_TRANSMISSAO_ELETRONICA VARCHAR(100) DEFAULT NULL,
	NUMR_DOCT_ESTORNO VARCHAR(18) DEFAULT NULL,
	TIPO_PAGAMENTO VARCHAR(8) DEFAULT NULL,
	DATA_OCORRENCIA DATE DEFAULT NULL,
	NUMR_ARQUIVO_LOTE VARCHAR(7) DEFAULT NULL,
	NUMR_RELACAO_PAGAMENTO_RE VARCHAR(18) DEFAULT NULL,
	DATA_RETORNO DATE DEFAULT NULL,
	NUMR_ARQUIVO_RETORNO VARCHAR(18) DEFAULT NULL,
	SITUACAO_TRANSMISSAO_ELETRONICA VARCHAR(8) DEFAULT NULL,
	DATA_CRIACAO_DOCUMENTO DATE DEFAULT NULL,
	HORA_CRIACAO_DOCUMENTO DATE DEFAULT NULL,
	USUARIO VARCHAR(8) DEFAULT NULL,
 	PRIMARY KEY (financeiro_estorno_ob_fiplan_id),
  KEY financeiro_estorno_ob_fiplan_cia (financeiro_estorno_ob_fiplan_cia),
  KEY financeiro_estorno_ob_fiplan_dept (financeiro_estorno_ob_fiplan_dept),
  KEY financeiro_estorno_ob_fiplan_responsavel (financeiro_estorno_ob_fiplan_responsavel),
  CONSTRAINT financeiro_estorno_ob_fiplan_dept FOREIGN KEY (financeiro_estorno_ob_fiplan_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_ob_fiplan_cia FOREIGN KEY (financeiro_estorno_ob_fiplan_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_ob_fiplan_responsavel FOREIGN KEY (financeiro_estorno_ob_fiplan_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;	


DROP TABLE IF EXISTS projeto_programa;

CREATE TABLE projeto_programa (
	projeto_programa_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	projeto_programa_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_programa_programa varchar(50) DEFAULT NULL,
  projeto_programa_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_programa_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY projeto_programa_id (projeto_programa_id),
	KEY projeto_programa_projeto (projeto_programa_projeto),
	CONSTRAINT projeto_programa_projeto FOREIGN KEY (projeto_programa_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
