SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-01-24';
UPDATE versao SET ultima_atualizacao_codigo='2019-01-24';
UPDATE versao SET versao_bd=544;

ALTER TABLE instrumento ADD COLUMN instrumento_valor_repasse DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER instrumento_valor_contrapartida;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_valor_repasse TINYINT(1) DEFAULT 1 AFTER instrumento_valor_contrapartida;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_valor_repasse_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_valor_repasse;
UPDATE instrumento_campo SET instrumento_valor_repasse_leg='Valor do repasse';
UPDATE instrumento_campo SET instrumento_valor_repasse=1;

ALTER TABLE instrumento_avulso_custo ADD COLUMN instrumento_avulso_custo_percentual TINYINT(1) DEFAULT 0 AFTER instrumento_avulso_custo_acrescimo;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_avulso_custo_percentual TINYINT(1) DEFAULT 1 AFTER instrumento_avulso_custo_acrescimo_leg2;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_avulso_custo_percentual_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_avulso_custo_percentual;
UPDATE instrumento_campo SET instrumento_avulso_custo_percentual_leg='Usar percentual em vez de quantitativo nos acréscimos de itens';
UPDATE instrumento_campo SET instrumento_avulso_custo_percentual=0;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('instrumentos','instrumento_valor_repasse','Valor de Repasse',0);

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('os','ordem de serviço','legenda','text'),
	('oss','ordens de serviço','legenda','text'),
	('genero_os','a','legenda','select'),
	('qnt_os','30','qnt','quantidade');

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('oss','os_relacionado','Relacionamento', 0),
	('oss','os_cias','cias', 0),
	('oss','os_depts','depts', 0),
	('oss','os_designados','Designados', 0),
	('oss','os_cia','cia', 1),
	('oss','os_dept','dept', 0),
	('oss','os_responsavel','Responsável', 1),
	('oss','os_cadastrador','Cadastrador', 0),
	('oss','os_requisitante','Requisitante', 0),
	('oss','os_gestor','Gestor', 0),
	('oss','os_principal_indicador','Indicador', 0),
	('oss','os_tr','tr', 0),
	('oss','os_instrumento','instrumento', 0),
	('oss','os_numero','Número', 1),
	('oss','os_nome','Nome', 1),
	('oss','os_data','Data Inclusão', 0),
	('oss','os_prazo_entrega','Prazo Entrega', 1),
	('oss','os_descricao','Descrição', 0),
	('oss','os_valor','Valor', 0),
	('oss','os_finalizada','Finalizada', 0),
	('oss','os_status','Status', 1),
	('oss','os_fornecedor','Fornecedor', 0),
	('oss','os_pedido_empenho','Pedido de Empenho', 0),
	('oss','os_empenho','Empenho', 0),
	('oss','os_percentagem','Percentagem', 1),
	('oss','os_cor','Cor', 0),
	('oss','os_aprovado','Aprovada', 1),
	('os','moeda','Moeda', 0),
	('os','priorizacao','Prioriação', 1);






DROP TABLE IF EXISTS os;

