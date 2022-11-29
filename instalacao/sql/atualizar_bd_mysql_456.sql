SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.11';
UPDATE versao SET ultima_atualizacao_bd='2017-11-06';
UPDATE versao SET ultima_atualizacao_codigo='2017-11-06';
UPDATE versao SET versao_bd=456;


ALTER TABLE ata CHANGE ata_relato ata_relato MEDIUMTEXT;
ALTER TABLE agrupamento CHANGE agrupamento_descricao agrupamento_descricao MEDIUMTEXT;
ALTER TABLE swot CHANGE swot_descricao swot_descricao MEDIUMTEXT;
ALTER TABLE operativo CHANGE operativo_descricao operativo_descricao MEDIUMTEXT;
ALTER TABLE programa CHANGE programa_descricao programa_descricao MEDIUMTEXT;
ALTER TABLE tgn CHANGE tgn_descricao tgn_descricao MEDIUMTEXT;
ALTER TABLE risco CHANGE risco_gatilho risco_gatilho MEDIUMTEXT;
ALTER TABLE risco CHANGE risco_acao_proposta risco_acao_proposta MEDIUMTEXT;
ALTER TABLE risco CHANGE risco_descricao risco_descricao MEDIUMTEXT;
ALTER TABLE risco_resposta CHANGE risco_resposta_acao_proposta risco_resposta_acao_proposta MEDIUMTEXT;
ALTER TABLE risco_resposta CHANGE risco_resposta_descricao risco_resposta_descricao MEDIUMTEXT;
ALTER TABLE monitoramento CHANGE monitoramento_descricao monitoramento_descricao MEDIUMTEXT;
ALTER TABLE painel_odometro CHANGE painel_odometro_descricao painel_odometro_descricao MEDIUMTEXT;
ALTER TABLE painel_odometro CHANGE painel_odometro_titulo painel_odometro_titulo VARCHAR(255) DEFAULT NULL;
ALTER TABLE painel_odometro CHANGE painel_odometro_subtitulo painel_odometro_subtitulo VARCHAR(255) DEFAULT NULL;
ALTER TABLE painel CHANGE painel_descricao painel_descricao MEDIUMTEXT;
ALTER TABLE painel CHANGE painel_titulo painel_titulo VARCHAR(255) DEFAULT NULL;
ALTER TABLE painel CHANGE painel_subtitulo painel_subtitulo VARCHAR(255) DEFAULT NULL;
ALTER TABLE painel_slideshow CHANGE painel_slideshow_descricao painel_slideshow_descricao MEDIUMTEXT;
ALTER TABLE painel_composicao CHANGE painel_composicao_descricao painel_composicao_descricao MEDIUMTEXT;
ALTER TABLE painel_composicao CHANGE painel_composicao_titulo painel_composicao_titulo VARCHAR(255) DEFAULT NULL;
ALTER TABLE painel_composicao CHANGE painel_composicao_subtitulo painel_composicao_subtitulo VARCHAR(255) DEFAULT NULL;

