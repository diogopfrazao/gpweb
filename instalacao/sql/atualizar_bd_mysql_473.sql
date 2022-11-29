SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-03-08';
UPDATE versao SET ultima_atualizacao_codigo='2018-03-08';
UPDATE versao SET versao_bd=473;

CALL PROC_DROP_FOREIGN_KEY('brainstorm','brainstorm_responsavel');
CALL PROC_DROP_FOREIGN_KEY('brainstorm','brainstorm_fk1');
ALTER TABLE brainstorm ADD CONSTRAINT brainstorm_responsavel FOREIGN KEY (brainstorm_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE brainstorm DROP FOREIGN KEY brainstorm_dept;
ALTER TABLE brainstorm ADD CONSTRAINT brainstorm_dept FOREIGN KEY (brainstorm_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

CALL PROC_DROP_FOREIGN_KEY('avaliacao','avaliacao_responsavel');
CALL PROC_DROP_FOREIGN_KEY('avaliacao','avaliacao_fk1');
ALTER TABLE avaliacao ADD CONSTRAINT avaliacao_responsavel FOREIGN KEY (avaliacao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE anexo DROP FOREIGN KEY anexo_usuario;
ALTER TABLE anexo ADD CONSTRAINT anexo_usuario FOREIGN KEY (anexo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta DROP FOREIGN KEY arquivo_pasta_dono;
ALTER TABLE arquivo_pasta ADD  CONSTRAINT arquivo_pasta_dono FOREIGN KEY (arquivo_pasta_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo DROP FOREIGN KEY arquivos_dono;
ALTER TABLE arquivo ADD CONSTRAINT arquivo_dono FOREIGN KEY (arquivo_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo DROP FOREIGN KEY arquivos_usuario_upload;
ALTER TABLE arquivo ADD CONSTRAINT arquivo_usuario_upload FOREIGN KEY (arquivo_usuario_upload) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo_historico DROP FOREIGN KEY arquivo_historico_dono;
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_dono FOREIGN KEY (arquivo_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo_historico DROP FOREIGN KEY arquivo_historico_usuario_upload;
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_usuario_upload FOREIGN KEY (arquivo_usuario_upload) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE causa_efeito DROP FOREIGN KEY causa_efeito_dept;
ALTER TABLE causa_efeito ADD CONSTRAINT causa_efeito_dept FOREIGN KEY (causa_efeito_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

CALL PROC_DROP_FOREIGN_KEY('causa_efeito','causa_efeito_fk1');
CALL PROC_DROP_FOREIGN_KEY('causa_efeito','avaliacao_responsavel');
ALTER TABLE causa_efeito ADD CONSTRAINT causa_efeito_responsavel FOREIGN KEY (causa_efeito_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

CALL PROC_DROP_FOREIGN_KEY('eventos','eventos_fk1');
CALL PROC_DROP_FOREIGN_KEY('eventos','evento_dono');
ALTER TABLE eventos ADD CONSTRAINT evento_dono FOREIGN KEY (evento_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE eventos DROP FOREIGN KEY eventos_dept;
ALTER TABLE eventos ADD CONSTRAINT eventos_dept FOREIGN KEY (evento_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE IF EXISTS fator_log;

ALTER TABLE favorito DROP FOREIGN KEY favorito_dept;
ALTER TABLE favorito ADD CONSTRAINT favorito_dept FOREIGN KEY (favorito_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE gut DROP FOREIGN KEY gut_dept;
ALTER TABLE gut ADD CONSTRAINT gut_dept FOREIGN KEY (gut_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

CALL PROC_DROP_FOREIGN_KEY('gut','gut_fk1');
CALL PROC_DROP_FOREIGN_KEY('gut','gut_responsavel');
ALTER TABLE gut ADD CONSTRAINT gut_responsavel FOREIGN KEY (gut_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE projeto_area DROP FOREIGN KEY projeto_area_fk15;

ALTER TABLE log_arquivo DROP FOREIGN KEY log_arquivos_fk1;
ALTER TABLE log_arquivo ADD CONSTRAINT log_arquivo_usuario FOREIGN KEY (log_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE indicador_valor_arquivo DROP FOREIGN KEY indicador_valor_arquivos_fk2;
ALTER TABLE indicador_valor_arquivo ADD CONSTRAINT indicador_valor_arquivo_usuario FOREIGN KEY (indicador_valor_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE eb_arquivo DROP FOREIGN KEY eb_arquivo_fk1;
ALTER TABLE eb_arquivo ADD CONSTRAINT eb_arquivo_usuario FOREIGN KEY (eb_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE baseline_eb_arquivo DROP FOREIGN KEY baseline_eb_arquivo_usuario;
ALTER TABLE baseline_eb_arquivo ADD CONSTRAINT baseline_eb_arquivo_usuario FOREIGN KEY (eb_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE ssti_arquivo DROP FOREIGN KEY ssti_arquivo_usuario;
ALTER TABLE ssti_arquivo ADD CONSTRAINT ssti_arquivo_usuario FOREIGN KEY (ssti_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE laudo_arquivo DROP FOREIGN KEY laudo_arquivo_usuario;
ALTER TABLE laudo_arquivo ADD CONSTRAINT laudo_arquivo_usuario FOREIGN KEY (laudo_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE agrupamento DROP FOREIGN KEY agrupamento_dept;
ALTER TABLE agrupamento ADD CONSTRAINT agrupamento_dept FOREIGN KEY (agrupamento_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE swot DROP FOREIGN KEY swot_dept;
ALTER TABLE swot ADD CONSTRAINT swot_dept FOREIGN KEY (swot_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE operativo DROP FOREIGN KEY operativo_dept;
ALTER TABLE operativo ADD CONSTRAINT operativo_dept FOREIGN KEY (operativo_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE problema DROP FOREIGN KEY problema_dept;
ALTER TABLE problema ADD CONSTRAINT problema_dept FOREIGN KEY (problema_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE programa DROP FOREIGN KEY programa_dept;
ALTER TABLE programa ADD CONSTRAINT programa_dept FOREIGN KEY (programa_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tgn DROP FOREIGN KEY tgn_dept;
ALTER TABLE tgn ADD CONSTRAINT tgn_dept FOREIGN KEY (tgn_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE risco DROP FOREIGN KEY risco_fk4;
ALTER TABLE risco ADD CONSTRAINT risco_dept FOREIGN KEY (risco_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tema DROP FOREIGN KEY tema_fk5;
ALTER TABLE tema ADD CONSTRAINT tema_dept FOREIGN KEY (tema_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE demandas DROP FOREIGN KEY demanda_dept;
ALTER TABLE demandas ADD CONSTRAINT demanda_dept FOREIGN KEY (demanda_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE perspectivas DROP FOREIGN KEY perspectivas_fk3;
ALTER TABLE perspectivas ADD CONSTRAINT pg_perspectiva_dept FOREIGN KEY (pg_perspectiva_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE IF EXISTS perspectiva_log;

CALL PROC_DROP_FOREIGN_KEY('estrategias','estrategias_fk4');
CALL PROC_DROP_FOREIGN_KEY('estrategias','estrategias_fk5');
ALTER TABLE estrategias ADD CONSTRAINT pg_estrategia_dept FOREIGN KEY (pg_estrategia_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE risco_resposta DROP FOREIGN KEY risco_resposta_dept;
ALTER TABLE risco_resposta ADD CONSTRAINT risco_resposta_dept FOREIGN KEY (risco_resposta_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE monitoramento DROP FOREIGN KEY monitoramento_dept;
ALTER TABLE monitoramento ADD CONSTRAINT monitoramento_dept FOREIGN KEY (monitoramento_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE painel_odometro DROP FOREIGN KEY painel_odometro_dept;
ALTER TABLE painel_odometro ADD CONSTRAINT painel_odometro_dept FOREIGN KEY (painel_odometro_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE painel DROP FOREIGN KEY painel_dept;
ALTER TABLE painel ADD CONSTRAINT painel_dept FOREIGN KEY (painel_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE painel_slideshow DROP FOREIGN KEY painel_slideshow_dept;
ALTER TABLE painel_slideshow ADD CONSTRAINT painel_slideshow_dept FOREIGN KEY (painel_slideshow_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE painel_composicao DROP FOREIGN KEY painel_composicao_dept;
ALTER TABLE painel_composicao ADD CONSTRAINT painel_composicao_dept FOREIGN KEY (painel_composicao_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE canvas DROP FOREIGN KEY canvas_dept;
ALTER TABLE canvas ADD CONSTRAINT canvas_dept FOREIGN KEY (canvas_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE ata DROP FOREIGN KEY ata_dept;
ALTER TABLE ata ADD CONSTRAINT ata_dept FOREIGN KEY (ata_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE avaliacao DROP FOREIGN KEY avaliacao_dept;
ALTER TABLE avaliacao ADD CONSTRAINT avaliacao_dept FOREIGN KEY (avaliacao_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE projeto_viabilidade DROP FOREIGN KEY projeto_viabilidade_dept;
ALTER TABLE projeto_viabilidade ADD CONSTRAINT projeto_viabilidade_dept FOREIGN KEY (projeto_viabilidade_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE projeto_abertura DROP FOREIGN KEY projeto_abertura_dept;
ALTER TABLE projeto_abertura ADD CONSTRAINT projeto_abertura_dept FOREIGN KEY (projeto_abertura_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE objetivo DROP FOREIGN KEY objetivo_dept;
ALTER TABLE objetivo ADD CONSTRAINT objetivo_dept FOREIGN KEY (objetivo_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE fator DROP FOREIGN KEY fator_dept;
ALTER TABLE fator ADD CONSTRAINT fator_dept FOREIGN KEY (fator_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE metas DROP FOREIGN KEY metas_fk8;
ALTER TABLE metas ADD CONSTRAINT pg_meta_dept FOREIGN KEY (pg_meta_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE praticas DROP FOREIGN KEY praticas_dept;
ALTER TABLE praticas ADD CONSTRAINT pratica_dept FOREIGN KEY (pratica_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE projetos DROP FOREIGN KEY projetos_dept;
ALTER TABLE projetos ADD CONSTRAINT projeto_dept FOREIGN KEY (projeto_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tarefas DROP FOREIGN KEY tarefas_dept;
ALTER TABLE tarefas ADD CONSTRAINT tarefa_dept FOREIGN KEY (tarefa_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo_historico DROP FOREIGN KEY arquivo_historico_dept;
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_dept FOREIGN KEY (arquivo_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE foruns DROP FOREIGN KEY forum_dept;
ALTER TABLE foruns ADD CONSTRAINT  forum_dept FOREIGN KEY (forum_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE recursos DROP FOREIGN KEY recursos_dept;
ALTER TABLE recursos ADD CONSTRAINT recurso_dept FOREIGN KEY (recurso_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE links DROP FOREIGN KEY link_dept;
ALTER TABLE links ADD CONSTRAINT link_dept FOREIGN KEY (link_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE IF EXISTS metas_log;

DROP TABLE IF EXISTS objetivo_log;

ALTER TABLE tr DROP FOREIGN KEY tr_dept_demandante;
ALTER TABLE tr ADD CONSTRAINT tr_dept_demandante FOREIGN KEY (tr_dept_demandante) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE checklist DROP FOREIGN KEY checklist_dept;
ALTER TABLE checklist ADD CONSTRAINT checklist_dept FOREIGN KEY (checklist_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE pratica_indicador DROP FOREIGN KEY pratica_indicador_dept;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_dept FOREIGN KEY (pratica_indicador_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE plano_acao DROP FOREIGN KEY plano_acao_dept;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_dept FOREIGN KEY (plano_acao_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta DROP FOREIGN KEY arquivo_pasta_dept;
ALTER TABLE arquivo_pasta ADD CONSTRAINT arquivo_pasta_dept FOREIGN KEY (arquivo_pasta_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivo DROP FOREIGN KEY arquivo_dept;
ALTER TABLE arquivo ADD CONSTRAINT arquivo_dept FOREIGN KEY (arquivo_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE instrumento DROP FOREIGN KEY instrumento_dept;
ALTER TABLE instrumento ADD CONSTRAINT instrumento_dept FOREIGN KEY (instrumento_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE indicador_lacuna DROP FOREIGN KEY indicador_lacuna_dept;
ALTER TABLE indicador_lacuna ADD CONSTRAINT indicador_lacuna_dept FOREIGN KEY (indicador_lacuna_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE template DROP FOREIGN KEY template_dept;
ALTER TABLE template ADD CONSTRAINT template_dept FOREIGN KEY (template_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tr DROP FOREIGN KEY tr_dept;
ALTER TABLE tr ADD CONSTRAINT tr_dept FOREIGN KEY (tr_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE me DROP FOREIGN KEY me_dept;
ALTER TABLE me ADD CONSTRAINT me_dept FOREIGN KEY (me_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE mswot DROP FOREIGN KEY mswot_dept;
ALTER TABLE mswot ADD CONSTRAINT mswot_dept FOREIGN KEY (mswot_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE beneficio DROP FOREIGN KEY beneficio_dept;
ALTER TABLE beneficio ADD CONSTRAINT beneficio_dept FOREIGN KEY (beneficio_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE patrocinadores DROP FOREIGN KEY patrocinador_dept;
ALTER TABLE patrocinadores ADD CONSTRAINT patrocinador_dept FOREIGN KEY (patrocinador_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE pdcl DROP FOREIGN KEY pdcl_dept;
ALTER TABLE pdcl ADD CONSTRAINT pdcl_dept FOREIGN KEY (pdcl_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;