CREATE TABLE os (
  os_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  os_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  os_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  os_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  os_cadastrador INTEGER(100) UNSIGNED DEFAULT NULL,
  os_requisitante INTEGER(100) UNSIGNED DEFAULT NULL,
  os_gestor INTEGER(100) UNSIGNED DEFAULT NULL,
  os_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  os_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  os_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  os_nome VARCHAR(255) DEFAULT NULL,
  os_numero VARCHAR(255) DEFAULT NULL,
  os_data DATE DEFAULT NULL,
  os_prazo_entrega DATE DEFAULT NULL,
 	os_descricao MEDIUMTEXT,
 	os_valor decimal(20,5) UNSIGNED DEFAULT 0,
 	os_finalizada TINYINT(1) DEFAULT 0,
 	os_dias_alerta INTEGER(10) UNSIGNED DEFAULT NULL,
  os_status INTEGER(10) DEFAULT 0,
  os_fornecedor VARCHAR(255) DEFAULT NULL,
  os_fornecedor_endereco VARCHAR(255) DEFAULT NULL,
  os_fornecedor_fone VARCHAR(11) DEFAULT NULL,
  os_fornecedor_cep VARCHAR(9) DEFAULT NULL,
  os_fornecedor_municipio INTEGER(20) DEFAULT NULL,
  os_fornecedor_estado VARCHAR(2) DEFAULT NULL,
  os_pedido_empenho VARCHAR(255) DEFAULT NULL,
  os_empenho VARCHAR(255) DEFAULT NULL,
  os_percentagem decimal(20,5) UNSIGNED DEFAULT 0,
  os_moeda INTEGER(100) UNSIGNED DEFAULT 1,
  os_cor VARCHAR(6) DEFAULT 'ffffff',
  os_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  os_aprovado TINYINT(1) DEFAULT 0,
  os_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (os_id),
  KEY os_cia (os_cia),
  KEY os_dept (os_dept),
	KEY os_responsavel (os_responsavel),
	KEY os_cadastrador (os_cadastrador),
	KEY os_requisitante (os_requisitante),
	KEY os_gestor (os_gestor),
  KEY os_principal_indicador (os_principal_indicador),
	KEY os_moeda (os_moeda),
  CONSTRAINT os_responsavel FOREIGN KEY (os_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_cadastrador FOREIGN KEY (os_cadastrador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_requisitante FOREIGN KEY (os_requisitante) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_gestor FOREIGN KEY (os_gestor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_cia FOREIGN KEY (os_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT os_dept FOREIGN KEY (os_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_tr FOREIGN KEY (os_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_instrumento FOREIGN KEY (os_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_principal_indicador FOREIGN KEY (os_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_moeda FOREIGN KEY (os_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS os_dept;

CREATE TABLE os_dept (
  os_dept_os INTEGER(100) UNSIGNED NOT NULL,
  os_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (os_dept_os, os_dept_dept),
  KEY os_dept_os (os_dept_os),
  KEY os_dept_dept (os_dept_dept),
  CONSTRAINT os_dept_dept FOREIGN KEY (os_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT os_dept_os FOREIGN KEY (os_dept_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS os_usuario;

CREATE TABLE os_usuario (
  os_usuario_os INTEGER(100) UNSIGNED NOT NULL,
  os_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY os_usuario_os (os_usuario_os),
  KEY os_usuario_usuario (os_usuario_usuario),
  CONSTRAINT os_usuario_os FOREIGN KEY (os_usuario_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT os_usuario_usuario FOREIGN KEY (os_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS os_cia;

CREATE TABLE os_cia (
  os_cia_os INTEGER(100) UNSIGNED NOT NULL,
  os_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (os_cia_os, os_cia_cia),
  KEY os_cia_os (os_cia_os),
  KEY os_cia_cia (os_cia_cia),
  CONSTRAINT os_cia_cia FOREIGN KEY (os_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT os_cia_os FOREIGN KEY (os_cia_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS os_gestao;

CREATE TABLE os_gestao (
	os_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	os_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_semelhante INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_mswot INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_painel INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_me INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_beneficio INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_painel_slideshow INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_trelo INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_trelo_cartao INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_pdcl INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_pdcl_item INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	os_gestao_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY os_gestao_id (os_gestao_id),
	KEY os_gestao_os (os_gestao_os),
	KEY os_gestao_semelhante (os_gestao_semelhante),
	KEY os_gestao_projeto (os_gestao_projeto),
	KEY os_gestao_tarefa (os_gestao_tarefa),
	KEY os_gestao_perspectiva (os_gestao_perspectiva),
	KEY os_gestao_tema (os_gestao_tema),
	KEY os_gestao_objetivo (os_gestao_objetivo),
	KEY os_gestao_estrategia (os_gestao_estrategia),
	KEY os_gestao_meta (os_gestao_meta),
	KEY os_gestao_fator (os_gestao_fator),
	KEY os_gestao_pratica (os_gestao_pratica),
	KEY os_gestao_indicador (os_gestao_indicador),
	KEY os_gestao_acao (os_gestao_acao),
	KEY os_gestao_canvas (os_gestao_canvas),
	KEY os_gestao_risco (os_gestao_risco),
	KEY os_gestao_risco_resposta (os_gestao_risco_resposta),
	KEY os_gestao_calendario (os_gestao_calendario),
	KEY os_gestao_monitoramento (os_gestao_monitoramento),
	KEY os_gestao_ata (os_gestao_ata),
	KEY os_gestao_mswot(os_gestao_mswot),
	KEY os_gestao_swot(os_gestao_swot),
	KEY os_gestao_operativo(os_gestao_operativo),
	KEY os_gestao_instrumento (os_gestao_instrumento),
	KEY os_gestao_recurso (os_gestao_recurso),
	KEY os_gestao_problema (os_gestao_problema),
	KEY os_gestao_demanda (os_gestao_demanda),
	KEY os_gestao_programa (os_gestao_programa),
	KEY os_gestao_licao (os_gestao_licao),
	KEY os_gestao_evento (os_gestao_evento),
	KEY os_gestao_link (os_gestao_link),
	KEY os_gestao_avaliacao (os_gestao_avaliacao),
	KEY os_gestao_tgn (os_gestao_tgn),
	KEY os_gestao_brainstorm (os_gestao_brainstorm),
	KEY os_gestao_gut (os_gestao_gut),
	KEY os_gestao_causa_efeito (os_gestao_causa_efeito),
	KEY os_gestao_arquivo (os_gestao_arquivo),
	KEY os_gestao_forum (os_gestao_forum),
	KEY os_gestao_checklist (os_gestao_checklist),
	KEY os_gestao_agenda (os_gestao_agenda),
	KEY os_gestao_agrupamento (os_gestao_agrupamento),
	KEY os_gestao_patrocinador (os_gestao_patrocinador),
	KEY os_gestao_template (os_gestao_template),
	KEY os_gestao_painel (os_gestao_painel),
	KEY os_gestao_painel_odometro (os_gestao_painel_odometro),
	KEY os_gestao_painel_composicao (os_gestao_painel_composicao),
	KEY os_gestao_tr (os_gestao_tr),
	KEY os_gestao_me (os_gestao_me),
	KEY os_gestao_acao_item (os_gestao_acao_item),
	KEY os_gestao_beneficio (os_gestao_beneficio),
	KEY os_gestao_painel_slideshow (os_gestao_painel_slideshow),
	KEY os_gestao_projeto_viabilidade (os_gestao_projeto_viabilidade),
	KEY os_gestao_projeto_abertura (os_gestao_projeto_abertura),
	KEY os_gestao_plano_gestao (os_gestao_plano_gestao),
	KEY os_gestao_ssti (os_gestao_ssti),
	KEY os_gestao_laudo (os_gestao_laudo),
	KEY os_gestao_trelo (os_gestao_trelo),
	KEY os_gestao_trelo_cartao (os_gestao_trelo_cartao),
	KEY os_gestao_pdcl (os_gestao_pdcl),
	KEY os_gestao_pdcl_item (os_gestao_pdcl_item),
	CONSTRAINT os_gestao_os FOREIGN KEY (os_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_semelhante FOREIGN KEY (os_gestao_semelhante) REFERENCES os (os_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT os_gestao_projeto FOREIGN KEY (os_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_tarefa FOREIGN KEY (os_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_perspectiva FOREIGN KEY (os_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_tema FOREIGN KEY (os_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_objetivo FOREIGN KEY (os_gestao_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_fator FOREIGN KEY (os_gestao_fator) REFERENCES fator (fator_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_estrategia FOREIGN KEY (os_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_meta FOREIGN KEY (os_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_pratica FOREIGN KEY (os_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_indicador FOREIGN KEY (os_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_acao FOREIGN KEY (os_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_canvas FOREIGN KEY (os_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_risco FOREIGN KEY (os_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_risco_resposta FOREIGN KEY (os_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_calendario FOREIGN KEY (os_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_monitoramento FOREIGN KEY (os_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_ata FOREIGN KEY (os_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_mswot FOREIGN KEY (os_gestao_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_swot FOREIGN KEY (os_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_operativo FOREIGN KEY (os_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_instrumento FOREIGN KEY (os_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_recurso FOREIGN KEY (os_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_problema FOREIGN KEY (os_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_demanda FOREIGN KEY (os_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_programa FOREIGN KEY (os_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_licao FOREIGN KEY (os_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_evento FOREIGN KEY (os_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_link FOREIGN KEY (os_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_avaliacao FOREIGN KEY (os_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_tgn FOREIGN KEY (os_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_brainstorm FOREIGN KEY (os_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_gut FOREIGN KEY (os_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_causa_efeito FOREIGN KEY (os_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_arquivo FOREIGN KEY (os_gestao_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_forum FOREIGN KEY (os_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_checklist FOREIGN KEY (os_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_agenda FOREIGN KEY (os_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_agrupamento FOREIGN KEY (os_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_patrocinador FOREIGN KEY (os_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_template FOREIGN KEY (os_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_painel FOREIGN KEY (os_gestao_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_painel_odometro FOREIGN KEY (os_gestao_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_painel_composicao FOREIGN KEY (os_gestao_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_tr FOREIGN KEY (os_gestao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_me FOREIGN KEY (os_gestao_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_acao_item FOREIGN KEY (os_gestao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_beneficio FOREIGN KEY (os_gestao_beneficio) REFERENCES beneficio (beneficio_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_painel_slideshow FOREIGN KEY (os_gestao_painel_slideshow) REFERENCES painel_slideshow (painel_slideshow_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_projeto_viabilidade FOREIGN KEY (os_gestao_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_projeto_abertura FOREIGN KEY (os_gestao_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_plano_gestao FOREIGN KEY (os_gestao_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_ssti FOREIGN KEY (os_gestao_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_laudo FOREIGN KEY (os_gestao_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_trelo FOREIGN KEY (os_gestao_trelo) REFERENCES trelo (trelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_trelo_cartao FOREIGN KEY (os_gestao_trelo_cartao) REFERENCES trelo_cartao (trelo_cartao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_pdcl FOREIGN KEY (os_gestao_pdcl) REFERENCES pdcl (pdcl_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT os_gestao_pdcl_item FOREIGN KEY (os_gestao_pdcl_item) REFERENCES pdcl_item (pdcl_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



ALTER TABLE favorito_trava ADD COLUMN favorito_trava_os TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_os TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_os TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura ADD COLUMN assinatura_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE assinatura ADD KEY assinatura_os (assinatura_os);
ALTER TABLE assinatura ADD CONSTRAINT assinatura_os FOREIGN KEY (assinatura_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_os (projeto_gestao_os);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_os FOREIGN KEY (projeto_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_perspectiva_gestao ADD COLUMN perspectiva_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD COLUMN perspectiva_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectiva_gestao ADD KEY perspectiva_gestao_os (perspectiva_gestao_os);
ALTER TABLE perspectiva_gestao ADD CONSTRAINT perspectiva_gestao_os FOREIGN KEY (perspectiva_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tema_gestao ADD COLUMN tema_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD COLUMN tema_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema_gestao ADD KEY tema_gestao_os (tema_gestao_os);
ALTER TABLE tema_gestao ADD CONSTRAINT tema_gestao_os FOREIGN KEY (tema_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_objetivo_gestao ADD COLUMN objetivo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD COLUMN objetivo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivo_gestao ADD KEY objetivo_gestao_os (objetivo_gestao_os);
ALTER TABLE objetivo_gestao ADD CONSTRAINT objetivo_gestao_os FOREIGN KEY (objetivo_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_fator_gestao ADD COLUMN fator_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD COLUMN fator_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_gestao ADD KEY fator_gestao_os (fator_gestao_os);
ALTER TABLE fator_gestao ADD CONSTRAINT fator_gestao_os FOREIGN KEY (fator_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_estrategia_gestao ADD COLUMN estrategia_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD COLUMN estrategia_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_gestao ADD KEY estrategia_gestao_os (estrategia_gestao_os);
ALTER TABLE estrategia_gestao ADD CONSTRAINT estrategia_gestao_os FOREIGN KEY (estrategia_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_meta_gestao ADD COLUMN meta_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD COLUMN meta_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE meta_gestao ADD KEY meta_gestao_os (meta_gestao_os);
ALTER TABLE meta_gestao ADD CONSTRAINT meta_gestao_os FOREIGN KEY (meta_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_gestao ADD COLUMN pratica_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD COLUMN pratica_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_gestao ADD KEY pratica_gestao_os (pratica_gestao_os);
ALTER TABLE pratica_gestao ADD CONSTRAINT pratica_gestao_os FOREIGN KEY (pratica_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_os (pratica_indicador_gestao_os);
ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_os FOREIGN KEY (pratica_indicador_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_gestao ADD COLUMN plano_acao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_os (plano_acao_gestao_os);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_os FOREIGN KEY (plano_acao_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_canvas_gestao ADD COLUMN canvas_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD COLUMN canvas_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas_gestao ADD KEY canvas_gestao_os (canvas_gestao_os);
ALTER TABLE canvas_gestao ADD CONSTRAINT canvas_gestao_os FOREIGN KEY (canvas_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_gestao ADD COLUMN risco_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD COLUMN risco_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_gestao ADD KEY risco_gestao_os (risco_gestao_os);
ALTER TABLE risco_gestao ADD CONSTRAINT risco_gestao_os FOREIGN KEY (risco_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_risco_resposta_gestao ADD COLUMN risco_resposta_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD COLUMN risco_resposta_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE risco_resposta_gestao ADD KEY risco_resposta_gestao_os (risco_resposta_gestao_os);
ALTER TABLE risco_resposta_gestao ADD CONSTRAINT risco_resposta_gestao_os FOREIGN KEY (risco_resposta_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_calendario_gestao ADD COLUMN calendario_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD COLUMN calendario_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_gestao ADD KEY calendario_gestao_os (calendario_gestao_os);
ALTER TABLE calendario_gestao ADD CONSTRAINT calendario_gestao_os FOREIGN KEY (calendario_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_monitoramento_gestao ADD COLUMN monitoramento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD COLUMN monitoramento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE monitoramento_gestao ADD KEY monitoramento_gestao_os (monitoramento_gestao_os);
ALTER TABLE monitoramento_gestao ADD CONSTRAINT monitoramento_gestao_os FOREIGN KEY (monitoramento_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_ata_gestao ADD COLUMN ata_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD COLUMN ata_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_gestao ADD KEY ata_gestao_os (ata_gestao_os);
ALTER TABLE ata_gestao ADD CONSTRAINT ata_gestao_os FOREIGN KEY (ata_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_mswot_gestao ADD COLUMN mswot_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD COLUMN mswot_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE mswot_gestao ADD KEY mswot_gestao_os (mswot_gestao_os);
ALTER TABLE mswot_gestao ADD CONSTRAINT mswot_gestao_os FOREIGN KEY (mswot_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_swot_gestao ADD COLUMN swot_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD COLUMN swot_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE swot_gestao ADD KEY swot_gestao_os (swot_gestao_os);
ALTER TABLE swot_gestao ADD CONSTRAINT swot_gestao_os FOREIGN KEY (swot_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_operativo_gestao ADD COLUMN operativo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD COLUMN operativo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE operativo_gestao ADD KEY operativo_gestao_os (operativo_gestao_os);
ALTER TABLE operativo_gestao ADD CONSTRAINT operativo_gestao_os FOREIGN KEY (operativo_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_instrumento_gestao ADD COLUMN instrumento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD COLUMN instrumento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento_gestao ADD KEY instrumento_gestao_os (instrumento_gestao_os);
ALTER TABLE instrumento_gestao ADD CONSTRAINT instrumento_gestao_os FOREIGN KEY (instrumento_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE recurso_gestao ADD COLUMN recurso_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recurso_gestao ADD KEY recurso_gestao_os (recurso_gestao_os);
ALTER TABLE recurso_gestao ADD CONSTRAINT recurso_gestao_os FOREIGN KEY (recurso_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_problema_gestao ADD COLUMN problema_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD COLUMN problema_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE problema_gestao ADD KEY problema_gestao_os (problema_gestao_os);
ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_os FOREIGN KEY (problema_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_demanda_gestao ADD COLUMN demanda_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD COLUMN demanda_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demanda_gestao ADD KEY demanda_gestao_os (demanda_gestao_os);
ALTER TABLE demanda_gestao ADD CONSTRAINT demanda_gestao_os FOREIGN KEY (demanda_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE programa_gestao ADD COLUMN programa_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE programa_gestao ADD KEY programa_gestao_os (programa_gestao_os);
ALTER TABLE programa_gestao ADD CONSTRAINT programa_gestao_os FOREIGN KEY (programa_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE licao_gestao ADD COLUMN licao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao_gestao ADD KEY licao_gestao_os (licao_gestao_os);
ALTER TABLE licao_gestao ADD CONSTRAINT licao_gestao_os FOREIGN KEY (licao_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD COLUMN evento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_os (evento_gestao_os);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_os FOREIGN KEY (evento_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_link_gestao ADD COLUMN link_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD COLUMN link_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE link_gestao ADD KEY link_gestao_os (link_gestao_os);
ALTER TABLE link_gestao ADD CONSTRAINT link_gestao_os FOREIGN KEY (link_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_avaliacao_gestao ADD COLUMN avaliacao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD COLUMN avaliacao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao_gestao ADD KEY avaliacao_gestao_os (avaliacao_gestao_os);
ALTER TABLE avaliacao_gestao ADD CONSTRAINT avaliacao_gestao_os FOREIGN KEY (avaliacao_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tgn_gestao ADD COLUMN tgn_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD COLUMN tgn_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tgn_gestao ADD KEY tgn_gestao_os (tgn_gestao_os);
ALTER TABLE tgn_gestao ADD CONSTRAINT tgn_gestao_os FOREIGN KEY (tgn_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_brainstorm_gestao ADD COLUMN brainstorm_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD COLUMN brainstorm_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm_gestao ADD KEY brainstorm_gestao_os (brainstorm_gestao_os);
ALTER TABLE brainstorm_gestao ADD CONSTRAINT brainstorm_gestao_os FOREIGN KEY (brainstorm_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_gut_gestao ADD COLUMN gut_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD COLUMN gut_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut_gestao ADD KEY gut_gestao_os (gut_gestao_os);
ALTER TABLE gut_gestao ADD CONSTRAINT gut_gestao_os FOREIGN KEY (gut_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_causa_efeito_gestao ADD COLUMN causa_efeito_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD COLUMN causa_efeito_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito_gestao ADD KEY causa_efeito_gestao_os (causa_efeito_gestao_os);
ALTER TABLE causa_efeito_gestao ADD CONSTRAINT causa_efeito_gestao_os FOREIGN KEY (causa_efeito_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_arquivo_gestao ADD COLUMN arquivo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD COLUMN arquivo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_gestao ADD KEY arquivo_gestao_os (arquivo_gestao_os);
ALTER TABLE arquivo_gestao ADD CONSTRAINT arquivo_gestao_os FOREIGN KEY (arquivo_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_forum_gestao ADD COLUMN forum_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD COLUMN forum_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE forum_gestao ADD KEY forum_gestao_os (forum_gestao_os);
ALTER TABLE forum_gestao ADD CONSTRAINT forum_gestao_os FOREIGN KEY (forum_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_checklist_gestao ADD COLUMN checklist_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD COLUMN checklist_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist_gestao ADD KEY checklist_gestao_os (checklist_gestao_os);
ALTER TABLE checklist_gestao ADD CONSTRAINT checklist_gestao_os FOREIGN KEY (checklist_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agenda_gestao ADD COLUMN agenda_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD COLUMN agenda_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda_gestao ADD KEY agenda_gestao_os (agenda_gestao_os);
ALTER TABLE agenda_gestao ADD CONSTRAINT agenda_gestao_os FOREIGN KEY (agenda_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_agrupamento_gestao ADD COLUMN agrupamento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD COLUMN agrupamento_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agrupamento_gestao ADD KEY agrupamento_gestao_os (agrupamento_gestao_os);
ALTER TABLE agrupamento_gestao ADD CONSTRAINT agrupamento_gestao_os FOREIGN KEY (agrupamento_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_patrocinador_gestao ADD COLUMN patrocinador_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD COLUMN patrocinador_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE patrocinador_gestao ADD KEY patrocinador_gestao_os (patrocinador_gestao_os);
ALTER TABLE patrocinador_gestao ADD CONSTRAINT patrocinador_gestao_os FOREIGN KEY (patrocinador_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_template_gestao ADD COLUMN template_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD COLUMN template_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template_gestao ADD KEY template_gestao_os (template_gestao_os);
ALTER TABLE template_gestao ADD CONSTRAINT template_gestao_os FOREIGN KEY (template_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_gestao ADD COLUMN painel_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD COLUMN painel_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_gestao ADD KEY painel_gestao_os (painel_gestao_os);
ALTER TABLE painel_gestao ADD CONSTRAINT painel_gestao_os FOREIGN KEY (painel_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_odometro_gestao ADD COLUMN painel_odometro_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD COLUMN painel_odometro_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_odometro_gestao ADD KEY painel_odometro_gestao_os (painel_odometro_gestao_os);
ALTER TABLE painel_odometro_gestao ADD CONSTRAINT painel_odometro_gestao_os FOREIGN KEY (painel_odometro_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_composicao_gestao ADD COLUMN painel_composicao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD COLUMN painel_composicao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_composicao_gestao ADD KEY painel_composicao_gestao_os (painel_composicao_gestao_os);
ALTER TABLE painel_composicao_gestao ADD CONSTRAINT painel_composicao_gestao_os FOREIGN KEY (painel_composicao_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tr_gestao ADD COLUMN tr_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD COLUMN tr_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr_gestao ADD KEY tr_gestao_os (tr_gestao_os);
ALTER TABLE tr_gestao ADD CONSTRAINT tr_gestao_os FOREIGN KEY (tr_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_me_gestao ADD COLUMN me_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD COLUMN me_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE me_gestao ADD KEY me_gestao_os (me_gestao_os);
ALTER TABLE me_gestao ADD CONSTRAINT me_gestao_os FOREIGN KEY (me_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD COLUMN plano_acao_item_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gestao ADD KEY plano_acao_item_gestao_os (plano_acao_item_gestao_os);
ALTER TABLE plano_acao_item_gestao ADD CONSTRAINT plano_acao_item_gestao_os FOREIGN KEY (plano_acao_item_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_beneficio_gestao ADD COLUMN beneficio_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD COLUMN beneficio_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE beneficio_gestao ADD KEY beneficio_gestao_os (beneficio_gestao_os);
ALTER TABLE beneficio_gestao ADD CONSTRAINT beneficio_gestao_os FOREIGN KEY (beneficio_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD COLUMN painel_slideshow_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE painel_slideshow_gestao ADD KEY painel_slideshow_gestao_os (painel_slideshow_gestao_os);
ALTER TABLE painel_slideshow_gestao ADD CONSTRAINT painel_slideshow_gestao_os FOREIGN KEY (painel_slideshow_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD COLUMN projeto_viabilidade_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade_gestao ADD KEY projeto_viabilidade_gestao_os (projeto_viabilidade_gestao_os);
ALTER TABLE projeto_viabilidade_gestao ADD CONSTRAINT projeto_viabilidade_gestao_os FOREIGN KEY (projeto_viabilidade_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD COLUMN projeto_abertura_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura_gestao ADD KEY projeto_abertura_gestao_os (projeto_abertura_gestao_os);
ALTER TABLE projeto_abertura_gestao ADD CONSTRAINT projeto_abertura_gestao_os FOREIGN KEY (projeto_abertura_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_plano_gestao_gestao ADD COLUMN plano_gestao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD COLUMN plano_gestao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_gestao ADD KEY plano_gestao_gestao_os (plano_gestao_gestao_os);
ALTER TABLE plano_gestao_gestao ADD CONSTRAINT plano_gestao_gestao_os FOREIGN KEY (plano_gestao_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta_gestao ADD COLUMN arquivo_pasta_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta_gestao ADD KEY arquivo_pasta_gestao_os (arquivo_pasta_gestao_os);
ALTER TABLE arquivo_pasta_gestao ADD CONSTRAINT arquivo_pasta_gestao_os FOREIGN KEY (arquivo_pasta_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_modelo_gestao ADD COLUMN modelo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD COLUMN modelo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_gestao ADD KEY modelo_gestao_os (modelo_gestao_os);
ALTER TABLE modelo_gestao ADD CONSTRAINT modelo_gestao_os FOREIGN KEY (modelo_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ssti_gestao ADD COLUMN ssti_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti_gestao ADD KEY ssti_gestao_os (ssti_gestao_os);
ALTER TABLE ssti_gestao ADD CONSTRAINT ssti_gestao_os FOREIGN KEY (ssti_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE laudo_gestao ADD COLUMN laudo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE laudo_gestao ADD KEY laudo_gestao_os (laudo_gestao_os);
ALTER TABLE laudo_gestao ADD CONSTRAINT laudo_gestao_os FOREIGN KEY (laudo_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE trelo_gestao ADD COLUMN trelo_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE trelo_gestao ADD KEY trelo_gestao_os (trelo_gestao_os);
ALTER TABLE trelo_gestao ADD CONSTRAINT trelo_gestao_os FOREIGN KEY (trelo_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE trelo_cartao_gestao ADD COLUMN trelo_cartao_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE trelo_cartao_gestao ADD KEY trelo_cartao_gestao_os (trelo_cartao_gestao_os);
ALTER TABLE trelo_cartao_gestao ADD CONSTRAINT trelo_cartao_gestao_os FOREIGN KEY (trelo_cartao_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pdcl_gestao ADD COLUMN pdcl_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pdcl_gestao ADD KEY pdcl_gestao_os (pdcl_gestao_os);
ALTER TABLE pdcl_gestao ADD CONSTRAINT pdcl_gestao_os FOREIGN KEY (pdcl_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pdcl_item_gestao ADD COLUMN pdcl_item_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pdcl_item_gestao ADD KEY pdcl_item_gestao_os (pdcl_item_gestao_os);
ALTER TABLE pdcl_item_gestao ADD CONSTRAINT pdcl_item_gestao_os FOREIGN KEY (pdcl_item_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_msg_gestao ADD COLUMN msg_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD COLUMN msg_gestao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg_gestao ADD KEY msg_gestao_os (msg_gestao_os);
ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_os FOREIGN KEY (msg_gestao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_log ADD COLUMN log_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD COLUMN log_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_os (log_os);
ALTER TABLE log ADD CONSTRAINT log_os FOREIGN KEY (log_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE pi ADD COLUMN pi_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pi ADD KEY pi_os (pi_os);
ALTER TABLE pi ADD CONSTRAINT pi_os FOREIGN KEY (pi_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ptres ADD COLUMN ptres_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ptres ADD KEY ptres_os (ptres_os);
ALTER TABLE ptres ADD CONSTRAINT ptres_os FOREIGN KEY (ptres_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE priorizacao_modelo ADD COLUMN priorizacao_modelo_os TINYINT(1) DEFAULT 0;

ALTER TABLE priorizacao ADD COLUMN priorizacao_os INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE priorizacao ADD KEY priorizacao_os (priorizacao_os);
ALTER TABLE priorizacao ADD CONSTRAINT priorizacao_os FOREIGN KEY (priorizacao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;


UPDATE priorizacao_modelo SET priorizacao_modelo_os=1 WHERE priorizacao_modelo_id<=6;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('StatusOSCor','c5eff4','0',NULL),
	('StatusOSCor','e4ee8b','1',NULL),
	('StatusOSCor','a0cc9d','2',NULL),
	('StatusOSCor','da908c','3',NULL),
	('StatusOSCor','e3c383','4',NULL),
	('StatusOSCor','f297f1','5',NULL),
	('StatusOS','Não Iniciado','0',NULL),
	('StatusOS','Pendente','1',NULL),
	('StatusOS','Concluída','2',NULL),
	('StatusOS','Não Realizado','3',NULL),
	('StatusOS','Em Andamento','4',NULL),
	('StatusOS','Em Atraso','5',NULL);