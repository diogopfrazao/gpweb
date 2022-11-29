SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2017-12-07';
UPDATE versao SET ultima_atualizacao_codigo='2017-12-07';
UPDATE versao SET versao_bd=463;



ALTER TABLE priorizacao_modelo_opcao CHANGE priorizacao_modelo_opcao_valor priorizacao_modelo_opcao_valor DECIMAL(20,2) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao CHANGE priorizacao_valor priorizacao_valor DECIMAL(20,2) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_priorizacao CHANGE priorizacao_valor priorizacao_valor DECIMAL(20,2) UNSIGNED DEFAULT NULL;

ALTER TABLE registro CHANGE registro_sql registro_sql MEDIUMTEXT;

ALTER TABLE anexo_leitura DROP FOREIGN KEY anexo_leitura_fk;
ALTER TABLE anexo_leitura ADD  CONSTRAINT anexo_leitura_anexo FOREIGN KEY (anexo_id) REFERENCES anexo (anexo_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS ssti;

CREATE TABLE ssti (
  ssti_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ssti_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_nome VARCHAR(255) DEFAULT NULL,
  ssti_numero VARCHAR(255) DEFAULT NULL,
  ssti_data DATE DEFAULT NULL,
 	ssti_descricao MEDIUMTEXT,
 	ssti_justificativa MEDIUMTEXT,
 	ssti_funcionamento MEDIUMTEXT,
 	ssti_demanda_legal TINYINT(1) DEFAULT 0,
 	ssti_data_atendimento DATE DEFAULT NULL,
 	ssti_nao_atendimento MEDIUMTEXT,
 	ssti_data_publicacao DATE DEFAULT NULL,
 	ssti_estimativa_lucro MEDIUMTEXT,
 	ssti_disponibilidade INTEGER(10) UNSIGNED DEFAULT NULL,
 	ssti_abrangencia VARCHAR(255) DEFAULT NULL,
	ssti_utilizacao MEDIUMTEXT,
	ssti_info_adicional MEDIUMTEXT,
  ssti_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  ssti_status INTEGER(10) DEFAULT 0,
  ssti_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  ssti_cor VARCHAR(6) DEFAULT 'ffffff',
  ssti_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  ssti_aprovado TINYINT(1) DEFAULT 0,
  ssti_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (ssti_id),
  KEY ssti_cia (ssti_cia),
  KEY ssti_dept (ssti_dept),
	KEY ssti_responsavel (ssti_responsavel),
  KEY ssti_principal_indicador (ssti_principal_indicador),
	KEY ssti_moeda (ssti_moeda),
  CONSTRAINT ssti_responsavel FOREIGN KEY (ssti_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ssti_cia FOREIGN KEY (ssti_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_dept FOREIGN KEY (ssti_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ssti_principal_indicador FOREIGN KEY (ssti_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ssti_moeda FOREIGN KEY (ssti_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ssti_custo;

CREATE TABLE ssti_custo (
  ssti_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ssti_custo_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_custo_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_custo_nome VARCHAR(255) DEFAULT NULL,
  ssti_custo_codigo VARCHAR(255) DEFAULT NULL,
  ssti_custo_fonte VARCHAR(255) DEFAULT NULL,
  ssti_custo_regiao VARCHAR(255) DEFAULT NULL,
  ssti_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  ssti_custo_data DATETIME DEFAULT NULL,
  ssti_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  ssti_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  ssti_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	ssti_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	ssti_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	ssti_custo_data_moeda DATE DEFAULT NULL,
  ssti_custo_percentagem TINYINT(4) DEFAULT 0,
  ssti_custo_descricao MEDIUMTEXT,
  ssti_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_custo_nd VARCHAR(11) DEFAULT NULL,
  ssti_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  ssti_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  ssti_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  ssti_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	ssti_custo_data_limite DATE DEFAULT NULL,
	ssti_custo_pi VARCHAR(100) DEFAULT NULL,
	ssti_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_custo_aprovado TINYINT(1) DEFAULT NULL,
	ssti_custo_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (ssti_custo_id),
  KEY ssti_custo_ssti (ssti_custo_ssti),
  KEY ssti_custo_usuario_inicio (ssti_custo_usuario),
  KEY ssti_custo_tr (ssti_custo_tr),
  KEY ssti_custo_ordem (ssti_custo_ordem),
  KEY ssti_custo_data_inicio (ssti_custo_data),
  KEY ssti_custo_nome (ssti_custo_nome),
  KEY ssti_custo_aprovou (ssti_custo_aprovou),
  KEY ssti_custo_moeda (ssti_custo_moeda),
  CONSTRAINT ssti_custo_usuario FOREIGN KEY (ssti_custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ssti_custo_tr FOREIGN KEY (ssti_custo_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ssti_custo_ssti FOREIGN KEY (ssti_custo_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_custo_aprovou FOREIGN KEY (ssti_custo_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ssti_custo_moeda FOREIGN KEY (ssti_custo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE custo_observacao ADD COLUMN custo_observacao_ssti_custo int(100) unsigned DEFAULT NULL;
ALTER TABLE custo_observacao ADD KEY custo_observacao_ssti_custo (custo_observacao_ssti_custo);
ALTER TABLE custo_observacao ADD CONSTRAINT custo_observacao_ssti_custo FOREIGN KEY (custo_observacao_ssti_custo) REFERENCES ssti_custo (ssti_custo_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS laudo_custo;
CREATE TABLE laudo_custo (
  laudo_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  laudo_custo_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_custo_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_custo_nome VARCHAR(255) DEFAULT NULL,
  laudo_custo_codigo VARCHAR(255) DEFAULT NULL,
  laudo_custo_fonte VARCHAR(255) DEFAULT NULL,
  laudo_custo_regiao VARCHAR(255) DEFAULT NULL,
  laudo_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  laudo_custo_data DATETIME DEFAULT NULL,
  laudo_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  laudo_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  laudo_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	laudo_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	laudo_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	laudo_custo_data_moeda DATE DEFAULT NULL,
  laudo_custo_percentagem TINYINT(4) DEFAULT 0,
  laudo_custo_descricao MEDIUMTEXT,
  laudo_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_custo_nd VARCHAR(11) DEFAULT NULL,
  laudo_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  laudo_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  laudo_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  laudo_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	laudo_custo_data_limite DATE DEFAULT NULL,
	laudo_custo_pi VARCHAR(100) DEFAULT NULL,
	laudo_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_custo_aprovado TINYINT(1) DEFAULT NULL,
	laudo_custo_data_aprovado DATETIME DEFAULT NULL,
	laudo_custo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (laudo_custo_id),
  KEY laudo_custo_laudo (laudo_custo_laudo),
  KEY laudo_custo_usuario_inicio (laudo_custo_usuario),
  KEY laudo_custo_tr (laudo_custo_tr),
  KEY laudo_custo_ordem (laudo_custo_ordem),
  KEY laudo_custo_data_inicio (laudo_custo_data),
  KEY laudo_custo_nome (laudo_custo_nome),
  KEY laudo_custo_aprovou (laudo_custo_aprovou),
  KEY laudo_custo_moeda (laudo_custo_moeda),
  CONSTRAINT laudo_custo_usuario FOREIGN KEY (laudo_custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_custo_tr FOREIGN KEY (laudo_custo_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_custo_laudo FOREIGN KEY (laudo_custo_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_custo_aprovou FOREIGN KEY (laudo_custo_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_custo_moeda FOREIGN KEY (laudo_custo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE custo_observacao ADD COLUMN custo_observacao_laudo_custo int(100) unsigned DEFAULT NULL;
ALTER TABLE custo_observacao ADD KEY custo_observacao_laudo_custo (custo_observacao_laudo_custo);
ALTER TABLE custo_observacao ADD CONSTRAINT custo_observacao_laudo_custo FOREIGN KEY (custo_observacao_laudo_custo) REFERENCES laudo_custo (laudo_custo_id) ON DELETE CASCADE ON UPDATE CASCADE;




DROP TABLE IF EXISTS ssti_dept;

CREATE TABLE ssti_dept (
  ssti_dept_ssti INTEGER(100) UNSIGNED NOT NULL,
  ssti_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (ssti_dept_ssti, ssti_dept_dept),
  KEY ssti_dept_ssti (ssti_dept_ssti),
  KEY ssti_dept_dept (ssti_dept_dept),
  CONSTRAINT ssti_dept_dept FOREIGN KEY (ssti_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_dept_ssti FOREIGN KEY (ssti_dept_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ssti_usuario;

CREATE TABLE ssti_usuario (
  ssti_usuario_ssti INTEGER(100) UNSIGNED NOT NULL,
  ssti_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY ssti_usuario_ssti (ssti_usuario_ssti),
  KEY ssti_usuario_usuario (ssti_usuario_usuario),
  CONSTRAINT ssti_usuario_ssti FOREIGN KEY (ssti_usuario_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_usuario_usuario FOREIGN KEY (ssti_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS ssti_patrocinador;

CREATE TABLE ssti_patrocinador (
  ssti_patrocinador_ssti INTEGER(100) UNSIGNED NOT NULL,
  ssti_patrocinador_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY ssti_patrocinador_ssti (ssti_patrocinador_ssti),
  KEY ssti_patrocinador_usuario (ssti_patrocinador_usuario),
  CONSTRAINT ssti_patrocinador_ssti FOREIGN KEY (ssti_patrocinador_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_patrocinador_usuario FOREIGN KEY (ssti_patrocinador_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ssti_stackeholder;

CREATE TABLE ssti_stackeholder (
  ssti_stackeholder_ssti INTEGER(100) UNSIGNED NOT NULL,
  ssti_stackeholder_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY ssti_stackeholder_ssti (ssti_stackeholder_ssti),
  KEY ssti_stackeholder_usuario (ssti_stackeholder_usuario),
  CONSTRAINT ssti_stackeholder_ssti FOREIGN KEY (ssti_stackeholder_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_stackeholder_usuario FOREIGN KEY (ssti_stackeholder_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ssti_cia;

CREATE TABLE ssti_cia (
  ssti_cia_ssti INTEGER(100) UNSIGNED NOT NULL,
  ssti_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (ssti_cia_ssti, ssti_cia_cia),
  KEY ssti_cia_ssti (ssti_cia_ssti),
  KEY ssti_cia_cia (ssti_cia_cia),
  CONSTRAINT ssti_cia_cia FOREIGN KEY (ssti_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_cia_ssti FOREIGN KEY (ssti_cia_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS ssti_gestao;

CREATE TABLE ssti_gestao (
	ssti_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	ssti_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY ssti_gestao_id (ssti_gestao_id),
	KEY ssti_gestao_ssti (ssti_gestao_ssti),
	KEY ssti_gestao_semelhante (ssti_gestao_semelhante),
	KEY ssti_gestao_projeto (ssti_gestao_projeto),
	KEY ssti_gestao_tarefa (ssti_gestao_tarefa),
	KEY ssti_gestao_perspectiva (ssti_gestao_perspectiva),
	KEY ssti_gestao_tema (ssti_gestao_tema),
	KEY ssti_gestao_objetivo (ssti_gestao_objetivo),
	KEY ssti_gestao_estrategia (ssti_gestao_estrategia),
	KEY ssti_gestao_meta (ssti_gestao_meta),
	KEY ssti_gestao_fator (ssti_gestao_fator),
	KEY ssti_gestao_pratica (ssti_gestao_pratica),
	KEY ssti_gestao_indicador (ssti_gestao_indicador),
	KEY ssti_gestao_acao (ssti_gestao_acao),
	KEY ssti_gestao_canvas (ssti_gestao_canvas),
	KEY ssti_gestao_risco (ssti_gestao_risco),
	KEY ssti_gestao_risco_resposta (ssti_gestao_risco_resposta),
	KEY ssti_gestao_calendario (ssti_gestao_calendario),
	KEY ssti_gestao_monitoramento (ssti_gestao_monitoramento),
	KEY ssti_gestao_ata (ssti_gestao_ata),
	KEY ssti_gestao_mswot(ssti_gestao_mswot),
	KEY ssti_gestao_swot(ssti_gestao_swot),
	KEY ssti_gestao_operativo(ssti_gestao_operativo),
	KEY ssti_gestao_instrumento (ssti_gestao_instrumento),
	KEY ssti_gestao_recurso (ssti_gestao_recurso),
	KEY ssti_gestao_problema (ssti_gestao_problema),
	KEY ssti_gestao_demanda (ssti_gestao_demanda),
	KEY ssti_gestao_programa (ssti_gestao_programa),
	KEY ssti_gestao_licao (ssti_gestao_licao),
	KEY ssti_gestao_evento (ssti_gestao_evento),
	KEY ssti_gestao_link (ssti_gestao_link),
	KEY ssti_gestao_avaliacao (ssti_gestao_avaliacao),
	KEY ssti_gestao_tgn (ssti_gestao_tgn),
	KEY ssti_gestao_brainstorm (ssti_gestao_brainstorm),
	KEY ssti_gestao_gut (ssti_gestao_gut),
	KEY ssti_gestao_causa_efeito (ssti_gestao_causa_efeito),
	KEY ssti_gestao_arquivo (ssti_gestao_arquivo),
	KEY ssti_gestao_forum (ssti_gestao_forum),
	KEY ssti_gestao_checklist (ssti_gestao_checklist),
	KEY ssti_gestao_agenda (ssti_gestao_agenda),
	KEY ssti_gestao_agrupamento (ssti_gestao_agrupamento),
	KEY ssti_gestao_patrocinador (ssti_gestao_patrocinador),
	KEY ssti_gestao_template (ssti_gestao_template),
	KEY ssti_gestao_painel (ssti_gestao_painel),
	KEY ssti_gestao_painel_odometro (ssti_gestao_painel_odometro),
	KEY ssti_gestao_painel_composicao (ssti_gestao_painel_composicao),
	KEY ssti_gestao_tr (ssti_gestao_tr),
	KEY ssti_gestao_me (ssti_gestao_me),
	KEY ssti_gestao_acao_item (ssti_gestao_acao_item),
	KEY ssti_gestao_beneficio (ssti_gestao_beneficio),
	KEY ssti_gestao_painel_slideshow (ssti_gestao_painel_slideshow),
	KEY ssti_gestao_projeto_viabilidade (ssti_gestao_projeto_viabilidade),
	KEY ssti_gestao_projeto_abertura (ssti_gestao_projeto_abertura),
	KEY ssti_gestao_plano_gestao (ssti_gestao_plano_gestao),
	KEY ssti_gestao_laudo (ssti_gestao_laudo),
	KEY ssti_gestao_trelo (ssti_gestao_trelo),
	KEY ssti_gestao_trelo_cartao (ssti_gestao_trelo_cartao),
	KEY ssti_gestao_pdcl (ssti_gestao_pdcl),
	KEY ssti_gestao_pdcl_item (ssti_gestao_pdcl_item),
	CONSTRAINT ssti_gestao_ssti FOREIGN KEY (ssti_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_semelhante FOREIGN KEY (ssti_gestao_semelhante) REFERENCES ssti (ssti_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_projeto FOREIGN KEY (ssti_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_tarefa FOREIGN KEY (ssti_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_perspectiva FOREIGN KEY (ssti_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_tema FOREIGN KEY (ssti_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_objetivo FOREIGN KEY (ssti_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_fator FOREIGN KEY (ssti_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_estrategia FOREIGN KEY (ssti_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_meta FOREIGN KEY (ssti_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_pratica FOREIGN KEY (ssti_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_indicador FOREIGN KEY (ssti_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_acao FOREIGN KEY (ssti_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_canvas FOREIGN KEY (ssti_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_risco FOREIGN KEY (ssti_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_risco_resposta FOREIGN KEY (ssti_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_calendario FOREIGN KEY (ssti_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_monitoramento FOREIGN KEY (ssti_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_ata FOREIGN KEY (ssti_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_mswot FOREIGN KEY (ssti_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_swot FOREIGN KEY (ssti_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_operativo FOREIGN KEY (ssti_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_instrumento FOREIGN KEY (ssti_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_recurso FOREIGN KEY (ssti_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_problema FOREIGN KEY (ssti_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_demanda FOREIGN KEY (ssti_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_programa FOREIGN KEY (ssti_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_licao FOREIGN KEY (ssti_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_evento FOREIGN KEY (ssti_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_link FOREIGN KEY (ssti_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_avaliacao FOREIGN KEY (ssti_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_tgn FOREIGN KEY (ssti_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_brainstorm FOREIGN KEY (ssti_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_gut FOREIGN KEY (ssti_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_causa_efeito FOREIGN KEY (ssti_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_arquivo FOREIGN KEY (ssti_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_forum FOREIGN KEY (ssti_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_checklist FOREIGN KEY (ssti_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_agenda FOREIGN KEY (ssti_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_agrupamento FOREIGN KEY (ssti_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_patrocinador FOREIGN KEY (ssti_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_template FOREIGN KEY (ssti_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_painel FOREIGN KEY (ssti_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_painel_odometro FOREIGN KEY (ssti_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_painel_composicao FOREIGN KEY (ssti_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_tr FOREIGN KEY (ssti_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_me FOREIGN KEY (ssti_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_acao_item FOREIGN KEY (ssti_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_beneficio FOREIGN KEY (ssti_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_painel_slideshow FOREIGN KEY (ssti_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_projeto_viabilidade FOREIGN KEY (ssti_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_projeto_abertura FOREIGN KEY (ssti_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_plano_gestao FOREIGN KEY (ssti_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_laudo FOREIGN KEY (ssti_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_trelo FOREIGN KEY (ssti_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_trelo_cartao FOREIGN KEY (ssti_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_pdcl FOREIGN KEY (ssti_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ssti_gestao_pdcl_item FOREIGN KEY (ssti_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ssti_config;

CREATE TABLE ssti_config (
	ssti_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	ssti_config_gerente INTEGER(100) UNSIGNED DEFAULT NULL,
	ssti_config_gerente_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_gestor INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_gestor_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
 	ssti_config_seorp_secretario INTEGER(100) UNSIGNED DEFAULT NULL,
 	ssti_config_seorp_secretario_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_seorp_coordenador INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_seorp_coordenador_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_gereo_gerente INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_gereo_gerente_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_gereo_coordenador INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_gereo_coordenador_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_coged_analista INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_coged_analista_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_coged_coordenador INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_config_coged_coordenador_parecer INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (ssti_config_id),
  KEY ssti_config_gerente (ssti_config_gerente),
  KEY ssti_config_gerente_parecer (ssti_config_gerente_parecer),
  KEY ssti_config_gestor (ssti_config_gestor),
  KEY ssti_config_gestor_parecer (ssti_config_gestor_parecer),
  KEY ssti_config_seorp_secretario (ssti_config_seorp_secretario),
  KEY ssti_config_seorp_secretario_parecer (ssti_config_seorp_secretario_parecer),
	KEY ssti_config_seorp_coordenador (ssti_config_seorp_coordenador),
	KEY ssti_config_seorp_coordenador_parecer (ssti_config_seorp_coordenador_parecer),
	KEY ssti_config_gereo_gerente (ssti_config_gereo_gerente),
	KEY ssti_config_gereo_gerente_parecer (ssti_config_gereo_gerente_parecer),
	KEY ssti_config_gereo_coordenador (ssti_config_gereo_coordenador),
	KEY ssti_config_gereo_coordenador_parecer (ssti_config_gereo_coordenador_parecer),
	KEY ssti_config_coged_analista (ssti_config_coged_analista),
  KEY ssti_config_coged_analista_parecer (ssti_config_coged_analista_parecer),
  KEY ssti_config_coged_coordenador (ssti_config_coged_coordenador),
  KEY ssti_config_coged_coordenador_parecer (ssti_config_coged_coordenador_parecer),
	CONSTRAINT ssti_config_gerente FOREIGN KEY (ssti_config_gerente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gerente_parecer FOREIGN KEY (ssti_config_gerente_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gestor FOREIGN KEY (ssti_config_gestor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gestor_parecer FOREIGN KEY (ssti_config_gestor_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_seorp_secretario FOREIGN KEY (ssti_config_seorp_secretario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_seorp_secretario_parecer FOREIGN KEY (ssti_config_seorp_secretario_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_seorp_coordenador FOREIGN KEY (ssti_config_seorp_coordenador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_seorp_coordenador_parecer FOREIGN KEY (ssti_config_seorp_coordenador_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gereo_gerente FOREIGN KEY (ssti_config_gereo_gerente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gereo_gerente_parecer FOREIGN KEY (ssti_config_gereo_gerente_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gereo_coordenador FOREIGN KEY (ssti_config_gereo_coordenador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_gereo_coordenador_parecer FOREIGN KEY (ssti_config_gereo_coordenador_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_coged_analista FOREIGN KEY (ssti_config_coged_analista) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_coged_analista_parecer FOREIGN KEY (ssti_config_coged_analista_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_coged_coordenador FOREIGN KEY (ssti_config_coged_coordenador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT ssti_config_coged_coordenador_parecer FOREIGN KEY (ssti_config_coged_coordenador_parecer) REFERENCES assinatura_atesta (assinatura_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

INSERT INTO ssti_config (ssti_config_id, ssti_config_gerente, ssti_config_gerente_parecer, ssti_config_gestor, ssti_config_gestor_parecer, ssti_config_seorp_secretario, ssti_config_seorp_secretario_parecer, ssti_config_seorp_coordenador, ssti_config_seorp_coordenador_parecer, ssti_config_gereo_gerente, ssti_config_gereo_gerente_parecer, ssti_config_gereo_coordenador, ssti_config_gereo_coordenador_parecer, ssti_config_coged_analista, ssti_config_coged_analista_parecer, ssti_config_coged_coordenador, ssti_config_coged_coordenador_parecer) VALUES
  (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

ALTER TABLE assinatura ADD COLUMN assinatura_nao_exclui TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura ADD COLUMN assinatura_extra VARCHAR(255) DEFAULT NULL;

ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ssti TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_ssti TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_ssti TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura ADD COLUMN assinatura_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_ssti (assinatura_ssti);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_ssti FOREIGN KEY (assinatura_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_ssti (projeto_gestao_ssti);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_ssti FOREIGN KEY (projeto_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_ssti (perspectiva_gestao_ssti);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_ssti FOREIGN KEY (perspectiva_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_ssti (tema_gestao_ssti);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_ssti FOREIGN KEY (tema_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_ssti (objetivo_gestao_ssti);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_ssti FOREIGN KEY (objetivo_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_ssti (fator_gestao_ssti);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_ssti FOREIGN KEY (fator_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_ssti (estrategia_gestao_ssti);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_ssti FOREIGN KEY (estrategia_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_ssti (meta_gestao_ssti);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_ssti FOREIGN KEY (meta_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_ssti (pratica_gestao_ssti);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_ssti FOREIGN KEY (pratica_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_ssti (pratica_indicador_gestao_ssti);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_ssti FOREIGN KEY (pratica_indicador_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_ssti (plano_acao_gestao_ssti);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_ssti FOREIGN KEY (plano_acao_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_ssti (canvas_gestao_ssti);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_ssti FOREIGN KEY (canvas_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_ssti (risco_gestao_ssti);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_ssti FOREIGN KEY (risco_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_ssti (risco_resposta_gestao_ssti);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_ssti FOREIGN KEY (risco_resposta_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_ssti (calendario_gestao_ssti);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_ssti FOREIGN KEY (calendario_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_ssti (monitoramento_gestao_ssti);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_ssti FOREIGN KEY (monitoramento_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_ssti (ata_gestao_ssti);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_ssti FOREIGN KEY (ata_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_ssti (mswot_gestao_ssti);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_ssti FOREIGN KEY (mswot_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_ssti (swot_gestao_ssti);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_ssti FOREIGN KEY (swot_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_ssti (operativo_gestao_ssti);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_ssti FOREIGN KEY (operativo_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_ssti (instrumento_gestao_ssti);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_ssti FOREIGN KEY (instrumento_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_ssti (recurso_gestao_ssti);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_ssti FOREIGN KEY (recurso_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_ssti (problema_gestao_ssti);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_ssti FOREIGN KEY (problema_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_ssti (demanda_gestao_ssti);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_ssti FOREIGN KEY (demanda_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_ssti (programa_gestao_ssti);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_ssti FOREIGN KEY (programa_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_ssti (licao_gestao_ssti);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_ssti FOREIGN KEY (licao_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_ssti (evento_gestao_ssti);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_ssti FOREIGN KEY (evento_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_ssti (link_gestao_ssti);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_ssti FOREIGN KEY (link_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_ssti (avaliacao_gestao_ssti);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_ssti FOREIGN KEY (avaliacao_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_ssti (tgn_gestao_ssti);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_ssti FOREIGN KEY (tgn_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_ssti (brainstorm_gestao_ssti);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_ssti FOREIGN KEY (brainstorm_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_ssti (gut_gestao_ssti);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_ssti FOREIGN KEY (gut_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_ssti (causa_efeito_gestao_ssti);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_ssti FOREIGN KEY (causa_efeito_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_ssti (arquivo_gestao_ssti);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_ssti FOREIGN KEY (arquivo_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_ssti (forum_gestao_ssti);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_ssti FOREIGN KEY (forum_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_ssti (checklist_gestao_ssti);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_ssti FOREIGN KEY (checklist_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_ssti (agenda_gestao_ssti);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_ssti FOREIGN KEY (agenda_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_ssti (agrupamento_gestao_ssti);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_ssti FOREIGN KEY (agrupamento_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_ssti (patrocinador_gestao_ssti);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_ssti FOREIGN KEY (patrocinador_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_ssti (template_gestao_ssti);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_ssti FOREIGN KEY (template_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_ssti (painel_gestao_ssti);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_ssti FOREIGN KEY (painel_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_ssti (painel_odometro_gestao_ssti);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_ssti FOREIGN KEY (painel_odometro_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_ssti (painel_composicao_gestao_ssti);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_ssti FOREIGN KEY (painel_composicao_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_ssti (tr_gestao_ssti);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_ssti FOREIGN KEY (tr_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_ssti (me_gestao_ssti);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_ssti FOREIGN KEY (me_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_ssti (plano_acao_item_gestao_ssti);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_ssti FOREIGN KEY (plano_acao_item_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_ssti (beneficio_gestao_ssti);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_ssti FOREIGN KEY (beneficio_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_ssti (painel_slideshow_gestao_ssti);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_ssti FOREIGN KEY (painel_slideshow_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_ssti (projeto_viabilidade_gestao_ssti);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_ssti FOREIGN KEY (projeto_viabilidade_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_ssti (projeto_abertura_gestao_ssti);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_ssti FOREIGN KEY (projeto_abertura_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_ssti (plano_gestao_gestao_ssti);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_ssti FOREIGN KEY (plano_gestao_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_ssti (arquivo_pasta_gestao_ssti);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_ssti FOREIGN KEY (arquivo_pasta_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_ssti (modelo_gestao_ssti);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_ssti FOREIGN KEY (modelo_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_ssti (msg_gestao_ssti);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_ssti FOREIGN KEY (msg_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_ssti (log_ssti);
ALTER TABLE log ADD CONSTRAINT log_ssti FOREIGN KEY (log_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
	
ALTER TABLE pi ADD COLUMN pi_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_ssti (pi_ssti);
ALTER TABLE pi ADD CONSTRAINT pi_ssti FOREIGN KEY (pi_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_ssti (ptres_ssti);
ALTER TABLE ptres ADD CONSTRAINT ptres_ssti FOREIGN KEY (ptres_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_ssti TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_ssti (priorizacao_ssti);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_ssti FOREIGN KEY (priorizacao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;


	
DROP TABLE IF EXISTS laudo;

CREATE TABLE laudo (
  laudo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  laudo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_nome VARCHAR(255) DEFAULT NULL,
  laudo_numero VARCHAR(255) DEFAULT NULL,
  laudo_data DATE DEFAULT NULL,
 	laudo_descricao MEDIUMTEXT,
 	laudo_tipo_demanda MEDIUMTEXT,
 	laudo_demanda_legal MEDIUMTEXT,
 	laudo_abrangencia MEDIUMTEXT,
 	laudo_retorno_financeiro MEDIUMTEXT,
 	laudo_custo_desenvolvimento MEDIUMTEXT,
 	laudo_tempo_desenvolvimento MEDIUMTEXT,
 	laudo_ranking INTEGER(10) DEFAULT 0,
 	laudo_apontamento MEDIUMTEXT,
  laudo_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  laudo_status INTEGER(10) DEFAULT 0,
  laudo_comite VARCHAR(50) DEFAULT NULL,
  laudo_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  laudo_cor VARCHAR(6) DEFAULT 'ffffff',
  laudo_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  laudo_aprovado TINYINT(1) DEFAULT 0,
  laudo_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (laudo_id),
  KEY laudo_cia (laudo_cia),
  KEY laudo_dept (laudo_dept),
	KEY laudo_responsavel (laudo_responsavel),
  KEY laudo_principal_indicador (laudo_principal_indicador),
	KEY laudo_moeda (laudo_moeda),
  CONSTRAINT laudo_responsavel FOREIGN KEY (laudo_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_cia FOREIGN KEY (laudo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_dept FOREIGN KEY (laudo_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_principal_indicador FOREIGN KEY (laudo_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_moeda FOREIGN KEY (laudo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS laudo_dept;

CREATE TABLE laudo_dept (
  laudo_dept_laudo INTEGER(100) UNSIGNED NOT NULL,
  laudo_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (laudo_dept_laudo, laudo_dept_dept),
  KEY laudo_dept_laudo (laudo_dept_laudo),
  KEY laudo_dept_dept (laudo_dept_dept),
  CONSTRAINT laudo_dept_dept FOREIGN KEY (laudo_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_dept_laudo FOREIGN KEY (laudo_dept_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS laudo_usuario;

CREATE TABLE laudo_usuario (
  laudo_usuario_laudo INTEGER(100) UNSIGNED NOT NULL,
  laudo_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY laudo_usuario_laudo (laudo_usuario_laudo),
  KEY laudo_usuario_usuario (laudo_usuario_usuario),
  CONSTRAINT laudo_usuario_laudo FOREIGN KEY (laudo_usuario_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_usuario_usuario FOREIGN KEY (laudo_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS laudo_cia;

CREATE TABLE laudo_cia (
  laudo_cia_laudo INTEGER(100) UNSIGNED NOT NULL,
  laudo_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (laudo_cia_laudo, laudo_cia_cia),
  KEY laudo_cia_laudo (laudo_cia_laudo),
  KEY laudo_cia_cia (laudo_cia_cia),
  CONSTRAINT laudo_cia_cia FOREIGN KEY (laudo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_cia_laudo FOREIGN KEY (laudo_cia_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS laudo_stackeholder;

CREATE TABLE laudo_stackeholder (
  laudo_stackeholder_laudo INTEGER(100) UNSIGNED NOT NULL,
  laudo_stackeholder_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY laudo_stackeholder_laudo (laudo_stackeholder_laudo),
  KEY laudo_stackeholder_usuario (laudo_stackeholder_usuario),
  CONSTRAINT laudo_stackeholder_laudo FOREIGN KEY (laudo_stackeholder_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_stackeholder_usuario FOREIGN KEY (laudo_stackeholder_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS laudo_gestao;

CREATE TABLE laudo_gestao (
	laudo_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	laudo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	
	laudo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	laudo_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY laudo_gestao_id (laudo_gestao_id),
	KEY laudo_gestao_laudo (laudo_gestao_laudo),
	KEY laudo_gestao_semelhante (laudo_gestao_semelhante),
	KEY laudo_gestao_projeto (laudo_gestao_projeto),
	KEY laudo_gestao_tarefa (laudo_gestao_tarefa),
	KEY laudo_gestao_perspectiva (laudo_gestao_perspectiva),
	KEY laudo_gestao_tema (laudo_gestao_tema),
	KEY laudo_gestao_objetivo (laudo_gestao_objetivo),
	KEY laudo_gestao_estrategia (laudo_gestao_estrategia),
	KEY laudo_gestao_meta (laudo_gestao_meta),
	KEY laudo_gestao_fator (laudo_gestao_fator),
	KEY laudo_gestao_pratica (laudo_gestao_pratica),
	KEY laudo_gestao_indicador (laudo_gestao_indicador),
	KEY laudo_gestao_acao (laudo_gestao_acao),
	KEY laudo_gestao_canvas (laudo_gestao_canvas),
	KEY laudo_gestao_risco (laudo_gestao_risco),
	KEY laudo_gestao_risco_resposta (laudo_gestao_risco_resposta),
	KEY laudo_gestao_calendario (laudo_gestao_calendario),
	KEY laudo_gestao_monitoramento (laudo_gestao_monitoramento),
	KEY laudo_gestao_ata (laudo_gestao_ata),
	KEY laudo_gestao_mswot(laudo_gestao_mswot),
	KEY laudo_gestao_swot(laudo_gestao_swot),
	KEY laudo_gestao_operativo(laudo_gestao_operativo),
	KEY laudo_gestao_instrumento (laudo_gestao_instrumento),
	KEY laudo_gestao_recurso (laudo_gestao_recurso),
	KEY laudo_gestao_problema (laudo_gestao_problema),
	KEY laudo_gestao_demanda (laudo_gestao_demanda),
	KEY laudo_gestao_programa (laudo_gestao_programa),
	KEY laudo_gestao_licao (laudo_gestao_licao),
	KEY laudo_gestao_evento (laudo_gestao_evento),
	KEY laudo_gestao_link (laudo_gestao_link),
	KEY laudo_gestao_avaliacao (laudo_gestao_avaliacao),
	KEY laudo_gestao_tgn (laudo_gestao_tgn),
	KEY laudo_gestao_brainstorm (laudo_gestao_brainstorm),
	KEY laudo_gestao_gut (laudo_gestao_gut),
	KEY laudo_gestao_causa_efeito (laudo_gestao_causa_efeito),
	KEY laudo_gestao_arquivo (laudo_gestao_arquivo),
	KEY laudo_gestao_forum (laudo_gestao_forum),
	KEY laudo_gestao_checklist (laudo_gestao_checklist),
	KEY laudo_gestao_agenda (laudo_gestao_agenda),
	KEY laudo_gestao_agrupamento (laudo_gestao_agrupamento),
	KEY laudo_gestao_patrocinador (laudo_gestao_patrocinador),
	KEY laudo_gestao_template (laudo_gestao_template),
	KEY laudo_gestao_painel (laudo_gestao_painel),
	KEY laudo_gestao_painel_odometro (laudo_gestao_painel_odometro),
	KEY laudo_gestao_painel_composicao (laudo_gestao_painel_composicao),
	KEY laudo_gestao_tr (laudo_gestao_tr),
	KEY laudo_gestao_me (laudo_gestao_me),
	KEY laudo_gestao_acao_item (laudo_gestao_acao_item),
	KEY laudo_gestao_beneficio (laudo_gestao_beneficio),
	KEY laudo_gestao_painel_slideshow (laudo_gestao_painel_slideshow),
	KEY laudo_gestao_projeto_viabilidade (laudo_gestao_projeto_viabilidade),
	KEY laudo_gestao_projeto_abertura (laudo_gestao_projeto_abertura),
	KEY laudo_gestao_plano_gestao (laudo_gestao_plano_gestao),
	KEY laudo_gestao_ssti (laudo_gestao_ssti),
	KEY laudo_gestao_trelo (laudo_gestao_trelo),
	KEY laudo_gestao_trelo_cartao (laudo_gestao_trelo_cartao),
	KEY laudo_gestao_pdcl (laudo_gestao_pdcl),
	KEY laudo_gestao_pdcl_item (laudo_gestao_pdcl_item),
	CONSTRAINT laudo_gestao_laudo FOREIGN KEY (laudo_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_semelhante FOREIGN KEY (laudo_gestao_semelhante) REFERENCES laudo (laudo_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_projeto FOREIGN KEY (laudo_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_tarefa FOREIGN KEY (laudo_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_perspectiva FOREIGN KEY (laudo_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_tema FOREIGN KEY (laudo_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_objetivo FOREIGN KEY (laudo_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_fator FOREIGN KEY (laudo_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_estrategia FOREIGN KEY (laudo_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_meta FOREIGN KEY (laudo_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_pratica FOREIGN KEY (laudo_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_indicador FOREIGN KEY (laudo_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_acao FOREIGN KEY (laudo_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_canvas FOREIGN KEY (laudo_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_risco FOREIGN KEY (laudo_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_risco_resposta FOREIGN KEY (laudo_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_calendario FOREIGN KEY (laudo_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_monitoramento FOREIGN KEY (laudo_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_ata FOREIGN KEY (laudo_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_mswot FOREIGN KEY (laudo_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_swot FOREIGN KEY (laudo_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_operativo FOREIGN KEY (laudo_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_instrumento FOREIGN KEY (laudo_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_recurso FOREIGN KEY (laudo_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_problema FOREIGN KEY (laudo_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_demanda FOREIGN KEY (laudo_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_programa FOREIGN KEY (laudo_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_licao FOREIGN KEY (laudo_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_evento FOREIGN KEY (laudo_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_link FOREIGN KEY (laudo_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_avaliacao FOREIGN KEY (laudo_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_tgn FOREIGN KEY (laudo_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_brainstorm FOREIGN KEY (laudo_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_gut FOREIGN KEY (laudo_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_causa_efeito FOREIGN KEY (laudo_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_arquivo FOREIGN KEY (laudo_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_forum FOREIGN KEY (laudo_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_checklist FOREIGN KEY (laudo_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_agenda FOREIGN KEY (laudo_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_agrupamento FOREIGN KEY (laudo_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_patrocinador FOREIGN KEY (laudo_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_template FOREIGN KEY (laudo_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_painel FOREIGN KEY (laudo_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_painel_odometro FOREIGN KEY (laudo_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_painel_composicao FOREIGN KEY (laudo_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_tr FOREIGN KEY (laudo_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_me FOREIGN KEY (laudo_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_acao_item FOREIGN KEY (laudo_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_beneficio FOREIGN KEY (laudo_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_painel_slideshow FOREIGN KEY (laudo_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_projeto_viabilidade FOREIGN KEY (laudo_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_projeto_abertura FOREIGN KEY (laudo_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_plano_gestao FOREIGN KEY (laudo_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_ssti FOREIGN KEY (laudo_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_trelo FOREIGN KEY (laudo_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_trelo_cartao FOREIGN KEY (laudo_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_pdcl FOREIGN KEY (laudo_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT laudo_gestao_pdcl_item FOREIGN KEY (laudo_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



ALTER TABLE favorito_trava ADD COLUMN favorito_trava_laudo TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_laudo TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_laudo TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura ADD COLUMN assinatura_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_laudo (assinatura_laudo);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_laudo FOREIGN KEY (assinatura_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_laudo (projeto_gestao_laudo);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_laudo FOREIGN KEY (projeto_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_laudo (perspectiva_gestao_laudo);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_laudo FOREIGN KEY (perspectiva_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_laudo (tema_gestao_laudo);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_laudo FOREIGN KEY (tema_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_laudo (objetivo_gestao_laudo);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_laudo FOREIGN KEY (objetivo_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_laudo (fator_gestao_laudo);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_laudo FOREIGN KEY (fator_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_laudo (estrategia_gestao_laudo);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_laudo FOREIGN KEY (estrategia_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_laudo (meta_gestao_laudo);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_laudo FOREIGN KEY (meta_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_laudo (pratica_gestao_laudo);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_laudo FOREIGN KEY (pratica_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_laudo (pratica_indicador_gestao_laudo);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_laudo FOREIGN KEY (pratica_indicador_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_laudo (plano_acao_gestao_laudo);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_laudo FOREIGN KEY (plano_acao_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_laudo (canvas_gestao_laudo);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_laudo FOREIGN KEY (canvas_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_laudo (risco_gestao_laudo);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_laudo FOREIGN KEY (risco_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_laudo (risco_resposta_gestao_laudo);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_laudo FOREIGN KEY (risco_resposta_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_laudo (calendario_gestao_laudo);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_laudo FOREIGN KEY (calendario_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_laudo (monitoramento_gestao_laudo);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_laudo FOREIGN KEY (monitoramento_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_laudo (ata_gestao_laudo);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_laudo FOREIGN KEY (ata_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_laudo (mswot_gestao_laudo);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_laudo FOREIGN KEY (mswot_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_laudo (swot_gestao_laudo);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_laudo FOREIGN KEY (swot_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_laudo (operativo_gestao_laudo);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_laudo FOREIGN KEY (operativo_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_laudo (instrumento_gestao_laudo);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_laudo FOREIGN KEY (instrumento_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_laudo (recurso_gestao_laudo);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_laudo FOREIGN KEY (recurso_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_laudo (problema_gestao_laudo);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_laudo FOREIGN KEY (problema_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_laudo (demanda_gestao_laudo);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_laudo FOREIGN KEY (demanda_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_laudo (programa_gestao_laudo);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_laudo FOREIGN KEY (programa_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_laudo (licao_gestao_laudo);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_laudo FOREIGN KEY (licao_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_laudo (evento_gestao_laudo);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_laudo FOREIGN KEY (evento_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_laudo (link_gestao_laudo);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_laudo FOREIGN KEY (link_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_laudo (avaliacao_gestao_laudo);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_laudo FOREIGN KEY (avaliacao_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_laudo (tgn_gestao_laudo);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_laudo FOREIGN KEY (tgn_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_laudo (brainstorm_gestao_laudo);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_laudo FOREIGN KEY (brainstorm_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_laudo (gut_gestao_laudo);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_laudo FOREIGN KEY (gut_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_laudo (causa_efeito_gestao_laudo);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_laudo FOREIGN KEY (causa_efeito_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_laudo (arquivo_gestao_laudo);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_laudo FOREIGN KEY (arquivo_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_laudo (forum_gestao_laudo);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_laudo FOREIGN KEY (forum_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_laudo (checklist_gestao_laudo);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_laudo FOREIGN KEY (checklist_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_laudo (agenda_gestao_laudo);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_laudo FOREIGN KEY (agenda_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_laudo (agrupamento_gestao_laudo);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_laudo FOREIGN KEY (agrupamento_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_laudo (patrocinador_gestao_laudo);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_laudo FOREIGN KEY (patrocinador_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_laudo (template_gestao_laudo);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_laudo FOREIGN KEY (template_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_laudo (painel_gestao_laudo);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_laudo FOREIGN KEY (painel_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_laudo (painel_odometro_gestao_laudo);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_laudo FOREIGN KEY (painel_odometro_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_laudo (painel_composicao_gestao_laudo);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_laudo FOREIGN KEY (painel_composicao_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_laudo (tr_gestao_laudo);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_laudo FOREIGN KEY (tr_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_laudo (me_gestao_laudo);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_laudo FOREIGN KEY (me_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_laudo (plano_acao_item_gestao_laudo);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_laudo FOREIGN KEY (plano_acao_item_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_laudo (beneficio_gestao_laudo);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_laudo FOREIGN KEY (beneficio_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_laudo (painel_slideshow_gestao_laudo);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_laudo FOREIGN KEY (painel_slideshow_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_laudo (projeto_viabilidade_gestao_laudo);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_laudo FOREIGN KEY (projeto_viabilidade_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_laudo (projeto_abertura_gestao_laudo);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_laudo FOREIGN KEY (projeto_abertura_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_laudo (plano_gestao_gestao_laudo);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_laudo FOREIGN KEY (plano_gestao_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_laudo (arquivo_pasta_gestao_laudo);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_laudo FOREIGN KEY (arquivo_pasta_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_laudo (modelo_gestao_laudo);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_laudo FOREIGN KEY (modelo_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_laudo (msg_gestao_laudo);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_laudo FOREIGN KEY (msg_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_laudo (log_laudo);
ALTER TABLE log ADD CONSTRAINT log_laudo FOREIGN KEY (log_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE pi ADD COLUMN pi_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_laudo (pi_laudo);
ALTER TABLE pi ADD CONSTRAINT pi_laudo FOREIGN KEY (pi_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_laudo (ptres_laudo);
ALTER TABLE ptres ADD CONSTRAINT ptres_laudo FOREIGN KEY (ptres_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_laudo TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_laudo (priorizacao_laudo);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_laudo FOREIGN KEY (priorizacao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;





DROP TABLE IF EXISTS trelo;

CREATE TABLE trelo (
  trelo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  trelo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_nome VARCHAR(255) DEFAULT NULL,
 	trelo_descricao MEDIUMTEXT,
  trelo_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  trelo_status INTEGER(10) DEFAULT 0,
  trelo_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  trelo_cor VARCHAR(6) DEFAULT 'ffffff',
  trelo_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  trelo_aprovado TINYINT(1) DEFAULT 0,
  trelo_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (trelo_id),
  KEY trelo_cia (trelo_cia),
  KEY trelo_dept (trelo_dept),
	KEY trelo_responsavel (trelo_responsavel),
  KEY trelo_principal_indicador (trelo_principal_indicador),
	KEY trelo_moeda (trelo_moeda),
  CONSTRAINT trelo_responsavel FOREIGN KEY (trelo_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT trelo_cia FOREIGN KEY (trelo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_dept FOREIGN KEY (trelo_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT trelo_principal_indicador FOREIGN KEY (trelo_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT trelo_moeda FOREIGN KEY (trelo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_dept;

CREATE TABLE trelo_dept (
  trelo_dept_trelo INTEGER(100) UNSIGNED NOT NULL,
  trelo_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (trelo_dept_trelo, trelo_dept_dept),
  KEY trelo_dept_trelo (trelo_dept_trelo),
  KEY trelo_dept_dept (trelo_dept_dept),
  CONSTRAINT trelo_dept_dept FOREIGN KEY (trelo_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_dept_trelo FOREIGN KEY (trelo_dept_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_usuario;

CREATE TABLE trelo_usuario (
  trelo_usuario_trelo INTEGER(100) UNSIGNED NOT NULL,
  trelo_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY trelo_usuario_trelo (trelo_usuario_trelo),
  KEY trelo_usuario_usuario (trelo_usuario_usuario),
  CONSTRAINT trelo_usuario_trelo FOREIGN KEY (trelo_usuario_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_usuario_usuario FOREIGN KEY (trelo_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_cia;

CREATE TABLE trelo_cia (
  trelo_cia_trelo INTEGER(100) UNSIGNED NOT NULL,
  trelo_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (trelo_cia_trelo, trelo_cia_cia),
  KEY trelo_cia_trelo (trelo_cia_trelo),
  KEY trelo_cia_cia (trelo_cia_cia),
  CONSTRAINT trelo_cia_cia FOREIGN KEY (trelo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_cia_trelo FOREIGN KEY (trelo_cia_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_gestao;

CREATE TABLE trelo_gestao (
	trelo_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	trelo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,

	trelo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY trelo_gestao_id (trelo_gestao_id),
	KEY trelo_gestao_trelo (trelo_gestao_trelo),
	KEY trelo_gestao_semelhante (trelo_gestao_semelhante),
	KEY trelo_gestao_projeto (trelo_gestao_projeto),
	KEY trelo_gestao_tarefa (trelo_gestao_tarefa),
	KEY trelo_gestao_perspectiva (trelo_gestao_perspectiva),
	KEY trelo_gestao_tema (trelo_gestao_tema),
	KEY trelo_gestao_objetivo (trelo_gestao_objetivo),
	KEY trelo_gestao_estrategia (trelo_gestao_estrategia),
	KEY trelo_gestao_meta (trelo_gestao_meta),
	KEY trelo_gestao_fator (trelo_gestao_fator),
	KEY trelo_gestao_pratica (trelo_gestao_pratica),
	KEY trelo_gestao_indicador (trelo_gestao_indicador),
	KEY trelo_gestao_acao (trelo_gestao_acao),
	KEY trelo_gestao_canvas (trelo_gestao_canvas),
	KEY trelo_gestao_risco (trelo_gestao_risco),
	KEY trelo_gestao_risco_resposta (trelo_gestao_risco_resposta),
	KEY trelo_gestao_calendario (trelo_gestao_calendario),
	KEY trelo_gestao_monitoramento (trelo_gestao_monitoramento),
	KEY trelo_gestao_ata (trelo_gestao_ata),
	KEY trelo_gestao_mswot(trelo_gestao_mswot),
	KEY trelo_gestao_swot(trelo_gestao_swot),
	KEY trelo_gestao_operativo(trelo_gestao_operativo),
	KEY trelo_gestao_instrumento (trelo_gestao_instrumento),
	KEY trelo_gestao_recurso (trelo_gestao_recurso),
	KEY trelo_gestao_problema (trelo_gestao_problema),
	KEY trelo_gestao_demanda (trelo_gestao_demanda),
	KEY trelo_gestao_programa (trelo_gestao_programa),
	KEY trelo_gestao_licao (trelo_gestao_licao),
	KEY trelo_gestao_evento (trelo_gestao_evento),
	KEY trelo_gestao_link (trelo_gestao_link),
	KEY trelo_gestao_avaliacao (trelo_gestao_avaliacao),
	KEY trelo_gestao_tgn (trelo_gestao_tgn),
	KEY trelo_gestao_brainstorm (trelo_gestao_brainstorm),
	KEY trelo_gestao_gut (trelo_gestao_gut),
	KEY trelo_gestao_causa_efeito (trelo_gestao_causa_efeito),
	KEY trelo_gestao_arquivo (trelo_gestao_arquivo),
	KEY trelo_gestao_forum (trelo_gestao_forum),
	KEY trelo_gestao_checklist (trelo_gestao_checklist),
	KEY trelo_gestao_agenda (trelo_gestao_agenda),
	KEY trelo_gestao_agrupamento (trelo_gestao_agrupamento),
	KEY trelo_gestao_patrocinador (trelo_gestao_patrocinador),
	KEY trelo_gestao_template (trelo_gestao_template),
	KEY trelo_gestao_painel (trelo_gestao_painel),
	KEY trelo_gestao_painel_odometro (trelo_gestao_painel_odometro),
	KEY trelo_gestao_painel_composicao (trelo_gestao_painel_composicao),
	KEY trelo_gestao_tr (trelo_gestao_tr),
	KEY trelo_gestao_me (trelo_gestao_me),
	KEY trelo_gestao_acao_item (trelo_gestao_acao_item),
	KEY trelo_gestao_beneficio (trelo_gestao_beneficio),
	KEY trelo_gestao_painel_slideshow (trelo_gestao_painel_slideshow),
	KEY trelo_gestao_projeto_viabilidade (trelo_gestao_projeto_viabilidade),
	KEY trelo_gestao_projeto_abertura (trelo_gestao_projeto_abertura),
	KEY trelo_gestao_plano_gestao (trelo_gestao_plano_gestao),
	KEY trelo_gestao_ssti (trelo_gestao_ssti),
	KEY trelo_gestao_laudo (trelo_gestao_laudo),
	
	KEY trelo_gestao_trelo_cartao (trelo_gestao_trelo_cartao),
	KEY trelo_gestao_pdcl (trelo_gestao_pdcl),
	KEY trelo_gestao_pdcl_item (trelo_gestao_pdcl_item),
	CONSTRAINT trelo_gestao_trelo FOREIGN KEY (trelo_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_semelhante FOREIGN KEY (trelo_gestao_semelhante) REFERENCES trelo (trelo_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_projeto FOREIGN KEY (trelo_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_tarefa FOREIGN KEY (trelo_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_perspectiva FOREIGN KEY (trelo_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_tema FOREIGN KEY (trelo_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_objetivo FOREIGN KEY (trelo_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_fator FOREIGN KEY (trelo_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_estrategia FOREIGN KEY (trelo_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_meta FOREIGN KEY (trelo_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_pratica FOREIGN KEY (trelo_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_indicador FOREIGN KEY (trelo_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_acao FOREIGN KEY (trelo_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_canvas FOREIGN KEY (trelo_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_risco FOREIGN KEY (trelo_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_risco_resposta FOREIGN KEY (trelo_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_calendario FOREIGN KEY (trelo_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_monitoramento FOREIGN KEY (trelo_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_ata FOREIGN KEY (trelo_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_mswot FOREIGN KEY (trelo_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_swot FOREIGN KEY (trelo_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_operativo FOREIGN KEY (trelo_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_instrumento FOREIGN KEY (trelo_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_recurso FOREIGN KEY (trelo_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_problema FOREIGN KEY (trelo_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_demanda FOREIGN KEY (trelo_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_programa FOREIGN KEY (trelo_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_licao FOREIGN KEY (trelo_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_evento FOREIGN KEY (trelo_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_link FOREIGN KEY (trelo_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_avaliacao FOREIGN KEY (trelo_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_tgn FOREIGN KEY (trelo_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_brainstorm FOREIGN KEY (trelo_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_gut FOREIGN KEY (trelo_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_causa_efeito FOREIGN KEY (trelo_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_arquivo FOREIGN KEY (trelo_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_forum FOREIGN KEY (trelo_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_checklist FOREIGN KEY (trelo_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_agenda FOREIGN KEY (trelo_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_agrupamento FOREIGN KEY (trelo_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_patrocinador FOREIGN KEY (trelo_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_template FOREIGN KEY (trelo_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_painel FOREIGN KEY (trelo_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_painel_odometro FOREIGN KEY (trelo_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_painel_composicao FOREIGN KEY (trelo_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_tr FOREIGN KEY (trelo_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_me FOREIGN KEY (trelo_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_acao_item FOREIGN KEY (trelo_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_beneficio FOREIGN KEY (trelo_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_painel_slideshow FOREIGN KEY (trelo_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_projeto_viabilidade FOREIGN KEY (trelo_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_projeto_abertura FOREIGN KEY (trelo_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_plano_gestao FOREIGN KEY (trelo_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_ssti FOREIGN KEY (trelo_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_laudo FOREIGN KEY (trelo_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,

	CONSTRAINT trelo_gestao_trelo_cartao FOREIGN KEY (trelo_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_pdcl FOREIGN KEY (trelo_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_gestao_pdcl_item FOREIGN KEY (trelo_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE favorito_trava ADD COLUMN favorito_trava_trelo TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_trelo TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_trelo TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura ADD COLUMN assinatura_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_trelo (assinatura_trelo);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_trelo FOREIGN KEY (assinatura_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_trelo (projeto_gestao_trelo);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_trelo FOREIGN KEY (projeto_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_trelo (perspectiva_gestao_trelo);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_trelo FOREIGN KEY (perspectiva_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_trelo (tema_gestao_trelo);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_trelo FOREIGN KEY (tema_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_trelo (objetivo_gestao_trelo);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_trelo FOREIGN KEY (objetivo_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_trelo (fator_gestao_trelo);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_trelo FOREIGN KEY (fator_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_trelo (estrategia_gestao_trelo);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_trelo FOREIGN KEY (estrategia_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_trelo (meta_gestao_trelo);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_trelo FOREIGN KEY (meta_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_trelo (pratica_gestao_trelo);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_trelo FOREIGN KEY (pratica_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_trelo (pratica_indicador_gestao_trelo);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_trelo FOREIGN KEY (pratica_indicador_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_trelo (plano_acao_gestao_trelo);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_trelo FOREIGN KEY (plano_acao_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_trelo (canvas_gestao_trelo);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_trelo FOREIGN KEY (canvas_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_trelo (risco_gestao_trelo);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_trelo FOREIGN KEY (risco_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_trelo (risco_resposta_gestao_trelo);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_trelo FOREIGN KEY (risco_resposta_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_trelo (calendario_gestao_trelo);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_trelo FOREIGN KEY (calendario_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_trelo (monitoramento_gestao_trelo);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_trelo FOREIGN KEY (monitoramento_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_trelo (ata_gestao_trelo);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_trelo FOREIGN KEY (ata_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_trelo (mswot_gestao_trelo);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_trelo FOREIGN KEY (mswot_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_trelo (swot_gestao_trelo);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_trelo FOREIGN KEY (swot_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_trelo (operativo_gestao_trelo);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_trelo FOREIGN KEY (operativo_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_trelo (instrumento_gestao_trelo);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_trelo FOREIGN KEY (instrumento_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_trelo (recurso_gestao_trelo);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_trelo FOREIGN KEY (recurso_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_trelo (problema_gestao_trelo);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_trelo FOREIGN KEY (problema_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_trelo (demanda_gestao_trelo);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_trelo FOREIGN KEY (demanda_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_trelo (programa_gestao_trelo);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_trelo FOREIGN KEY (programa_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_trelo (licao_gestao_trelo);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_trelo FOREIGN KEY (licao_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_trelo (evento_gestao_trelo);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_trelo FOREIGN KEY (evento_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_trelo (link_gestao_trelo);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_trelo FOREIGN KEY (link_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_trelo (avaliacao_gestao_trelo);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_trelo FOREIGN KEY (avaliacao_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_trelo (tgn_gestao_trelo);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_trelo FOREIGN KEY (tgn_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_trelo (brainstorm_gestao_trelo);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_trelo FOREIGN KEY (brainstorm_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_trelo (gut_gestao_trelo);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_trelo FOREIGN KEY (gut_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_trelo (causa_efeito_gestao_trelo);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_trelo FOREIGN KEY (causa_efeito_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_trelo (arquivo_gestao_trelo);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_trelo FOREIGN KEY (arquivo_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_trelo (forum_gestao_trelo);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_trelo FOREIGN KEY (forum_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_trelo (checklist_gestao_trelo);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_trelo FOREIGN KEY (checklist_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_trelo (agenda_gestao_trelo);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_trelo FOREIGN KEY (agenda_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_trelo (agrupamento_gestao_trelo);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_trelo FOREIGN KEY (agrupamento_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_trelo (patrocinador_gestao_trelo);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_trelo FOREIGN KEY (patrocinador_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_trelo (template_gestao_trelo);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_trelo FOREIGN KEY (template_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_trelo (painel_gestao_trelo);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_trelo FOREIGN KEY (painel_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_trelo (painel_odometro_gestao_trelo);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_trelo FOREIGN KEY (painel_odometro_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_trelo (painel_composicao_gestao_trelo);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_trelo FOREIGN KEY (painel_composicao_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_trelo (tr_gestao_trelo);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_trelo FOREIGN KEY (tr_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_trelo (me_gestao_trelo);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_trelo FOREIGN KEY (me_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_trelo (plano_acao_item_gestao_trelo);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_trelo FOREIGN KEY (plano_acao_item_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_trelo (beneficio_gestao_trelo);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_trelo FOREIGN KEY (beneficio_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_trelo (painel_slideshow_gestao_trelo);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_trelo FOREIGN KEY (painel_slideshow_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_trelo (projeto_viabilidade_gestao_trelo);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_trelo FOREIGN KEY (projeto_viabilidade_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_trelo (projeto_abertura_gestao_trelo);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_trelo FOREIGN KEY (projeto_abertura_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_trelo (plano_gestao_gestao_trelo);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_trelo FOREIGN KEY (plano_gestao_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_trelo (arquivo_pasta_gestao_trelo);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_trelo FOREIGN KEY (arquivo_pasta_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_trelo (modelo_gestao_trelo);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_trelo FOREIGN KEY (modelo_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_trelo (msg_gestao_trelo);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_trelo FOREIGN KEY (msg_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_trelo (log_trelo);
ALTER TABLE log ADD CONSTRAINT log_trelo FOREIGN KEY (log_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE pi ADD COLUMN pi_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_trelo (pi_trelo);
ALTER TABLE pi ADD CONSTRAINT pi_trelo FOREIGN KEY (pi_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_trelo (ptres_trelo);
ALTER TABLE ptres ADD CONSTRAINT ptres_trelo FOREIGN KEY (ptres_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_trelo TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_trelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_trelo (priorizacao_trelo);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_trelo FOREIGN KEY (priorizacao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE;	



DROP TABLE IF EXISTS trelo_cartao;

CREATE TABLE trelo_cartao (
  trelo_cartao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  trelo_cartao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_cartao_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_cartao_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_cartao_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  trelo_cartao_nome VARCHAR(255) DEFAULT NULL,
 	trelo_cartao_descricao MEDIUMTEXT,
  trelo_cartao_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  trelo_cartao_status INTEGER(10) DEFAULT 0,
  trelo_cartao_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  trelo_cartao_cor VARCHAR(6) DEFAULT 'ffffff',
  trelo_cartao_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  trelo_cartao_aprovado TINYINT(1) DEFAULT 0,
  trelo_cartao_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (trelo_cartao_id),
  KEY trelo_cartao_cia (trelo_cartao_cia),
  KEY trelo_cartao_dept (trelo_cartao_dept),
	KEY trelo_cartao_responsavel (trelo_cartao_responsavel),
  KEY trelo_cartao_principal_indicador (trelo_cartao_principal_indicador),
	KEY trelo_cartao_moeda (trelo_cartao_moeda),
  CONSTRAINT trelo_cartao_responsavel FOREIGN KEY (trelo_cartao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_cia FOREIGN KEY (trelo_cartao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_dept FOREIGN KEY (trelo_cartao_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_principal_indicador FOREIGN KEY (trelo_cartao_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_moeda FOREIGN KEY (trelo_cartao_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_cartao_dept;

CREATE TABLE trelo_cartao_dept (
  trelo_cartao_dept_trelo_cartao INTEGER(100) UNSIGNED NOT NULL,
  trelo_cartao_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (trelo_cartao_dept_trelo_cartao, trelo_cartao_dept_dept),
  KEY trelo_cartao_dept_trelo_cartao (trelo_cartao_dept_trelo_cartao),
  KEY trelo_cartao_dept_dept (trelo_cartao_dept_dept),
  CONSTRAINT trelo_cartao_dept_dept FOREIGN KEY (trelo_cartao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_dept_trelo_cartao FOREIGN KEY (trelo_cartao_dept_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_cartao_usuario;

CREATE TABLE trelo_cartao_usuario (
  trelo_cartao_usuario_trelo_cartao INTEGER(100) UNSIGNED NOT NULL,
  trelo_cartao_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY trelo_cartao_usuario_trelo_cartao (trelo_cartao_usuario_trelo_cartao),
  KEY trelo_cartao_usuario_usuario (trelo_cartao_usuario_usuario),
  CONSTRAINT trelo_cartao_usuario_trelo_cartao FOREIGN KEY (trelo_cartao_usuario_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_usuario_usuario FOREIGN KEY (trelo_cartao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_cartao_cia;

CREATE TABLE trelo_cartao_cia (
  trelo_cartao_cia_trelo_cartao INTEGER(100) UNSIGNED NOT NULL,
  trelo_cartao_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (trelo_cartao_cia_trelo_cartao, trelo_cartao_cia_cia),
  KEY trelo_cartao_cia_trelo_cartao (trelo_cartao_cia_trelo_cartao),
  KEY trelo_cartao_cia_cia (trelo_cartao_cia_cia),
  CONSTRAINT trelo_cartao_cia_cia FOREIGN KEY (trelo_cartao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT trelo_cartao_cia_trelo_cartao FOREIGN KEY (trelo_cartao_cia_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS trelo_cartao_gestao;

CREATE TABLE trelo_cartao_gestao (
	trelo_cartao_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	trelo_cartao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,

	trelo_cartao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	trelo_cartao_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY trelo_cartao_gestao_id (trelo_cartao_gestao_id),
	KEY trelo_cartao_gestao_trelo_cartao (trelo_cartao_gestao_trelo_cartao),
	KEY trelo_cartao_gestao_semelhante (trelo_cartao_gestao_semelhante),
	KEY trelo_cartao_gestao_projeto (trelo_cartao_gestao_projeto),
	KEY trelo_cartao_gestao_tarefa (trelo_cartao_gestao_tarefa),
	KEY trelo_cartao_gestao_perspectiva (trelo_cartao_gestao_perspectiva),
	KEY trelo_cartao_gestao_tema (trelo_cartao_gestao_tema),
	KEY trelo_cartao_gestao_objetivo (trelo_cartao_gestao_objetivo),
	KEY trelo_cartao_gestao_estrategia (trelo_cartao_gestao_estrategia),
	KEY trelo_cartao_gestao_meta (trelo_cartao_gestao_meta),
	KEY trelo_cartao_gestao_fator (trelo_cartao_gestao_fator),
	KEY trelo_cartao_gestao_pratica (trelo_cartao_gestao_pratica),
	KEY trelo_cartao_gestao_indicador (trelo_cartao_gestao_indicador),
	KEY trelo_cartao_gestao_acao (trelo_cartao_gestao_acao),
	KEY trelo_cartao_gestao_canvas (trelo_cartao_gestao_canvas),
	KEY trelo_cartao_gestao_risco (trelo_cartao_gestao_risco),
	KEY trelo_cartao_gestao_risco_resposta (trelo_cartao_gestao_risco_resposta),
	KEY trelo_cartao_gestao_calendario (trelo_cartao_gestao_calendario),
	KEY trelo_cartao_gestao_monitoramento (trelo_cartao_gestao_monitoramento),
	KEY trelo_cartao_gestao_ata (trelo_cartao_gestao_ata),
	KEY trelo_cartao_gestao_mswot(trelo_cartao_gestao_mswot),
	KEY trelo_cartao_gestao_swot(trelo_cartao_gestao_swot),
	KEY trelo_cartao_gestao_operativo(trelo_cartao_gestao_operativo),
	KEY trelo_cartao_gestao_instrumento (trelo_cartao_gestao_instrumento),
	KEY trelo_cartao_gestao_recurso (trelo_cartao_gestao_recurso),
	KEY trelo_cartao_gestao_problema (trelo_cartao_gestao_problema),
	KEY trelo_cartao_gestao_demanda (trelo_cartao_gestao_demanda),
	KEY trelo_cartao_gestao_programa (trelo_cartao_gestao_programa),
	KEY trelo_cartao_gestao_licao (trelo_cartao_gestao_licao),
	KEY trelo_cartao_gestao_evento (trelo_cartao_gestao_evento),
	KEY trelo_cartao_gestao_link (trelo_cartao_gestao_link),
	KEY trelo_cartao_gestao_avaliacao (trelo_cartao_gestao_avaliacao),
	KEY trelo_cartao_gestao_tgn (trelo_cartao_gestao_tgn),
	KEY trelo_cartao_gestao_brainstorm (trelo_cartao_gestao_brainstorm),
	KEY trelo_cartao_gestao_gut (trelo_cartao_gestao_gut),
	KEY trelo_cartao_gestao_causa_efeito (trelo_cartao_gestao_causa_efeito),
	KEY trelo_cartao_gestao_arquivo (trelo_cartao_gestao_arquivo),
	KEY trelo_cartao_gestao_forum (trelo_cartao_gestao_forum),
	KEY trelo_cartao_gestao_checklist (trelo_cartao_gestao_checklist),
	KEY trelo_cartao_gestao_agenda (trelo_cartao_gestao_agenda),
	KEY trelo_cartao_gestao_agrupamento (trelo_cartao_gestao_agrupamento),
	KEY trelo_cartao_gestao_patrocinador (trelo_cartao_gestao_patrocinador),
	KEY trelo_cartao_gestao_template (trelo_cartao_gestao_template),
	KEY trelo_cartao_gestao_painel (trelo_cartao_gestao_painel),
	KEY trelo_cartao_gestao_painel_odometro (trelo_cartao_gestao_painel_odometro),
	KEY trelo_cartao_gestao_painel_composicao (trelo_cartao_gestao_painel_composicao),
	KEY trelo_cartao_gestao_tr (trelo_cartao_gestao_tr),
	KEY trelo_cartao_gestao_me (trelo_cartao_gestao_me),
	KEY trelo_cartao_gestao_acao_item (trelo_cartao_gestao_acao_item),
	KEY trelo_cartao_gestao_beneficio (trelo_cartao_gestao_beneficio),
	KEY trelo_cartao_gestao_painel_slideshow (trelo_cartao_gestao_painel_slideshow),
	KEY trelo_cartao_gestao_projeto_viabilidade (trelo_cartao_gestao_projeto_viabilidade),
	KEY trelo_cartao_gestao_projeto_abertura (trelo_cartao_gestao_projeto_abertura),
	KEY trelo_cartao_gestao_plano_gestao (trelo_cartao_gestao_plano_gestao),
	KEY trelo_cartao_gestao_ssti (trelo_cartao_gestao_ssti),
	KEY trelo_cartao_gestao_laudo (trelo_cartao_gestao_laudo),
	KEY trelo_cartao_gestao_trelo (trelo_cartao_gestao_trelo),

	KEY trelo_cartao_gestao_pdcl (trelo_cartao_gestao_pdcl),
	KEY trelo_cartao_gestao_pdcl_item (trelo_cartao_gestao_pdcl_item),
	CONSTRAINT trelo_cartao_gestao_trelo_cartao FOREIGN KEY (trelo_cartao_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_semelhante FOREIGN KEY (trelo_cartao_gestao_semelhante) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_projeto FOREIGN KEY (trelo_cartao_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_tarefa FOREIGN KEY (trelo_cartao_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_perspectiva FOREIGN KEY (trelo_cartao_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_tema FOREIGN KEY (trelo_cartao_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_objetivo FOREIGN KEY (trelo_cartao_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_fator FOREIGN KEY (trelo_cartao_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_estrategia FOREIGN KEY (trelo_cartao_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_meta FOREIGN KEY (trelo_cartao_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_pratica FOREIGN KEY (trelo_cartao_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_indicador FOREIGN KEY (trelo_cartao_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_acao FOREIGN KEY (trelo_cartao_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_canvas FOREIGN KEY (trelo_cartao_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_risco FOREIGN KEY (trelo_cartao_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_risco_resposta FOREIGN KEY (trelo_cartao_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_calendario FOREIGN KEY (trelo_cartao_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_monitoramento FOREIGN KEY (trelo_cartao_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_ata FOREIGN KEY (trelo_cartao_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_mswot FOREIGN KEY (trelo_cartao_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_swot FOREIGN KEY (trelo_cartao_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_operativo FOREIGN KEY (trelo_cartao_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_instrumento FOREIGN KEY (trelo_cartao_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_recurso FOREIGN KEY (trelo_cartao_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_problema FOREIGN KEY (trelo_cartao_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_demanda FOREIGN KEY (trelo_cartao_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_programa FOREIGN KEY (trelo_cartao_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_licao FOREIGN KEY (trelo_cartao_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_evento FOREIGN KEY (trelo_cartao_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_link FOREIGN KEY (trelo_cartao_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_avaliacao FOREIGN KEY (trelo_cartao_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_tgn FOREIGN KEY (trelo_cartao_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_brainstorm FOREIGN KEY (trelo_cartao_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_gut FOREIGN KEY (trelo_cartao_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_causa_efeito FOREIGN KEY (trelo_cartao_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_arquivo FOREIGN KEY (trelo_cartao_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_forum FOREIGN KEY (trelo_cartao_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_checklist FOREIGN KEY (trelo_cartao_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_agenda FOREIGN KEY (trelo_cartao_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_agrupamento FOREIGN KEY (trelo_cartao_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_patrocinador FOREIGN KEY (trelo_cartao_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_template FOREIGN KEY (trelo_cartao_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_painel FOREIGN KEY (trelo_cartao_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_painel_odometro FOREIGN KEY (trelo_cartao_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_painel_composicao FOREIGN KEY (trelo_cartao_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_tr FOREIGN KEY (trelo_cartao_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_me FOREIGN KEY (trelo_cartao_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_acao_item FOREIGN KEY (trelo_cartao_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_beneficio FOREIGN KEY (trelo_cartao_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_painel_slideshow FOREIGN KEY (trelo_cartao_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_projeto_viabilidade FOREIGN KEY (trelo_cartao_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_projeto_abertura FOREIGN KEY (trelo_cartao_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_plano_gestao FOREIGN KEY (trelo_cartao_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_ssti FOREIGN KEY (trelo_cartao_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_laudo FOREIGN KEY (trelo_cartao_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_trelo FOREIGN KEY (trelo_cartao_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,

	CONSTRAINT trelo_cartao_gestao_pdcl FOREIGN KEY (trelo_cartao_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT trelo_cartao_gestao_pdcl_item FOREIGN KEY (trelo_cartao_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE favorito_trava ADD COLUMN favorito_trava_trelo_cartao TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_trelo_cartao TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_trelo_cartao TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura ADD COLUMN assinatura_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_trelo_cartao (assinatura_trelo_cartao);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_trelo_cartao FOREIGN KEY (assinatura_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_trelo_cartao (projeto_gestao_trelo_cartao);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_trelo_cartao FOREIGN KEY (projeto_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_trelo_cartao (perspectiva_gestao_trelo_cartao);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_trelo_cartao FOREIGN KEY (perspectiva_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_trelo_cartao (tema_gestao_trelo_cartao);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_trelo_cartao FOREIGN KEY (tema_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_trelo_cartao (objetivo_gestao_trelo_cartao);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_trelo_cartao FOREIGN KEY (objetivo_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_trelo_cartao (fator_gestao_trelo_cartao);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_trelo_cartao FOREIGN KEY (fator_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_trelo_cartao (estrategia_gestao_trelo_cartao);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_trelo_cartao FOREIGN KEY (estrategia_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_trelo_cartao (meta_gestao_trelo_cartao);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_trelo_cartao FOREIGN KEY (meta_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_trelo_cartao (pratica_gestao_trelo_cartao);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_trelo_cartao FOREIGN KEY (pratica_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_trelo_cartao (pratica_indicador_gestao_trelo_cartao);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_trelo_cartao FOREIGN KEY (pratica_indicador_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_trelo_cartao (plano_acao_gestao_trelo_cartao);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_trelo_cartao FOREIGN KEY (plano_acao_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_trelo_cartao (canvas_gestao_trelo_cartao);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_trelo_cartao FOREIGN KEY (canvas_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_trelo_cartao (risco_gestao_trelo_cartao);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_trelo_cartao FOREIGN KEY (risco_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_trelo_cartao (risco_resposta_gestao_trelo_cartao);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_trelo_cartao FOREIGN KEY (risco_resposta_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_trelo_cartao (calendario_gestao_trelo_cartao);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_trelo_cartao FOREIGN KEY (calendario_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_trelo_cartao (monitoramento_gestao_trelo_cartao);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_trelo_cartao FOREIGN KEY (monitoramento_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_trelo_cartao (ata_gestao_trelo_cartao);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_trelo_cartao FOREIGN KEY (ata_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_trelo_cartao (mswot_gestao_trelo_cartao);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_trelo_cartao FOREIGN KEY (mswot_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_trelo_cartao (swot_gestao_trelo_cartao);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_trelo_cartao FOREIGN KEY (swot_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_trelo_cartao (operativo_gestao_trelo_cartao);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_trelo_cartao FOREIGN KEY (operativo_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_trelo_cartao (instrumento_gestao_trelo_cartao);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_trelo_cartao FOREIGN KEY (instrumento_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_trelo_cartao (recurso_gestao_trelo_cartao);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_trelo_cartao FOREIGN KEY (recurso_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_trelo_cartao (problema_gestao_trelo_cartao);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_trelo_cartao FOREIGN KEY (problema_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_trelo_cartao (demanda_gestao_trelo_cartao);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_trelo_cartao FOREIGN KEY (demanda_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_trelo_cartao (programa_gestao_trelo_cartao);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_trelo_cartao FOREIGN KEY (programa_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_trelo_cartao (licao_gestao_trelo_cartao);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_trelo_cartao FOREIGN KEY (licao_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_trelo_cartao (evento_gestao_trelo_cartao);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_trelo_cartao FOREIGN KEY (evento_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_trelo_cartao (link_gestao_trelo_cartao);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_trelo_cartao FOREIGN KEY (link_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_trelo_cartao (avaliacao_gestao_trelo_cartao);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_trelo_cartao FOREIGN KEY (avaliacao_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_trelo_cartao (tgn_gestao_trelo_cartao);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_trelo_cartao FOREIGN KEY (tgn_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_trelo_cartao (brainstorm_gestao_trelo_cartao);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_trelo_cartao FOREIGN KEY (brainstorm_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_trelo_cartao (gut_gestao_trelo_cartao);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_trelo_cartao FOREIGN KEY (gut_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_trelo_cartao (causa_efeito_gestao_trelo_cartao);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_trelo_cartao FOREIGN KEY (causa_efeito_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_trelo_cartao (arquivo_gestao_trelo_cartao);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_trelo_cartao FOREIGN KEY (arquivo_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_trelo_cartao (forum_gestao_trelo_cartao);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_trelo_cartao FOREIGN KEY (forum_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_trelo_cartao (checklist_gestao_trelo_cartao);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_trelo_cartao FOREIGN KEY (checklist_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_trelo_cartao (agenda_gestao_trelo_cartao);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_trelo_cartao FOREIGN KEY (agenda_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_trelo_cartao (agrupamento_gestao_trelo_cartao);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_trelo_cartao FOREIGN KEY (agrupamento_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_trelo_cartao (patrocinador_gestao_trelo_cartao);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_trelo_cartao FOREIGN KEY (patrocinador_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_trelo_cartao (template_gestao_trelo_cartao);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_trelo_cartao FOREIGN KEY (template_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_trelo_cartao (painel_gestao_trelo_cartao);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_trelo_cartao FOREIGN KEY (painel_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_trelo_cartao (painel_odometro_gestao_trelo_cartao);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_trelo_cartao FOREIGN KEY (painel_odometro_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_trelo_cartao (painel_composicao_gestao_trelo_cartao);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_trelo_cartao FOREIGN KEY (painel_composicao_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_trelo_cartao (tr_gestao_trelo_cartao);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_trelo_cartao FOREIGN KEY (tr_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_trelo_cartao (me_gestao_trelo_cartao);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_trelo_cartao FOREIGN KEY (me_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_trelo_cartao (plano_acao_item_gestao_trelo_cartao);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_trelo_cartao FOREIGN KEY (plano_acao_item_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_trelo_cartao (beneficio_gestao_trelo_cartao);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_trelo_cartao FOREIGN KEY (beneficio_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_trelo_cartao (painel_slideshow_gestao_trelo_cartao);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_trelo_cartao FOREIGN KEY (painel_slideshow_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_trelo_cartao (projeto_viabilidade_gestao_trelo_cartao);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_trelo_cartao FOREIGN KEY (projeto_viabilidade_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_trelo_cartao (projeto_abertura_gestao_trelo_cartao);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_trelo_cartao FOREIGN KEY (projeto_abertura_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_trelo_cartao (plano_gestao_gestao_trelo_cartao);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_trelo_cartao FOREIGN KEY (plano_gestao_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_trelo_cartao (arquivo_pasta_gestao_trelo_cartao);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_trelo_cartao FOREIGN KEY (arquivo_pasta_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_trelo_cartao (modelo_gestao_trelo_cartao);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_trelo_cartao FOREIGN KEY (modelo_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_trelo_cartao (msg_gestao_trelo_cartao);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_trelo_cartao FOREIGN KEY (msg_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_trelo_cartao (log_trelo_cartao);
ALTER TABLE log ADD CONSTRAINT log_trelo_cartao FOREIGN KEY (log_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE pi ADD COLUMN pi_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_trelo_cartao (pi_trelo_cartao);
ALTER TABLE pi ADD CONSTRAINT pi_trelo_cartao FOREIGN KEY (pi_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_trelo_cartao (ptres_trelo_cartao);
ALTER TABLE ptres ADD CONSTRAINT ptres_trelo_cartao FOREIGN KEY (ptres_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_trelo_cartao TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_trelo_cartao (priorizacao_trelo_cartao);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_trelo_cartao FOREIGN KEY (priorizacao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE;











DROP TABLE IF EXISTS pdcl;

CREATE TABLE pdcl (
  pdcl_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pdcl_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_nome VARCHAR(255) DEFAULT NULL,
 	pdcl_descricao MEDIUMTEXT,
  pdcl_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  pdcl_status INTEGER(10) DEFAULT 0,
  pdcl_inicio DATETIME DEFAULT NULL,
  pdcl_fim DATETIME DEFAULT NULL,
  pdcl_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  pdcl_cor VARCHAR(6) DEFAULT 'ffffff',
  pdcl_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  pdcl_aprovado TINYINT(1) DEFAULT 0,
  pdcl_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (pdcl_id),
  KEY pdcl_cia (pdcl_cia),
  KEY pdcl_dept (pdcl_dept),
	KEY pdcl_responsavel (pdcl_responsavel),
  KEY pdcl_principal_indicador (pdcl_principal_indicador),
	KEY pdcl_moeda (pdcl_moeda),
  CONSTRAINT pdcl_responsavel FOREIGN KEY (pdcl_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_cia FOREIGN KEY (pdcl_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_dept FOREIGN KEY (pdcl_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_principal_indicador FOREIGN KEY (pdcl_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_moeda FOREIGN KEY (pdcl_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_dept;

CREATE TABLE pdcl_dept (
  pdcl_dept_pdcl INTEGER(100) UNSIGNED NOT NULL,
  pdcl_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_dept_pdcl, pdcl_dept_dept),
  KEY pdcl_dept_pdcl (pdcl_dept_pdcl),
  KEY pdcl_dept_dept (pdcl_dept_dept),
  CONSTRAINT pdcl_dept_dept FOREIGN KEY (pdcl_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_dept_pdcl FOREIGN KEY (pdcl_dept_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_usuario;

CREATE TABLE pdcl_usuario (
  pdcl_usuario_pdcl INTEGER(100) UNSIGNED NOT NULL,
  pdcl_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY pdcl_usuario_pdcl (pdcl_usuario_pdcl),
  KEY pdcl_usuario_usuario (pdcl_usuario_usuario),
  CONSTRAINT pdcl_usuario_pdcl FOREIGN KEY (pdcl_usuario_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_usuario_usuario FOREIGN KEY (pdcl_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
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

DROP TABLE IF EXISTS pdcl_gestao;

CREATE TABLE pdcl_gestao (
	pdcl_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	pdcl_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,

	pdcl_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY pdcl_gestao_id (pdcl_gestao_id),
	KEY pdcl_gestao_pdcl (pdcl_gestao_pdcl),
	KEY pdcl_gestao_semelhante (pdcl_gestao_semelhante),
	KEY pdcl_gestao_projeto (pdcl_gestao_projeto),
	KEY pdcl_gestao_tarefa (pdcl_gestao_tarefa),
	KEY pdcl_gestao_perspectiva (pdcl_gestao_perspectiva),
	KEY pdcl_gestao_tema (pdcl_gestao_tema),
	KEY pdcl_gestao_objetivo (pdcl_gestao_objetivo),
	KEY pdcl_gestao_estrategia (pdcl_gestao_estrategia),
	KEY pdcl_gestao_meta (pdcl_gestao_meta),
	KEY pdcl_gestao_fator (pdcl_gestao_fator),
	KEY pdcl_gestao_pratica (pdcl_gestao_pratica),
	KEY pdcl_gestao_indicador (pdcl_gestao_indicador),
	KEY pdcl_gestao_acao (pdcl_gestao_acao),
	KEY pdcl_gestao_canvas (pdcl_gestao_canvas),
	KEY pdcl_gestao_risco (pdcl_gestao_risco),
	KEY pdcl_gestao_risco_resposta (pdcl_gestao_risco_resposta),
	KEY pdcl_gestao_calendario (pdcl_gestao_calendario),
	KEY pdcl_gestao_monitoramento (pdcl_gestao_monitoramento),
	KEY pdcl_gestao_ata (pdcl_gestao_ata),
	KEY pdcl_gestao_mswot(pdcl_gestao_mswot),
	KEY pdcl_gestao_swot(pdcl_gestao_swot),
	KEY pdcl_gestao_operativo(pdcl_gestao_operativo),
	KEY pdcl_gestao_instrumento (pdcl_gestao_instrumento),
	KEY pdcl_gestao_recurso (pdcl_gestao_recurso),
	KEY pdcl_gestao_problema (pdcl_gestao_problema),
	KEY pdcl_gestao_demanda (pdcl_gestao_demanda),
	KEY pdcl_gestao_programa (pdcl_gestao_programa),
	KEY pdcl_gestao_licao (pdcl_gestao_licao),
	KEY pdcl_gestao_evento (pdcl_gestao_evento),
	KEY pdcl_gestao_link (pdcl_gestao_link),
	KEY pdcl_gestao_avaliacao (pdcl_gestao_avaliacao),
	KEY pdcl_gestao_tgn (pdcl_gestao_tgn),
	KEY pdcl_gestao_brainstorm (pdcl_gestao_brainstorm),
	KEY pdcl_gestao_gut (pdcl_gestao_gut),
	KEY pdcl_gestao_causa_efeito (pdcl_gestao_causa_efeito),
	KEY pdcl_gestao_arquivo (pdcl_gestao_arquivo),
	KEY pdcl_gestao_forum (pdcl_gestao_forum),
	KEY pdcl_gestao_checklist (pdcl_gestao_checklist),
	KEY pdcl_gestao_agenda (pdcl_gestao_agenda),
	KEY pdcl_gestao_agrupamento (pdcl_gestao_agrupamento),
	KEY pdcl_gestao_patrocinador (pdcl_gestao_patrocinador),
	KEY pdcl_gestao_template (pdcl_gestao_template),
	KEY pdcl_gestao_painel (pdcl_gestao_painel),
	KEY pdcl_gestao_painel_odometro (pdcl_gestao_painel_odometro),
	KEY pdcl_gestao_painel_composicao (pdcl_gestao_painel_composicao),
	KEY pdcl_gestao_tr (pdcl_gestao_tr),
	KEY pdcl_gestao_me (pdcl_gestao_me),
	KEY pdcl_gestao_acao_item (pdcl_gestao_acao_item),
	KEY pdcl_gestao_beneficio (pdcl_gestao_beneficio),
	KEY pdcl_gestao_painel_slideshow (pdcl_gestao_painel_slideshow),
	KEY pdcl_gestao_projeto_viabilidade (pdcl_gestao_projeto_viabilidade),
	KEY pdcl_gestao_projeto_abertura (pdcl_gestao_projeto_abertura),
	KEY pdcl_gestao_plano_gestao (pdcl_gestao_plano_gestao),
	KEY pdcl_gestao_ssti (pdcl_gestao_ssti),
	KEY pdcl_gestao_laudo (pdcl_gestao_laudo),
	KEY pdcl_gestao_trelo (pdcl_gestao_trelo),
	KEY pdcl_gestao_trelo_cartao (pdcl_gestao_trelo_cartao),

	KEY pdcl_gestao_pdcl_item (pdcl_gestao_pdcl_item),
	CONSTRAINT pdcl_gestao_pdcl FOREIGN KEY (pdcl_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_semelhante FOREIGN KEY (pdcl_gestao_semelhante) REFERENCES pdcl (pdcl_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_projeto FOREIGN KEY (pdcl_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_tarefa FOREIGN KEY (pdcl_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_perspectiva FOREIGN KEY (pdcl_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_tema FOREIGN KEY (pdcl_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_objetivo FOREIGN KEY (pdcl_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_fator FOREIGN KEY (pdcl_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_estrategia FOREIGN KEY (pdcl_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_meta FOREIGN KEY (pdcl_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_pratica FOREIGN KEY (pdcl_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_indicador FOREIGN KEY (pdcl_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_acao FOREIGN KEY (pdcl_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_canvas FOREIGN KEY (pdcl_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_risco FOREIGN KEY (pdcl_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_risco_resposta FOREIGN KEY (pdcl_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_calendario FOREIGN KEY (pdcl_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_monitoramento FOREIGN KEY (pdcl_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_ata FOREIGN KEY (pdcl_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_mswot FOREIGN KEY (pdcl_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_swot FOREIGN KEY (pdcl_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_operativo FOREIGN KEY (pdcl_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_instrumento FOREIGN KEY (pdcl_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_recurso FOREIGN KEY (pdcl_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_problema FOREIGN KEY (pdcl_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_demanda FOREIGN KEY (pdcl_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_programa FOREIGN KEY (pdcl_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_licao FOREIGN KEY (pdcl_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_evento FOREIGN KEY (pdcl_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_link FOREIGN KEY (pdcl_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_avaliacao FOREIGN KEY (pdcl_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_tgn FOREIGN KEY (pdcl_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_brainstorm FOREIGN KEY (pdcl_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_gut FOREIGN KEY (pdcl_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_causa_efeito FOREIGN KEY (pdcl_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_arquivo FOREIGN KEY (pdcl_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_forum FOREIGN KEY (pdcl_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_checklist FOREIGN KEY (pdcl_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_agenda FOREIGN KEY (pdcl_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_agrupamento FOREIGN KEY (pdcl_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_patrocinador FOREIGN KEY (pdcl_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_template FOREIGN KEY (pdcl_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_painel FOREIGN KEY (pdcl_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_painel_odometro FOREIGN KEY (pdcl_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_painel_composicao FOREIGN KEY (pdcl_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_tr FOREIGN KEY (pdcl_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_me FOREIGN KEY (pdcl_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_acao_item FOREIGN KEY (pdcl_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_beneficio FOREIGN KEY (pdcl_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_painel_slideshow FOREIGN KEY (pdcl_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_projeto_viabilidade FOREIGN KEY (pdcl_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_projeto_abertura FOREIGN KEY (pdcl_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_plano_gestao FOREIGN KEY (pdcl_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_ssti FOREIGN KEY (pdcl_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_laudo FOREIGN KEY (pdcl_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_trelo FOREIGN KEY (pdcl_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_gestao_trelo_cartao FOREIGN KEY (pdcl_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,

	CONSTRAINT pdcl_gestao_pdcl_item FOREIGN KEY (pdcl_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE favorito_trava ADD COLUMN favorito_trava_pdcl TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_pdcl TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_pdcl TINYINT(1) DEFAULT 0;


ALTER TABLE assinatura ADD COLUMN assinatura_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_pdcl (assinatura_pdcl);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_pdcl FOREIGN KEY (assinatura_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;





ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_pdcl (projeto_gestao_pdcl);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_pdcl FOREIGN KEY (projeto_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_pdcl (perspectiva_gestao_pdcl);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_pdcl FOREIGN KEY (perspectiva_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_pdcl (tema_gestao_pdcl);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_pdcl FOREIGN KEY (tema_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_pdcl (objetivo_gestao_pdcl);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_pdcl FOREIGN KEY (objetivo_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_pdcl (fator_gestao_pdcl);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_pdcl FOREIGN KEY (fator_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_pdcl (estrategia_gestao_pdcl);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_pdcl FOREIGN KEY (estrategia_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_pdcl (meta_gestao_pdcl);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_pdcl FOREIGN KEY (meta_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_pdcl (pratica_gestao_pdcl);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_pdcl FOREIGN KEY (pratica_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_pdcl (pratica_indicador_gestao_pdcl);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_pdcl FOREIGN KEY (pratica_indicador_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_pdcl (plano_acao_gestao_pdcl);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_pdcl FOREIGN KEY (plano_acao_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_pdcl (canvas_gestao_pdcl);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_pdcl FOREIGN KEY (canvas_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_pdcl (risco_gestao_pdcl);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_pdcl FOREIGN KEY (risco_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_pdcl (risco_resposta_gestao_pdcl);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_pdcl FOREIGN KEY (risco_resposta_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_pdcl (calendario_gestao_pdcl);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_pdcl FOREIGN KEY (calendario_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_pdcl (monitoramento_gestao_pdcl);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_pdcl FOREIGN KEY (monitoramento_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_pdcl (ata_gestao_pdcl);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_pdcl FOREIGN KEY (ata_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_pdcl (mswot_gestao_pdcl);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_pdcl FOREIGN KEY (mswot_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_pdcl (swot_gestao_pdcl);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_pdcl FOREIGN KEY (swot_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_pdcl (operativo_gestao_pdcl);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_pdcl FOREIGN KEY (operativo_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_pdcl (instrumento_gestao_pdcl);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_pdcl FOREIGN KEY (instrumento_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_pdcl (recurso_gestao_pdcl);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_pdcl FOREIGN KEY (recurso_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_pdcl (problema_gestao_pdcl);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_pdcl FOREIGN KEY (problema_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_pdcl (demanda_gestao_pdcl);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_pdcl FOREIGN KEY (demanda_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_pdcl (programa_gestao_pdcl);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_pdcl FOREIGN KEY (programa_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_pdcl (licao_gestao_pdcl);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_pdcl FOREIGN KEY (licao_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_pdcl (evento_gestao_pdcl);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_pdcl FOREIGN KEY (evento_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_pdcl (link_gestao_pdcl);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_pdcl FOREIGN KEY (link_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_pdcl (avaliacao_gestao_pdcl);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_pdcl FOREIGN KEY (avaliacao_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_pdcl (tgn_gestao_pdcl);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_pdcl FOREIGN KEY (tgn_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_pdcl (brainstorm_gestao_pdcl);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_pdcl FOREIGN KEY (brainstorm_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_pdcl (gut_gestao_pdcl);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_pdcl FOREIGN KEY (gut_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_pdcl (causa_efeito_gestao_pdcl);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_pdcl FOREIGN KEY (causa_efeito_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_pdcl (arquivo_gestao_pdcl);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_pdcl FOREIGN KEY (arquivo_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_pdcl (forum_gestao_pdcl);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_pdcl FOREIGN KEY (forum_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_pdcl (checklist_gestao_pdcl);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_pdcl FOREIGN KEY (checklist_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_pdcl (agenda_gestao_pdcl);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_pdcl FOREIGN KEY (agenda_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_pdcl (agrupamento_gestao_pdcl);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_pdcl FOREIGN KEY (agrupamento_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_pdcl (patrocinador_gestao_pdcl);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_pdcl FOREIGN KEY (patrocinador_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_pdcl (template_gestao_pdcl);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_pdcl FOREIGN KEY (template_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_pdcl (painel_gestao_pdcl);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_pdcl FOREIGN KEY (painel_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_pdcl (painel_odometro_gestao_pdcl);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_pdcl FOREIGN KEY (painel_odometro_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_pdcl (painel_composicao_gestao_pdcl);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_pdcl FOREIGN KEY (painel_composicao_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_pdcl (tr_gestao_pdcl);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_pdcl FOREIGN KEY (tr_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_pdcl (me_gestao_pdcl);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_pdcl FOREIGN KEY (me_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_pdcl (plano_acao_item_gestao_pdcl);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_pdcl FOREIGN KEY (plano_acao_item_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_pdcl (beneficio_gestao_pdcl);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_pdcl FOREIGN KEY (beneficio_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_pdcl (painel_slideshow_gestao_pdcl);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_pdcl FOREIGN KEY (painel_slideshow_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_pdcl (projeto_viabilidade_gestao_pdcl);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_pdcl FOREIGN KEY (projeto_viabilidade_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_pdcl (projeto_abertura_gestao_pdcl);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_pdcl FOREIGN KEY (projeto_abertura_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_pdcl (plano_gestao_gestao_pdcl);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_pdcl FOREIGN KEY (plano_gestao_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_pdcl (arquivo_pasta_gestao_pdcl);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_pdcl FOREIGN KEY (arquivo_pasta_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_pdcl (modelo_gestao_pdcl);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_pdcl FOREIGN KEY (modelo_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_pdcl (msg_gestao_pdcl);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_pdcl FOREIGN KEY (msg_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_pdcl (log_pdcl);
ALTER TABLE log ADD CONSTRAINT log_pdcl FOREIGN KEY (log_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE pi ADD COLUMN pi_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_pdcl (pi_pdcl);
ALTER TABLE pi ADD CONSTRAINT pi_pdcl FOREIGN KEY (pi_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_pdcl (ptres_pdcl);
ALTER TABLE ptres ADD CONSTRAINT ptres_pdcl FOREIGN KEY (ptres_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_pdcl TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_pdcl (priorizacao_pdcl);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_pdcl FOREIGN KEY (priorizacao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE IF EXISTS pdcl_item;

CREATE TABLE pdcl_item (
  pdcl_item_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pdcl_item_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pdcl_item_nome VARCHAR(255) DEFAULT NULL,
 	pdcl_item_descricao MEDIUMTEXT,
  pdcl_item_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  pdcl_item_status INTEGER(10) DEFAULT 0,
  pdcl_item_inicio DATETIME DEFAULT NULL,
  pdcl_item_fim DATETIME DEFAULT NULL,
  pdcl_item_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  pdcl_item_cor VARCHAR(6) DEFAULT 'ffffff',
  pdcl_item_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  pdcl_item_aprovado TINYINT(1) DEFAULT 0,
  pdcl_item_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (pdcl_item_id),
  KEY pdcl_item_cia (pdcl_item_cia),
  KEY pdcl_item_dept (pdcl_item_dept),
	KEY pdcl_item_responsavel (pdcl_item_responsavel),
  KEY pdcl_item_principal_indicador (pdcl_item_principal_indicador),
	KEY pdcl_item_moeda (pdcl_item_moeda),
  CONSTRAINT pdcl_item_responsavel FOREIGN KEY (pdcl_item_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_cia FOREIGN KEY (pdcl_item_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_dept FOREIGN KEY (pdcl_item_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_principal_indicador FOREIGN KEY (pdcl_item_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_moeda FOREIGN KEY (pdcl_item_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_item_dept;

CREATE TABLE pdcl_item_dept (
  pdcl_item_dept_pdcl_item INTEGER(100) UNSIGNED NOT NULL,
  pdcl_item_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pdcl_item_dept_pdcl_item, pdcl_item_dept_dept),
  KEY pdcl_item_dept_pdcl_item (pdcl_item_dept_pdcl_item),
  KEY pdcl_item_dept_dept (pdcl_item_dept_dept),
  CONSTRAINT pdcl_item_dept_dept FOREIGN KEY (pdcl_item_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_dept_pdcl_item FOREIGN KEY (pdcl_item_dept_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pdcl_item_usuario;

CREATE TABLE pdcl_item_usuario (
  pdcl_item_usuario_pdcl_item INTEGER(100) UNSIGNED NOT NULL,
  pdcl_item_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY pdcl_item_usuario_pdcl_item (pdcl_item_usuario_pdcl_item),
  KEY pdcl_item_usuario_usuario (pdcl_item_usuario_usuario),
  CONSTRAINT pdcl_item_usuario_pdcl_item FOREIGN KEY (pdcl_item_usuario_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pdcl_item_usuario_usuario FOREIGN KEY (pdcl_item_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
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

DROP TABLE IF EXISTS pdcl_item_gestao;

CREATE TABLE pdcl_item_gestao (
	pdcl_item_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	pdcl_item_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,

	pdcl_item_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	pdcl_item_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY pdcl_item_gestao_id (pdcl_item_gestao_id),
	KEY pdcl_item_gestao_pdcl_item (pdcl_item_gestao_pdcl_item),
	KEY pdcl_item_gestao_semelhante (pdcl_item_gestao_semelhante),
	KEY pdcl_item_gestao_projeto (pdcl_item_gestao_projeto),
	KEY pdcl_item_gestao_tarefa (pdcl_item_gestao_tarefa),
	KEY pdcl_item_gestao_perspectiva (pdcl_item_gestao_perspectiva),
	KEY pdcl_item_gestao_tema (pdcl_item_gestao_tema),
	KEY pdcl_item_gestao_objetivo (pdcl_item_gestao_objetivo),
	KEY pdcl_item_gestao_estrategia (pdcl_item_gestao_estrategia),
	KEY pdcl_item_gestao_meta (pdcl_item_gestao_meta),
	KEY pdcl_item_gestao_fator (pdcl_item_gestao_fator),
	KEY pdcl_item_gestao_pratica (pdcl_item_gestao_pratica),
	KEY pdcl_item_gestao_indicador (pdcl_item_gestao_indicador),
	KEY pdcl_item_gestao_acao (pdcl_item_gestao_acao),
	KEY pdcl_item_gestao_canvas (pdcl_item_gestao_canvas),
	KEY pdcl_item_gestao_risco (pdcl_item_gestao_risco),
	KEY pdcl_item_gestao_risco_resposta (pdcl_item_gestao_risco_resposta),
	KEY pdcl_item_gestao_calendario (pdcl_item_gestao_calendario),
	KEY pdcl_item_gestao_monitoramento (pdcl_item_gestao_monitoramento),
	KEY pdcl_item_gestao_ata (pdcl_item_gestao_ata),
	KEY pdcl_item_gestao_mswot(pdcl_item_gestao_mswot),
	KEY pdcl_item_gestao_swot(pdcl_item_gestao_swot),
	KEY pdcl_item_gestao_operativo(pdcl_item_gestao_operativo),
	KEY pdcl_item_gestao_instrumento (pdcl_item_gestao_instrumento),
	KEY pdcl_item_gestao_recurso (pdcl_item_gestao_recurso),
	KEY pdcl_item_gestao_problema (pdcl_item_gestao_problema),
	KEY pdcl_item_gestao_demanda (pdcl_item_gestao_demanda),
	KEY pdcl_item_gestao_programa (pdcl_item_gestao_programa),
	KEY pdcl_item_gestao_licao (pdcl_item_gestao_licao),
	KEY pdcl_item_gestao_evento (pdcl_item_gestao_evento),
	KEY pdcl_item_gestao_link (pdcl_item_gestao_link),
	KEY pdcl_item_gestao_avaliacao (pdcl_item_gestao_avaliacao),
	KEY pdcl_item_gestao_tgn (pdcl_item_gestao_tgn),
	KEY pdcl_item_gestao_brainstorm (pdcl_item_gestao_brainstorm),
	KEY pdcl_item_gestao_gut (pdcl_item_gestao_gut),
	KEY pdcl_item_gestao_causa_efeito (pdcl_item_gestao_causa_efeito),
	KEY pdcl_item_gestao_arquivo (pdcl_item_gestao_arquivo),
	KEY pdcl_item_gestao_forum (pdcl_item_gestao_forum),
	KEY pdcl_item_gestao_checklist (pdcl_item_gestao_checklist),
	KEY pdcl_item_gestao_agenda (pdcl_item_gestao_agenda),
	KEY pdcl_item_gestao_agrupamento (pdcl_item_gestao_agrupamento),
	KEY pdcl_item_gestao_patrocinador (pdcl_item_gestao_patrocinador),
	KEY pdcl_item_gestao_template (pdcl_item_gestao_template),
	KEY pdcl_item_gestao_painel (pdcl_item_gestao_painel),
	KEY pdcl_item_gestao_painel_odometro (pdcl_item_gestao_painel_odometro),
	KEY pdcl_item_gestao_painel_composicao (pdcl_item_gestao_painel_composicao),
	KEY pdcl_item_gestao_tr (pdcl_item_gestao_tr),
	KEY pdcl_item_gestao_me (pdcl_item_gestao_me),
	KEY pdcl_item_gestao_acao_item (pdcl_item_gestao_acao_item),
	KEY pdcl_item_gestao_beneficio (pdcl_item_gestao_beneficio),
	KEY pdcl_item_gestao_painel_slideshow (pdcl_item_gestao_painel_slideshow),
	KEY pdcl_item_gestao_projeto_viabilidade (pdcl_item_gestao_projeto_viabilidade),
	KEY pdcl_item_gestao_projeto_abertura (pdcl_item_gestao_projeto_abertura),
	KEY pdcl_item_gestao_plano_gestao (pdcl_item_gestao_plano_gestao),
	KEY pdcl_item_gestao_ssti (pdcl_item_gestao_ssti),
	KEY pdcl_item_gestao_laudo (pdcl_item_gestao_laudo),
	KEY pdcl_item_gestao_trelo (pdcl_item_gestao_trelo),
	KEY pdcl_item_gestao_trelo_cartao (pdcl_item_gestao_trelo_cartao),
	KEY pdcl_item_gestao_pdcl (pdcl_item_gestao_pdcl),

	CONSTRAINT pdcl_item_gestao_pdcl_item FOREIGN KEY (pdcl_item_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_semelhante FOREIGN KEY (pdcl_item_gestao_semelhante) REFERENCES pdcl_item (pdcl_item_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_projeto FOREIGN KEY (pdcl_item_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_tarefa FOREIGN KEY (pdcl_item_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_perspectiva FOREIGN KEY (pdcl_item_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_tema FOREIGN KEY (pdcl_item_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_objetivo FOREIGN KEY (pdcl_item_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_fator FOREIGN KEY (pdcl_item_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_estrategia FOREIGN KEY (pdcl_item_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_meta FOREIGN KEY (pdcl_item_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_pratica FOREIGN KEY (pdcl_item_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_indicador FOREIGN KEY (pdcl_item_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_acao FOREIGN KEY (pdcl_item_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_canvas FOREIGN KEY (pdcl_item_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_risco FOREIGN KEY (pdcl_item_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_risco_resposta FOREIGN KEY (pdcl_item_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_calendario FOREIGN KEY (pdcl_item_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_monitoramento FOREIGN KEY (pdcl_item_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_ata FOREIGN KEY (pdcl_item_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_mswot FOREIGN KEY (pdcl_item_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_swot FOREIGN KEY (pdcl_item_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_operativo FOREIGN KEY (pdcl_item_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_instrumento FOREIGN KEY (pdcl_item_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_recurso FOREIGN KEY (pdcl_item_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_problema FOREIGN KEY (pdcl_item_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_demanda FOREIGN KEY (pdcl_item_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_programa FOREIGN KEY (pdcl_item_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_licao FOREIGN KEY (pdcl_item_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_evento FOREIGN KEY (pdcl_item_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_link FOREIGN KEY (pdcl_item_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_avaliacao FOREIGN KEY (pdcl_item_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_tgn FOREIGN KEY (pdcl_item_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_brainstorm FOREIGN KEY (pdcl_item_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_gut FOREIGN KEY (pdcl_item_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_causa_efeito FOREIGN KEY (pdcl_item_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_arquivo FOREIGN KEY (pdcl_item_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_forum FOREIGN KEY (pdcl_item_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_checklist FOREIGN KEY (pdcl_item_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_agenda FOREIGN KEY (pdcl_item_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_agrupamento FOREIGN KEY (pdcl_item_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_patrocinador FOREIGN KEY (pdcl_item_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_template FOREIGN KEY (pdcl_item_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_painel FOREIGN KEY (pdcl_item_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_painel_odometro FOREIGN KEY (pdcl_item_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_painel_composicao FOREIGN KEY (pdcl_item_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_tr FOREIGN KEY (pdcl_item_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_me FOREIGN KEY (pdcl_item_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_acao_item FOREIGN KEY (pdcl_item_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_beneficio FOREIGN KEY (pdcl_item_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_painel_slideshow FOREIGN KEY (pdcl_item_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_projeto_viabilidade FOREIGN KEY (pdcl_item_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_projeto_abertura FOREIGN KEY (pdcl_item_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_plano_gestao FOREIGN KEY (pdcl_item_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_ssti FOREIGN KEY (pdcl_item_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_laudo FOREIGN KEY (pdcl_item_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_trelo FOREIGN KEY (pdcl_item_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_trelo_cartao FOREIGN KEY (pdcl_item_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pdcl_item_gestao_pdcl FOREIGN KEY (pdcl_item_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE favorito_trava ADD COLUMN favorito_trava_pdcl_item TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_pdcl_item TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_pdcl_item TINYINT(1) DEFAULT 0;



ALTER TABLE assinatura ADD COLUMN assinatura_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_pdcl_item (assinatura_pdcl_item);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_pdcl_item FOREIGN KEY (assinatura_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;






ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_pdcl_item (projeto_gestao_pdcl_item);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_pdcl_item FOREIGN KEY (projeto_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_pdcl_item (perspectiva_gestao_pdcl_item);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_pdcl_item FOREIGN KEY (perspectiva_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_pdcl_item (tema_gestao_pdcl_item);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_pdcl_item FOREIGN KEY (tema_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_pdcl_item (objetivo_gestao_pdcl_item);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_pdcl_item FOREIGN KEY (objetivo_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_pdcl_item (fator_gestao_pdcl_item);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_pdcl_item FOREIGN KEY (fator_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_pdcl_item (estrategia_gestao_pdcl_item);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_pdcl_item FOREIGN KEY (estrategia_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_pdcl_item (meta_gestao_pdcl_item);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_pdcl_item FOREIGN KEY (meta_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_pdcl_item (pratica_gestao_pdcl_item);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_pdcl_item FOREIGN KEY (pratica_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_pdcl_item (pratica_indicador_gestao_pdcl_item);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_pdcl_item FOREIGN KEY (pratica_indicador_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_pdcl_item (plano_acao_gestao_pdcl_item);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_pdcl_item FOREIGN KEY (plano_acao_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_pdcl_item (canvas_gestao_pdcl_item);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_pdcl_item FOREIGN KEY (canvas_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_pdcl_item (risco_gestao_pdcl_item);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_pdcl_item FOREIGN KEY (risco_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_pdcl_item (risco_resposta_gestao_pdcl_item);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_pdcl_item FOREIGN KEY (risco_resposta_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_pdcl_item (calendario_gestao_pdcl_item);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_pdcl_item FOREIGN KEY (calendario_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_pdcl_item (monitoramento_gestao_pdcl_item);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_pdcl_item FOREIGN KEY (monitoramento_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_pdcl_item (ata_gestao_pdcl_item);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_pdcl_item FOREIGN KEY (ata_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_pdcl_item (mswot_gestao_pdcl_item);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_pdcl_item FOREIGN KEY (mswot_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_pdcl_item (swot_gestao_pdcl_item);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_pdcl_item FOREIGN KEY (swot_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_pdcl_item (operativo_gestao_pdcl_item);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_pdcl_item FOREIGN KEY (operativo_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_pdcl_item (instrumento_gestao_pdcl_item);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_pdcl_item FOREIGN KEY (instrumento_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_pdcl_item (recurso_gestao_pdcl_item);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_pdcl_item FOREIGN KEY (recurso_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_pdcl_item (problema_gestao_pdcl_item);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_pdcl_item FOREIGN KEY (problema_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_pdcl_item (demanda_gestao_pdcl_item);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_pdcl_item FOREIGN KEY (demanda_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_pdcl_item (programa_gestao_pdcl_item);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_pdcl_item FOREIGN KEY (programa_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_pdcl_item (licao_gestao_pdcl_item);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_pdcl_item FOREIGN KEY (licao_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_pdcl_item (evento_gestao_pdcl_item);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_pdcl_item FOREIGN KEY (evento_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_pdcl_item (link_gestao_pdcl_item);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_pdcl_item FOREIGN KEY (link_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_pdcl_item (avaliacao_gestao_pdcl_item);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_pdcl_item FOREIGN KEY (avaliacao_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_pdcl_item (tgn_gestao_pdcl_item);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_pdcl_item FOREIGN KEY (tgn_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_pdcl_item (brainstorm_gestao_pdcl_item);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_pdcl_item FOREIGN KEY (brainstorm_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_pdcl_item (gut_gestao_pdcl_item);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_pdcl_item FOREIGN KEY (gut_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_pdcl_item (causa_efeito_gestao_pdcl_item);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_pdcl_item FOREIGN KEY (causa_efeito_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_pdcl_item (arquivo_gestao_pdcl_item);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_pdcl_item FOREIGN KEY (arquivo_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_pdcl_item (forum_gestao_pdcl_item);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_pdcl_item FOREIGN KEY (forum_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_pdcl_item (checklist_gestao_pdcl_item);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_pdcl_item FOREIGN KEY (checklist_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_pdcl_item (agenda_gestao_pdcl_item);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_pdcl_item FOREIGN KEY (agenda_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_pdcl_item (agrupamento_gestao_pdcl_item);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_pdcl_item FOREIGN KEY (agrupamento_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_pdcl_item (patrocinador_gestao_pdcl_item);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_pdcl_item FOREIGN KEY (patrocinador_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_pdcl_item (template_gestao_pdcl_item);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_pdcl_item FOREIGN KEY (template_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_pdcl_item (painel_gestao_pdcl_item);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_pdcl_item FOREIGN KEY (painel_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_pdcl_item (painel_odometro_gestao_pdcl_item);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_pdcl_item FOREIGN KEY (painel_odometro_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_pdcl_item (painel_composicao_gestao_pdcl_item);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_pdcl_item FOREIGN KEY (painel_composicao_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_pdcl_item (tr_gestao_pdcl_item);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_pdcl_item FOREIGN KEY (tr_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_pdcl_item (me_gestao_pdcl_item);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_pdcl_item FOREIGN KEY (me_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_pdcl_item (plano_acao_item_gestao_pdcl_item);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_pdcl_item FOREIGN KEY (plano_acao_item_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_pdcl_item (beneficio_gestao_pdcl_item);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_pdcl_item FOREIGN KEY (beneficio_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_pdcl_item (painel_slideshow_gestao_pdcl_item);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_pdcl_item FOREIGN KEY (painel_slideshow_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_pdcl_item (projeto_viabilidade_gestao_pdcl_item);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_pdcl_item FOREIGN KEY (projeto_viabilidade_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_pdcl_item (projeto_abertura_gestao_pdcl_item);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_pdcl_item FOREIGN KEY (projeto_abertura_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_pdcl_item (plano_gestao_gestao_pdcl_item);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_pdcl_item FOREIGN KEY (plano_gestao_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_pdcl_item (arquivo_pasta_gestao_pdcl_item);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_pdcl_item FOREIGN KEY (arquivo_pasta_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_pdcl_item (modelo_gestao_pdcl_item);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_pdcl_item FOREIGN KEY (modelo_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_pdcl_item (msg_gestao_pdcl_item);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_pdcl_item FOREIGN KEY (msg_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_pdcl_item (log_pdcl_item);
ALTER TABLE log ADD CONSTRAINT log_pdcl_item FOREIGN KEY (log_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE pi ADD COLUMN pi_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_pdcl_item (pi_pdcl_item);
ALTER TABLE pi ADD CONSTRAINT pi_pdcl_item FOREIGN KEY (pi_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_pdcl_item (ptres_pdcl_item);
ALTER TABLE ptres ADD CONSTRAINT ptres_pdcl_item FOREIGN KEY (ptres_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_pdcl_item TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_pdcl_item (priorizacao_pdcl_item);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_pdcl_item FOREIGN KEY (priorizacao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE;






DELETE FROM config WHERE config_nome='trelo';
DELETE FROM config WHERE config_nome='trelos';
DELETE FROM config WHERE config_nome='genero_trelo';
DELETE FROM config WHERE config_nome='qnt_trelo';		

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('trelo','trelo','legenda','text'),
	('trelos','trelos','legenda','text'),
	('genero_trelo','o','legenda','select'),
	('qnt_trelo','30','qnt','quantidade');
	
DELETE FROM config_lista WHERE config_nome='genero_trelo';

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_trelo','o'),
  ('genero_trelo','a');  
  
  
DELETE FROM config WHERE config_nome='trelo_cartao';
DELETE FROM config WHERE config_nome='trelo_cartaos';
DELETE FROM config WHERE config_nome='genero_trelo_cartao';
DELETE FROM config WHERE config_nome='qnt_trelo_cartao';	
	
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('trelo_cartao','cartão','legenda','text'),
	('trelo_cartaos','cartôes','legenda','text'),
	('genero_trelo_cartao','o','legenda','select'),
	('qnt_trelo_cartao','30','qnt','quantidade');
	
DELETE FROM config_lista WHERE config_nome='genero_trelo_cartao';

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_trelo_cartao','o'),
  ('genero_trelo_cartao','a');	
  
DELETE FROM config WHERE config_nome='pdcl';
DELETE FROM config WHERE config_nome='pdcls';
DELETE FROM config WHERE config_nome='genero_pdcl';
DELETE FROM config WHERE config_nome='qnt_pdcl';		 

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('pdcl','PDCL','legenda','text'),
	('pdcls','PDCLs','legenda','text'),
	('genero_pdcl','o','legenda','select'),
	('qnt_pdcl','30','qnt','quantidade');
	
DELETE FROM config_lista WHERE config_nome='genero_pdcl';

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_pdcl','o'),
  ('genero_pdcl','a');
  
  
DELETE FROM config WHERE config_nome='pdcl_item';
DELETE FROM config WHERE config_nome='pdcl_items';
DELETE FROM config WHERE config_nome='genero_pdcl_item';
DELETE FROM config WHERE config_nome='qnt_pdcl_item';
		 
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('pdcl_item','item PDCL','legenda','text'),
	('pdcl_items','itens PDCL','legenda','text'),
	('genero_pdcl_item','o','legenda','select'),
	('qnt_pdcl_item','30','qnt','quantidade');

DELETE FROM config_lista WHERE config_nome='genero_pdcl_item';

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_pdcl_item','o'),
  ('genero_pdcl_item','a');	  
  
  
DELETE FROM config WHERE config_nome='ssti';
DELETE FROM config WHERE config_nome='sstis';
DELETE FROM config WHERE config_nome='genero_ssti';
DELETE FROM config WHERE config_nome='qnt_ssti';
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('ssti','SSTI','legenda','text'),
	('sstis','SSTIs','legenda','text'),
	('genero_ssti','a','legenda','select'),
	('qnt_ssti','30','qnt','quantidade');

DELETE FROM config_lista WHERE config_nome='genero_ssti';
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_ssti','o'),
  ('genero_ssti','a');
  
  
DELETE FROM config WHERE config_nome='laudo';
DELETE FROM config WHERE config_nome='laudos';
DELETE FROM config WHERE config_nome='genero_laudo';
DELETE FROM config WHERE config_nome='qnt_laudo';	
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('laudo','laudo técnico','legenda','text'),
	('laudos','laudos técnicos','legenda','text'),
	('genero_laudo','o','legenda','select'),
	('qnt_laudo','30','qnt','quantidade');

DELETE FROM config_lista WHERE config_nome='genero_laudo';
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_laudo','o'),
  ('genero_laudo','a');	     
    