ALTER TABLE custo CHANGE custo_descricao custo_descricao MEDIUMTEXT;
ALTER TABLE brainstorm CHANGE brainstorm_descricao brainstorm_descricao MEDIUMTEXT;
ALTER TABLE canvas CHANGE canvas_descricao canvas_descricao MEDIUMTEXT;
ALTER TABLE tema CHANGE tema_descricao tema_descricao MEDIUMTEXT;
ALTER TABLE ata_pauta CHANGE ata_pauta_texto ata_pauta_texto MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_justificativa projeto_mudanca_justificativa MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_parecer_tecnico projeto_mudanca_parecer_tecnico MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_solucoes projeto_mudanca_solucoes MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_impacto_cronograma projeto_mudanca_impacto_cronograma MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_impacto_custo projeto_mudanca_impacto_custo MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_novo_risco projeto_mudanca_novo_risco MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_outros_impactos projeto_mudanca_outros_impactos MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_solucao projeto_mudanca_solucao MEDIUMTEXT;
ALTER TABLE projeto_mudanca CHANGE projeto_mudanca_parecer projeto_mudanca_parecer MEDIUMTEXT;
ALTER TABLE projeto_recebimento CHANGE projeto_recebimento_observacao projeto_recebimento_observacao MEDIUMTEXT;
ALTER TABLE projeto_recebimento_lista CHANGE projeto_recebimento_lista_produto projeto_recebimento_lista_produto MEDIUMTEXT;
ALTER TABLE projeto_encerramento CHANGE projeto_encerramento_justificativa projeto_encerramento_justificativa MEDIUMTEXT;
ALTER TABLE licao CHANGE licao_ocorrencia licao_ocorrencia MEDIUMTEXT;
ALTER TABLE licao CHANGE licao_consequencia licao_consequencia MEDIUMTEXT;
ALTER TABLE licao CHANGE licao_acao_tomada licao_acao_tomada MEDIUMTEXT;
ALTER TABLE licao CHANGE licao_aprendizado licao_aprendizado MEDIUMTEXT;
ALTER TABLE avaliacao CHANGE avaliacao_descricao avaliacao_descricao MEDIUMTEXT;
ALTER TABLE avaliacao_indicador_lista CHANGE avaliacao_indicador_lista_observacao avaliacao_indicador_lista_observacao MEDIUMTEXT;
ALTER TABLE projeto_risco CHANGE projeto_risco_descricao projeto_risco_descricao MEDIUMTEXT;
ALTER TABLE projeto_risco_tipo CHANGE projeto_risco_tipo_descricao projeto_risco_tipo_descricao MEDIUMTEXT;
ALTER TABLE projeto_risco_tipo CHANGE projeto_risco_tipo_resposta projeto_risco_tipo_resposta MEDIUMTEXT;
ALTER TABLE projeto_risco_tipo CHANGE projeto_risco_tipo_acao projeto_risco_tipo_acao MEDIUMTEXT;
ALTER TABLE projeto_risco_tipo CHANGE projeto_risco_tipo_consequencia projeto_risco_tipo_consequencia MEDIUMTEXT;
ALTER TABLE projeto_comunicacao CHANGE projeto_comunicacao_descricao projeto_comunicacao_descricao MEDIUMTEXT;
ALTER TABLE projeto_comunicacao_evento CHANGE projeto_comunicacao_evento_objetivo projeto_comunicacao_evento_objetivo MEDIUMTEXT;
ALTER TABLE projeto_comunicacao_evento CHANGE projeto_comunicacao_evento_responsavel projeto_comunicacao_evento_responsavel MEDIUMTEXT;
ALTER TABLE projeto_comunicacao_evento CHANGE projeto_comunicacao_evento_publico projeto_comunicacao_evento_publico MEDIUMTEXT;
ALTER TABLE projeto_comunicacao_evento CHANGE projeto_comunicacao_evento_canal projeto_comunicacao_evento_canal MEDIUMTEXT;
ALTER TABLE projeto_comunicacao_evento CHANGE projeto_comunicacao_evento_periodicidade projeto_comunicacao_evento_periodicidade MEDIUMTEXT;
ALTER TABLE projeto_qualidade_entrega CHANGE projeto_qualidade_entrega_criterio projeto_qualidade_entrega_criterio MEDIUMTEXT;
ALTER TABLE projeto_qualidade CHANGE projeto_qualidade_descricao projeto_qualidade_descricao MEDIUMTEXT;
ALTER TABLE artefatos_tipo CHANGE artefato_tipo_descricao artefato_tipo_descricao MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_necessidade projeto_viabilidade_necessidade MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_alinhamento projeto_viabilidade_alinhamento MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_requisitos projeto_viabilidade_requisitos MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_solucoes projeto_viabilidade_solucoes MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_viabilidade_tecnica projeto_viabilidade_viabilidade_tecnica MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_financeira projeto_viabilidade_financeira MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_institucional projeto_viabilidade_institucional MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_solucao projeto_viabilidade_solucao MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_continuidade projeto_viabilidade_continuidade MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_tempo projeto_viabilidade_tempo MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_custo projeto_viabilidade_custo MEDIUMTEXT;
ALTER TABLE projeto_viabilidade CHANGE projeto_viabilidade_observacao projeto_viabilidade_observacao MEDIUMTEXT;
ALTER TABLE cias CHANGE cia_descricao cia_descricao MEDIUMTEXT;
ALTER TABLE cias DROP COLUMN cia_customizado;
ALTER TABLE depts CHANGE dept_descricao dept_descricao MEDIUMTEXT;
ALTER TABLE contatos CHANGE contato_notas contato_notas MEDIUMTEXT;
ALTER TABLE usuarios CHANGE usuario_rodape usuario_rodape MEDIUMTEXT;
ALTER TABLE agenda CHANGE agenda_descricao agenda_descricao MEDIUMTEXT;
ALTER TABLE msg CHANGE texto texto MEDIUMTEXT;
ALTER TABLE anotacao CHANGE texto texto MEDIUMTEXT;
ALTER TABLE perspectivas CHANGE pg_perspectiva_descricao pg_perspectiva_descricao MEDIUMTEXT;
ALTER TABLE objetivo CHANGE objetivo_descricao objetivo_descricao MEDIUMTEXT;
ALTER TABLE fator CHANGE fator_descricao fator_descricao MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_nome pg_estrategia_nome TEXT;
ALTER TABLE metas CHANGE pg_meta_descricao pg_meta_descricao MEDIUMTEXT;
ALTER TABLE checklist CHANGE checklist_descricao checklist_descricao MEDIUMTEXT;
ALTER TABLE tarefas CHANGE tarefa_descricao tarefa_descricao MEDIUMTEXT;
ALTER TABLE baseline_tarefas CHANGE tarefa_descricao tarefa_descricao MEDIUMTEXT;
ALTER TABLE plano_acao CHANGE plano_acao_descricao plano_acao_descricao MEDIUMTEXT;
ALTER TABLE arquivo_pasta CHANGE arquivo_pasta_descricao arquivo_pasta_descricao MEDIUMTEXT;
ALTER TABLE arquivo CHANGE arquivo_descricao arquivo_descricao MEDIUMTEXT;
ALTER TABLE arquivo_saida CHANGE arquivo_saida_motivo arquivo_saida_motivo MEDIUMTEXT;
ALTER TABLE arquivo_historico CHANGE arquivo_descricao arquivo_descricao MEDIUMTEXT;
ALTER TABLE baseline CHANGE baseline_descricao baseline_descricao MEDIUMTEXT;
ALTER TABLE baseline_eventos CHANGE evento_descricao evento_descricao MEDIUMTEXT;
ALTER TABLE eventos CHANGE evento_descricao evento_descricao MEDIUMTEXT;
ALTER TABLE baseline_projeto_area CHANGE projeto_area_obs projeto_area_obs MEDIUMTEXT;
ALTER TABLE projeto_area CHANGE projeto_area_obs projeto_area_obs MEDIUMTEXT;
ALTER TABLE baseline_projeto_contatos CHANGE perfil perfil MEDIUMTEXT;
ALTER TABLE projeto_contatos CHANGE perfil perfil MEDIUMTEXT;
ALTER TABLE baseline_projeto_integrantes CHANGE projeto_integrante_atributo projeto_integrante_atributo MEDIUMTEXT;
ALTER TABLE baseline_projeto_integrantes CHANGE projeto_integrantes_situacao projeto_integrantes_situacao MEDIUMTEXT;
ALTER TABLE baseline_projeto_integrantes CHANGE projeto_integrantes_necessidade projeto_integrantes_necessidade MEDIUMTEXT;
ALTER TABLE projeto_integrantes CHANGE projeto_integrante_atributo projeto_integrante_atributo MEDIUMTEXT;
ALTER TABLE projeto_integrantes CHANGE projeto_integrantes_situacao projeto_integrantes_situacao MEDIUMTEXT;
ALTER TABLE projeto_integrantes CHANGE projeto_integrantes_necessidade projeto_integrantes_necessidade MEDIUMTEXT;
ALTER TABLE projeto_observado CHANGE obs_remetente obs_remetente MEDIUMTEXT;
ALTER TABLE projeto_observado CHANGE obs_destinatario obs_destinatario MEDIUMTEXT;
ALTER TABLE tarefa_custos CHANGE tarefa_custos_descricao tarefa_custos_descricao MEDIUMTEXT;
ALTER TABLE baseline_tarefa_custos CHANGE tarefa_custos_descricao tarefa_custos_descricao MEDIUMTEXT;
ALTER TABLE tarefa_gastos CHANGE tarefa_gastos_descricao tarefa_gastos_descricao MEDIUMTEXT;
ALTER TABLE baseline_tarefa_gastos CHANGE tarefa_gastos_descricao tarefa_gastos_descricao MEDIUMTEXT;
ALTER TABLE tarefa_h_custos CHANGE h_custos_descricao2 h_custos_descricao2 MEDIUMTEXT;
ALTER TABLE tarefa_h_custos CHANGE h_custos_descricao1 h_custos_descricao1 MEDIUMTEXT;
ALTER TABLE tarefa_h_gastos CHANGE h_gastos_descricao1 h_gastos_descricao1 MEDIUMTEXT;
ALTER TABLE tarefa_h_gastos CHANGE h_gastos_descricao2 h_gastos_descricao2 MEDIUMTEXT;
ALTER TABLE tarefas_bloco CHANGE tarefas_bloco_detalhe tarefas_bloco_detalhe MEDIUMTEXT;
ALTER TABLE template CHANGE template_descricao template_descricao MEDIUMTEXT;
ALTER TABLE me CHANGE me_descricao me_descricao MEDIUMTEXT;
ALTER TABLE mswot CHANGE mswot_descricao mswot_descricao MEDIUMTEXT;



ALTER TABLE recurso_tarefa CHANGE recurso_tarefa_obs recurso_tarefa_obs MEDIUMTEXT;
ALTER TABLE baseline_recurso_tarefa CHANGE recurso_tarefa_obs recurso_tarefa_obs MEDIUMTEXT;
ALTER TABLE recurso_tarefa_custo CHANGE recurso_tarefa_custo_descricao recurso_tarefa_custo_descricao MEDIUMTEXT;
ALTER TABLE baseline_recurso_tarefa_custo CHANGE recurso_tarefa_custo_descricao recurso_tarefa_custo_descricao MEDIUMTEXT;
ALTER TABLE projeto_custo CHANGE projeto_custo_descricao projeto_custo_descricao MEDIUMTEXT;
ALTER TABLE baseline_projeto_custo CHANGE projeto_custo_descricao projeto_custo_descricao MEDIUMTEXT;
ALTER TABLE beneficio CHANGE beneficio_descricao beneficio_descricao MEDIUMTEXT;