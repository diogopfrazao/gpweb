<?php
global $config, $bd;



if(!file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	
	
	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_risco'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_risco INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_risco (log_risco);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_risco FOREIGN KEY (log_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_risco_resposta'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_risco_resposta (log_risco_resposta);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_risco_resposta FOREIGN KEY (log_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_calendario'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_calendario INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_calendario (log_calendario);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_calendario FOREIGN KEY (log_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_monitoramento'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_monitoramento (log_monitoramento);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_monitoramento FOREIGN KEY (log_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_ata'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_ata INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_ata (log_ata);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_ata FOREIGN KEY (log_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_mswot'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_mswot INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_mswot(log_mswot);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_mswot FOREIGN KEY (log_mswot) REFERENCES mswot (mswot_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
			
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_swot'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_swot INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_swot (log_swot);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_swot FOREIGN KEY (log_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_operativo'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_operativo INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_operativo (log_operativo);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_operativo FOREIGN KEY (log_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_recurso'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_recurso INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_recurso (log_recurso);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_recurso FOREIGN KEY (log_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_problema'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_problema INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_problema (log_problema);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_problema FOREIGN KEY (log_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_demanda'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_demanda INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_demanda (log_demanda);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_demanda FOREIGN KEY (log_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_programa'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_programa INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_programa (log_programa);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_programa FOREIGN KEY (log_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_licao'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_licao INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_licao (log_licao);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_licao FOREIGN KEY (log_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_evento'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_evento INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_evento (log_evento);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_evento FOREIGN KEY (log_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_link'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_link INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_link (log_link);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_link FOREIGN KEY (log_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_avaliacao'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_avaliacao (log_avaliacao);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_avaliacao FOREIGN KEY (log_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_tgn'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_tgn INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_tgn (log_tgn);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_tgn FOREIGN KEY (log_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_brainstorm'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_brainstorm (log_brainstorm);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_brainstorm FOREIGN KEY (log_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_gut'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_gut INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_gut (log_gut);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_gut FOREIGN KEY (log_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_causa_efeito'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_causa_efeito (log_causa_efeito);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_causa_efeito FOREIGN KEY (log_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_arquivo'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_arquivo INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_arquivo (log_arquivo);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_arquivo FOREIGN KEY (log_arquivo) REFERENCES arquivo (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_forum'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_forum INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_forum (log_forum);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_forum FOREIGN KEY (log_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_checklist'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_checklist INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_checklist (log_checklist);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_checklist FOREIGN KEY (log_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_agenda'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_agenda INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_agenda (log_agenda);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_agenda FOREIGN KEY (log_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_agrupamento'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_agrupamento (log_agrupamento);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_agrupamento FOREIGN KEY (log_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_patrocinador'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_patrocinador (log_patrocinador);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_patrocinador FOREIGN KEY (log_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_template'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_template INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_template (log_template);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_template FOREIGN KEY (log_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_painel'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_painel INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_painel (log_painel);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_painel FOREIGN KEY (log_painel) REFERENCES painel (painel_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_painel_odometro'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_painel_odometro INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_painel_odometro (log_painel_odometro);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_painel_odometro FOREIGN KEY (log_painel_odometro) REFERENCES painel_odometro (painel_odometro_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
		
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_painel_composicao'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_painel_composicao INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_painel_composicao (log_painel_composicao);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_painel_composicao FOREIGN KEY (log_painel_composicao) REFERENCES painel_composicao (painel_composicao_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_tr'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_tr INTEGER(100) UNSIGNED DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD KEY log_tr (log_tr);");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_tr FOREIGN KEY (log_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE;");	
		}
	
	
	
	
	/*
















	*/
	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_tipo_problema'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_tipo_problema INTEGER(10) DEFAULT 0;");	

	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'decimal'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_custo decimal(20,5) unsigned DEFAULT 0;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_nd'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_nd varchar(11) DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_categoria_economica'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_categoria_economica varchar(1) DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_grupo_despesa'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_grupo_despesa varchar(1) DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_modalidade_aplicacao'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_modalidade_aplicacao varchar(2) DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_reg_mudanca_inicio'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_reg_mudanca_inicio datetime DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_reg_mudanca_fim'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_reg_mudanca_fim datetime DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_reg_mudanca_duracao'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_reg_mudanca_duracao decimal(20,3) unsigned DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_reg_mudanca_percentagem'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_reg_mudanca_percentagem decimal(20,5) unsigned DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_reg_mudanca_realizado'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_reg_mudanca_realizado decimal(20,5) unsigned DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_reg_mudanca_status'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_reg_mudanca_status int(100) unsigned DEFAULT 0;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_aprovou'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) {
		$bd->Execute("ALTER TABLE log ADD COLUMN log_aprovou int(100) unsigned DEFAULT NULL;");	
		$bd->Execute("ALTER TABLE log ADD CONSTRAINT log_aprovou FOREIGN KEY (log_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;");
		}
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_aprovado'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_aprovado tinyint(1) DEFAULT NULL;");	
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM log LIKE 'log_data_aprovado'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if(!$existe) $bd->Execute("ALTER TABLE log ADD COLUMN log_data_aprovado datetime DEFAULT NULL;");	
	
	
	
	$bd->Execute("CREATE TABLE agrupamento (
  agrupamento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  agrupamento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  agrupamento_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  agrupamento_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  agrupamento_nome VARCHAR(255) DEFAULT NULL,
	agrupamento_descricao TEXT,
	agrupamento_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  agrupamento_cor VARCHAR(6) DEFAULT 'FFFFFF',
  agrupamento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  agrupamento_ativo TINYINT(1) DEFAULT 1,
  agrupamento_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (agrupamento_id),
  KEY agrupamento_cia (agrupamento_cia),
  KEY agrupamento_dept (agrupamento_dept),
  KEY agrupamento_usuario (agrupamento_usuario),
  KEY agrupamento_moeda (agrupamento_moeda),
	CONSTRAINT agrupamento_moeda FOREIGN KEY (agrupamento_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE, 
  CONSTRAINT agrupamento_cia FOREIGN KEY (agrupamento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agrupamento_dept FOREIGN KEY (agrupamento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agrupamento_usuario FOREIGN KEY (agrupamento_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB;");
	
	$bd->Execute("CREATE TABLE swot (
  swot_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  swot_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  swot_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  swot_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  swot_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  swot_nome VARCHAR(255),
  swot_prazo DATE DEFAULT NULL,
  swot_inicio DATE DEFAULT NULL,
  swot_fim DATE DEFAULT NULL,
  swot_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
  swot_oque TEXT,
  swot_descricao TEXT,
  swot_onde TEXT,
  swot_quando TEXT,
  swot_como TEXT,
  swot_porque TEXT,
  swot_quanto TEXT,
  swot_quem TEXT,
  swot_controle TEXT,
  swot_melhorias TEXT,
  swot_metodo_aprendizado TEXT,
  swot_desde_quando TEXT,
  swot_g INTEGER(10) UNSIGNED DEFAULT 1,
  swot_u INTEGER(10) UNSIGNED DEFAULT 1,
  swot_t INTEGER(10) UNSIGNED DEFAULT 1,
  swot_pontuacao INTEGER(10) UNSIGNED DEFAULT 1,
  swot_tipo VARCHAR(1) DEFAULT NULL,
  swot_cor VARCHAR(6) DEFAULT 'FFFFFF',
  swot_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  swot_ativo TINYINT(1) DEFAULT 1,
  swot_aprovado TINYINT(1) DEFAULT 0,
  swot_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  PRIMARY KEY (swot_id),
  KEY swot_cia (swot_cia),
  KEY swot_dept (swot_dept),
  KEY swot_responsavel (swot_responsavel),
 	KEY swot_principal_indicador (swot_principal_indicador),
 	KEY swot_moeda (swot_moeda),
  CONSTRAINT swot_cia FOREIGN KEY (swot_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT swot_dept FOREIGN KEY (swot_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT swot_responsavel FOREIGN KEY (swot_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT swot_principal_indicador FOREIGN KEY (swot_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT swot_moeda FOREIGN KEY (swot_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE operativo (
  operativo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  operativo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_previsao DATE DEFAULT NULL,
  operativo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_atualizacao DATETIME DEFAULT NULL,
  operativo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_projeto_principal INTEGER(100) UNSIGNED DEFAULT NULL,
  operativo_nome VARCHAR(255) DEFAULT NULL,
  operativo_descricao TEXT,
  operativo_convenio VARCHAR(200) DEFAULT NULL,
  operativo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  operativo_cor VARCHAR(6) DEFAULT 'FFFFFF',
  operativo_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  operativo_ativo TINYINT(1) DEFAULT 1,
  operativo_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (operativo_id),
  KEY operativo_cia (operativo_cia),
  KEY operativo_dept (operativo_dept),
  KEY operativo_usuario (operativo_usuario),
  KEY operativo_perspectiva (operativo_perspectiva),
  KEY operativo_tema (operativo_tema),
  KEY operativo_objetivo (operativo_objetivo),
  KEY operativo_projeto (operativo_projeto),
  KEY operativo_projeto_principal (operativo_projeto_principal),
  KEY operativo_moeda (operativo_moeda),
  CONSTRAINT operativo_cia FOREIGN KEY (operativo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT operativo_dept FOREIGN KEY (operativo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT operativo_usuario FOREIGN KEY (operativo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT operativo_perspectiva FOREIGN KEY (operativo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT operativo_tema FOREIGN KEY (operativo_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT operativo_objetivo FOREIGN KEY (operativo_objetivo) REFERENCES objetivo (objetivo_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT operativo_projeto FOREIGN KEY (operativo_projeto) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT operativo_projeto_principal FOREIGN KEY (operativo_projeto_principal) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT operativo_moeda FOREIGN KEY (operativo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB;");
	
	$bd->Execute("CREATE TABLE problema (
  problema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  problema_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  problema_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  problema_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  problema_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  problema_nome VARCHAR(255) DEFAULT NULL,
  problema_descricao MEDIUMTEXT,
  problema_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
  problema_inicio DATE DEFAULT NULL,
  problema_fim DATE DEFAULT NULL,
  problema_solucao MEDIUMTEXT,
  problema_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  problema_cor VARCHAR(6) DEFAULT 'ffffff',
  problema_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  problema_ativo TINYINT(1) DEFAULT 1,
  problema_aprovado TINYINT(1) DEFAULT 0,
  problema_status INTEGER(10) DEFAULT 0,
  PRIMARY KEY (problema_id),
  KEY problema_cia (problema_cia),
  KEY problema_dept (problema_dept),
  KEY problema_responsavel (problema_responsavel),
  KEY problema_principal_indicador (problema_principal_indicador),
  KEY problema_moeda (problema_moeda),
	CONSTRAINT problema_cia FOREIGN KEY (problema_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT problema_dept FOREIGN KEY (problema_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT problema_responsavel FOREIGN KEY (problema_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT problema_principal_indicador FOREIGN KEY (problema_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT problema_moeda FOREIGN KEY (problema_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE 
)ENGINE=InnoDB;");
	
	$bd->Execute("CREATE TABLE programa (
  programa_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  programa_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  programa_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  programa_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  programa_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  programa_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  programa_nome VARCHAR(250) DEFAULT NULL,
  programa_data DATETIME DEFAULT NULL,
  programa_inicio DATETIME DEFAULT NULL,
	programa_fim DATETIME DEFAULT NULL,
	programa_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0,
  programa_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  programa_descricao TEXT,
  programa_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
  programa_meta DECIMAL(20,5) UNSIGNED DEFAULT 0,
  programa_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  programa_cor VARCHAR(6) DEFAULT 'FFFFFF',
	programa_ativo TINYINT(1) DEFAULT 1,
	programa_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (programa_id),
  KEY programa_cia (programa_cia),
  KEY programa_dept (programa_dept),
  KEY programa_superior (programa_superior),
  KEY programa_usuario (programa_usuario),
  KEY programa_indicador (programa_indicador),
  KEY programa_moeda (programa_moeda),
  CONSTRAINT programa_cia FOREIGN KEY (programa_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT programa_superior FOREIGN KEY (programa_superior) REFERENCES programa (programa_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT programa_usuario FOREIGN KEY (programa_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT programa_indicador FOREIGN KEY (programa_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT programa_dept FOREIGN KEY (programa_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT programa_moeda FOREIGN KEY (programa_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE tgn (
  tgn_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tgn_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tgn_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  tgn_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  tgn_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  tgn_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  tgn_nome VARCHAR(250) DEFAULT NULL,
  tgn_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  tgn_cor VARCHAR(6) DEFAULT 'FFFFFF',
  tgn_descricao TEXT,
  tgn_ativo TINYINT(1) DEFAULT 1,
  tgn_categoria VARCHAR(50) DEFAULT NULL,
  tgn_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  tgn_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (tgn_id),
  KEY tgn_cia (tgn_cia),
  KEY tgn_dept (tgn_dept),
  KEY tgn_superior (tgn_superior),
  KEY tgn_usuario (tgn_usuario),
  KEY tgn_principal_indicador (tgn_principal_indicador),
  KEY tgn_moeda (tgn_moeda),
  CONSTRAINT tgn_cia FOREIGN KEY (tgn_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tgn_superior FOREIGN KEY (tgn_superior) REFERENCES tgn (tgn_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tgn_usuario FOREIGN KEY (tgn_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tgn_dept FOREIGN KEY (tgn_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tgn_principal_indicador FOREIGN KEY (tgn_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tgn_moeda FOREIGN KEY (tgn_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE risco (
  risco_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  risco_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_nome VARCHAR(250) DEFAULT NULL,
  risco_probabilidade1 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_impacto1 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_gravidade1 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_urgencia1 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_tendencia1 INTEGER(10) UNSIGNED DEFAULT NULL,
 	risco_probabilidade2 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_impacto2 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_gravidade2 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_urgencia2 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_tendencia2 INTEGER(10) UNSIGNED DEFAULT NULL,
  risco_iniciativa VARCHAR(20) DEFAULT NULL,
  risco_gatilho TEXT,
  risco_acao_proposta TEXT,
  risco_data DATETIME DEFAULT NULL,
  risco_inicio DATETIME DEFAULT NULL,
	risco_fim DATETIME DEFAULT NULL,
	risco_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0,
  risco_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  risco_cor VARCHAR(6) DEFAULT 'FFFFFF',
  risco_descricao TEXT,
  risco_categoria VARCHAR(50) DEFAULT NULL,
  risco_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
  risco_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
  risco_ponto_alvo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  risco_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  risco_ativo TINYINT(1) DEFAULT 1,
  risco_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (risco_id),
  KEY risco_cia (risco_cia),
  KEY risco_dept (risco_dept),
  KEY risco_superior (risco_superior),
  KEY risco_usuario (risco_usuario),
  KEY risco_indicador (risco_indicador),
  KEY risco_moeda (risco_moeda),
  CONSTRAINT risco_fk FOREIGN KEY (risco_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT risco_fk1 FOREIGN KEY (risco_superior) REFERENCES risco (risco_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT risco_fk2 FOREIGN KEY (risco_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT risco_fk3 FOREIGN KEY (risco_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT risco_fk4 FOREIGN KEY (risco_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT risco_moeda FOREIGN KEY (risco_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE 
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE risco_resposta (
  risco_resposta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  risco_resposta_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_resposta_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_resposta_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_resposta_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_resposta_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  risco_resposta_nome VARCHAR(250) DEFAULT NULL,
  risco_resposta_acao_proposta TEXT,
  risco_resposta_data DATETIME DEFAULT NULL,
  risco_resposta_inicio DATETIME DEFAULT NULL,
	risco_resposta_fim DATETIME DEFAULT NULL,
	risco_resposta_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0,
  risco_resposta_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  risco_resposta_cor VARCHAR(6) DEFAULT 'FFFFFF',
  risco_resposta_descricao TEXT,
  risco_resposta_categoria VARCHAR(50) DEFAULT NULL,
  risco_resposta_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
  risco_resposta_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	risco_resposta_ponto_alvo DECIMAL(20,5) UNSIGNED DEFAULT 0,
	risco_resposta_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	risco_resposta_ativo TINYINT(1) DEFAULT 1,
	risco_resposta_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (risco_resposta_id),
  KEY risco_resposta_cia (risco_resposta_cia),
  KEY risco_resposta_dept (risco_resposta_dept),
  KEY risco_resposta_superior (risco_resposta_superior),
  KEY risco_resposta_usuario (risco_resposta_usuario),
  KEY risco_resposta_indicador (risco_resposta_indicador),
  KEY risco_resposta_moeda (risco_resposta_moeda),
  CONSTRAINT risco_resposta_cia FOREIGN KEY (risco_resposta_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT risco_resposta_superior FOREIGN KEY (risco_resposta_superior) REFERENCES risco_resposta (risco_resposta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT risco_resposta_usuario FOREIGN KEY (risco_resposta_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT risco_resposta_indicador FOREIGN KEY (risco_resposta_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT risco_resposta_dept FOREIGN KEY (risco_resposta_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT risco_resposta_moeda FOREIGN KEY (risco_resposta_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE 
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE monitoramento (
  monitoramento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  monitoramento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  monitoramento_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  monitoramento_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  monitoramento_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  monitoramento_nome VARCHAR(250) DEFAULT NULL,
  monitoramento_data DATETIME DEFAULT NULL,
  monitoramento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  monitoramento_cor VARCHAR(6) DEFAULT 'FFFFFF',
  monitoramento_oque TEXT,
  monitoramento_descricao TEXT,
  monitoramento_onde TEXT,
  monitoramento_quando TEXT,
  monitoramento_como TEXT,
  monitoramento_porque TEXT,
  monitoramento_quanto TEXT,
  monitoramento_quem TEXT,
  monitoramento_controle TEXT,
  monitoramento_melhorias TEXT,
  monitoramento_metodo_aprendizado TEXT,
  monitoramento_desde_quando TEXT,
  monitoramento_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
  monitoramento_ativo TINYINT(1) DEFAULT 1,
  monitoramento_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (monitoramento_id),
  KEY monitoramento_cia (monitoramento_cia),
  KEY monitoramento_usuario (monitoramento_usuario),
  KEY monitoramento_principal_indicador (monitoramento_principal_indicador),
  KEY monitoramento_moeda (monitoramento_moeda),
  CONSTRAINT monitoramento_cia FOREIGN KEY (monitoramento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT monitoramento_dept FOREIGN KEY (monitoramento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT monitoramento_usuario FOREIGN KEY (monitoramento_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT monitoramento_principal_indicador FOREIGN KEY (monitoramento_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT monitoramento_moeda FOREIGN KEY (monitoramento_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE painel_odometro (
  painel_odometro_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  painel_odometro_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_odometro_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_odometro_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	painel_odometro_nome VARCHAR(100) DEFAULT NULL,
	painel_odometro_descricao TEXT,
  painel_odometro_titulo VARCHAR(100) DEFAULT NULL,
  painel_odometro_subtitulo VARCHAR(100) DEFAULT NULL,
  painel_odometro_url VARCHAR(255) DEFAULT NULL,
  painel_odometro_link_tipo VARCHAR(30) DEFAULT NULL,
	painel_odometro_link_chave INTEGER(100) UNSIGNED DEFAULT NULL,
	painel_odometro_link_chave2 INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_odometro_texto_painel VARCHAR(18) DEFAULT NULL,
  painel_odometro_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_odometro_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  painel_odometro_cor VARCHAR(6)  DEFAULT 'FFFFFF',
  painel_odometro_tipo VARCHAR(20) DEFAULT 'odometro',
 	painel_odometro_angulo_inicial INTEGER(10) DEFAULT -150,
 	painel_odometro_angulo_final INTEGER(10) DEFAULT 150,
 	painel_odometro_pontuacao TINYINT(1) DEFAULT 0,
 	painel_odometro_ponto0 DECIMAL(20,5) DEFAULT 0,
 	painel_odometro_ponto1 DECIMAL(20,5) DEFAULT 50,
 	painel_odometro_ponto2 DECIMAL(20,5) DEFAULT 80,
 	painel_odometro_ponto3 DECIMAL(20,5) DEFAULT 100,
 	painel_odometro_gradiente0 DECIMAL(20,5) DEFAULT 0.1,
 	painel_odometro_gradiente1 DECIMAL(20,5) DEFAULT 0.5,
 	painel_odometro_gradiente2 DECIMAL(20,5) DEFAULT 0.9,
 	painel_odometro_data_final DATE DEFAULT NULL,
 	painel_odometro_final_hoje TINYINT(1) DEFAULT 0,
	painel_odometro_largura INTEGER(10) UNSIGNED DEFAULT 400,
	painel_odometro_altura INTEGER(10) UNSIGNED DEFAULT 400,
	painel_odometro_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	painel_odometro_ativo TINYINT(1) DEFAULT 1,
	painel_odometro_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (painel_odometro_id),
  KEY painel_odometro_cia (painel_odometro_cia),
 	KEY painel_odometro_dept (painel_odometro_dept),
	KEY painel_odometro_moeda (painel_odometro_moeda),
  CONSTRAINT painel_odometro_cia FOREIGN KEY (painel_odometro_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT painel_odometro_indicador FOREIGN KEY (painel_odometro_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
 	CONSTRAINT painel_odometro_dept FOREIGN KEY (painel_odometro_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT painel_odometro_moeda FOREIGN KEY (painel_odometro_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE painel (
  painel_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  painel_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_nome VARCHAR(255) DEFAULT NULL,
  painel_titulo VARCHAR(100) DEFAULT NULL,
  painel_subtitulo VARCHAR(100) DEFAULT NULL,
  painel_link_tipo VARCHAR(30) DEFAULT NULL,
	painel_link_chave INTEGER(100) UNSIGNED DEFAULT NULL,
	painel_link_chave2 INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_url VARCHAR(255) DEFAULT NULL,
  painel_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_descricao TEXT,
  painel_cor VARCHAR(6)  DEFAULT 'FFFFFF',
  painel_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  painel_suavizar TINYINT(1) DEFAULT 0,
  painel_multiplo_y TINYINT(1) DEFAULT 0,
  painel_legenda_y VARCHAR(50) DEFAULT NULL,
  painel_max_zoom INTEGER(100) UNSIGNED DEFAULT 0,
  painel_nr_pontos INTEGER(100) UNSIGNED DEFAULT 10,
	painel_agrupar VARCHAR(6)  DEFAULT 'ano',
	painel_data_final DATE DEFAULT NULL,
	painel_final_hoje TINYINT(1) DEFAULT 0,
	painel_angulo_legenda_x INTEGER(10) DEFAULT 0,
	painel_largura INTEGER(10) UNSIGNED DEFAULT 400,
	painel_altura INTEGER(10) UNSIGNED DEFAULT 400,
	painel_legenda TINYINT(1) DEFAULT 1,
	painel_legenday TINYINT(1) DEFAULT 0,
	painel_valor_ponto TINYINT(1) DEFAULT 0,
	painel_valor_sobreposicao TINYINT(1) DEFAULT 0,
	painel_valor_rotacao SMALLINT DEFAULT 0,
	painel_valor_alinhamento VARCHAR(6) DEFAULT 'center',
	painel_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	painel_ativo TINYINT(1) DEFAULT 1,
	painel_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (painel_id),
  KEY painel_cia (painel_cia),
  KEY painel_dept (painel_dept),
  KEY painel_moeda (painel_moeda),
  CONSTRAINT painel_cia FOREIGN KEY (painel_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT painel_dept FOREIGN KEY (painel_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT painel_moeda FOREIGN KEY (painel_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE painel_slideshow (
  painel_slideshow_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  painel_slideshow_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_slideshow_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_slideshow_nome VARCHAR(100) DEFAULT NULL,
  painel_slideshow_descricao TEXT,
  painel_slideshow_tempo INTEGER(10) UNSIGNED DEFAULT 15,
  painel_slideshow_refresh INTEGER(10) UNSIGNED DEFAULT 0,
  painel_slideshow_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_slideshow_cor VARCHAR(6)  DEFAULT 'FFFFFF',
  painel_slideshow_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  painel_slideshow_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (painel_slideshow_id),
  KEY painel_slideshow_cia (painel_slideshow_cia),
  KEY painel_slideshow_dept (painel_slideshow_dept),
  CONSTRAINT painel_slideshow_cia FOREIGN KEY (painel_slideshow_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT painel_slideshow_dept FOREIGN KEY (painel_slideshow_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	$bd->Execute("CREATE TABLE painel_composicao (
  painel_composicao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  painel_composicao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_composicao_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_composicao_nome VARCHAR(100) DEFAULT NULL,
  painel_composicao_titulo VARCHAR(100) DEFAULT NULL,
  painel_composicao_subtitulo VARCHAR(100) DEFAULT NULL,
  painel_composicao_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_composicao_url VARCHAR(255) DEFAULT NULL,
  painel_composicao_link_tipo VARCHAR(30) DEFAULT NULL,
	painel_composicao_link_chave INTEGER(100) UNSIGNED DEFAULT NULL,
	painel_composicao_link_chave2 INTEGER(100) UNSIGNED DEFAULT NULL,
  painel_composicao_descricao TEXT,
  painel_composicao_cor VARCHAR(6)  DEFAULT 'FFFFFF',
  painel_composicao_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  painel_composicao_colunas INTEGER(100) UNSIGNED DEFAULT 3,
 	painel_composicao_largura INTEGER(10) UNSIGNED DEFAULT NULL,
	painel_composicao_altura INTEGER(10) UNSIGNED DEFAULT NULL,
	painel_composicao_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	painel_composicao_ativo TINYINT(1) DEFAULT 1,
	painel_composicao_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (painel_composicao_id),
  KEY painel_composicao_cia (painel_composicao_cia),
  KEY painel_composicao_dept (painel_composicao_dept),
  KEY painel_composicao_moeda (painel_composicao_moeda),
  CONSTRAINT painel_composicao_cia FOREIGN KEY (painel_composicao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT painel_composicao_dept FOREIGN KEY (painel_composicao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT painel_composicao_moeda FOREIGN KEY (painel_composicao_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	
	
	}
	

	
$sql = new BDConsulta;

	

$sql->adTabela('tarefa_log');	
$sql->adCampo('tarefa_log.*');
$lista = $sql->lista();
$sql->limpar();
$novo_id=array();
foreach($lista AS $linha) {
	$sql->adTabela('log');
	$sql->adInserir('log_tarefa', $linha['tarefa_log_tarefa']);
	$sql->adInserir('log_criador', $linha['tarefa_log_criador']);
	//$sql->adInserir('log_correcao', $linha['tarefa_log_correcao']);
	$sql->adInserir('log_nome', $linha['tarefa_log_nome']);
	$sql->adInserir('log_descricao', $linha['tarefa_log_descricao']);
	$sql->adInserir('log_horas', $linha['tarefa_log_horas']);
	$sql->adInserir('log_data', $linha['tarefa_log_data']);
	$sql->adInserir('log_custo', $linha['tarefa_log_custo']);
	$sql->adInserir('log_nd', $linha['tarefa_log_nd']);
	$sql->adInserir('log_categoria_economica', $linha['tarefa_log_categoria_economica']);
	$sql->adInserir('log_grupo_despesa', $linha['tarefa_log_grupo_despesa']);
	$sql->adInserir('log_modalidade_aplicacao', $linha['tarefa_log_modalidade_aplicacao']);
	$sql->adInserir('log_corrigir', $linha['tarefa_log_problema']);
	$sql->adInserir('log_tipo_problema', $linha['tarefa_log_tipo_problema']);
	$sql->adInserir('log_referencia', $linha['tarefa_log_referencia']);
	$sql->adInserir('log_url_relacionada', $linha['tarefa_log_url_relacionada']);
	$sql->adInserir('log_reg_mudanca_inicio', $linha['tarefa_log_reg_mudanca_inicio']);
	$sql->adInserir('log_reg_mudanca_fim', $linha['tarefa_log_reg_mudanca_fim']);
	$sql->adInserir('log_reg_mudanca_duracao', $linha['tarefa_log_reg_mudanca_duracao']);
	$sql->adInserir('log_reg_mudanca_percentagem', $linha['tarefa_log_reg_mudanca_percentagem']);
	$sql->adInserir('log_reg_mudanca_realizado', $linha['tarefa_log_reg_mudanca_realizado']);
	$sql->adInserir('log_reg_mudanca_status', $linha['tarefa_log_reg_mudanca_status']);
	$sql->adInserir('log_acesso', $linha['tarefa_log_acesso']);
	$sql->adInserir('log_aprovou', $linha['tarefa_log_aprovou']);
	$sql->adInserir('log_aprovado', $linha['tarefa_log_aprovado']);
	$sql->adInserir('log_data_aprovado', $linha['tarefa_log_data_aprovado']);
	$sql->exec();
	$log_id=$bd->Insert_ID('log','log_id');
	$sql->limpar();
	$novo_id[$linha['tarefa_log_id']]=$log_id;
	}	
foreach($lista AS $linha) {
	if ($linha['tarefa_log_correcao'] && isset($novo_id[$linha['tarefa_log_correcao']]) && isset($novo_id[$linha['tarefa_log_id']])){
		$sql->adTabela('log');
		$sql->adAtualizar('log_correcao', $novo_id[$linha['tarefa_log_correcao']]);
		$sql->adOnde('log_id='.(int)$novo_id[$linha['tarefa_log_id']]);
		$sql->exec();
		$sql->limpar();
		}
	}	






$sql->adTabela('tarefa_log_arquivo');	
$sql->adCampo('tarefa_log_arquivo.*');
$lista = $sql->lista();
$sql->limpar();	

foreach($lista AS $linha) {
	if (isset($novo_id[$linha['tarefa_log_arquivo_tarefa_log_id']])){
		$sql->adTabela('log_arquivo');
		$sql->adInserir('log_arquivo_log', $novo_id[$linha['tarefa_log_arquivo_tarefa_log_id']]);
		$sql->adInserir('log_arquivo_usuario', $linha['tarefa_log_arquivo_usuario']);
		$sql->adInserir('log_arquivo_ordem', $linha['tarefa_log_arquivo_ordem']);
		$sql->adInserir('log_arquivo_endereco', $linha['tarefa_log_arquivo_endereco']);
		$sql->adInserir('log_arquivo_data', $linha['tarefa_log_arquivo_data']);
		$sql->adInserir('log_arquivo_nome', $linha['tarefa_log_arquivo_nome']);
		$sql->adInserir('log_arquivo_tipo', $linha['tarefa_log_arquivo_tipo']);
		$sql->adInserir('log_arquivo_extensao', $linha['tarefa_log_arquivo_extensao']);
		$sql->exec();
		$sql->limpar();
		}
	}	
	
	
$sql->adTabela('custo');	
$sql->adCampo('custo.*');
$sql->adOnde('custo_tarefa_log IS NOT NULL');
$lista = $sql->lista();
$sql->limpar();		
foreach($lista AS $linha) {
	if (isset($novo_id[$linha['custo_tarefa_log']])){
		$sql->adTabela('custo');
		$sql->adAtualizar('custo_log', $novo_id[$linha['custo_tarefa_log']]);
		$sql->adOnde('custo_id='.(int)$linha['custo_id']);
		$sql->exec();
		$sql->limpar();
		}
	}		

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){		
	$sql->adTabela('custo_observacao');	
	$sql->adCampo('custo_observacao.*');
	$sql->adOnde('custo_observacao_tarefa_log IS NOT NULL');
	$lista = $sql->lista();
	$sql->limpar();		
	foreach($lista AS $linha) {
		if (isset($novo_id[$linha['custo_observacao_tarefa_log']])){
			$sql->adTabela('custo_observacao');
			$sql->adAtualizar('custo_observacao_log', $novo_id[$linha['custo_observacao_tarefa_log']]);
			$sql->adOnde('custo_observacao_id='.(int)$linha['custo_observacao_id']);
			$sql->exec();
			$sql->limpar();
			}
		}	
	}	
		
			
?>