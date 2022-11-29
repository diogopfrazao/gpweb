SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-14';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-14';
UPDATE versao SET versao_bd=536;


DROP TABLE IF EXISTS instrumento_campo;

CREATE TABLE instrumento_campo (
  instrumento_campo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_campo_nome VARCHAR(255) DEFAULT NULL,
  instrumento_campo_ordem INTEGER(100) DEFAULT NULL,
  instrumento_identificacao TINYINT(1) DEFAULT 1,
  instrumento_identificacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_nome TINYINT(1) DEFAULT 1,
  instrumento_nome_leg VARCHAR(50) DEFAULT NULL,
  instrumento_entidade TINYINT(1) DEFAULT 1,
  instrumento_entidade_leg VARCHAR(50) DEFAULT NULL,
  instrumento_entidade_cnpj TINYINT(1) DEFAULT 1,
  instrumento_entidade_cnpj_leg VARCHAR(50) DEFAULT NULL,
  instrumento_tipo TINYINT(1) DEFAULT 1,
  instrumento_tipo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_numero TINYINT(1) DEFAULT 1,
  instrumento_numero_leg VARCHAR(50) DEFAULT NULL,
  instrumento_ano TINYINT(1) DEFAULT 1,
  instrumento_ano_leg VARCHAR(50) DEFAULT NULL,
  instrumento_prorrogavel TINYINT(1) DEFAULT 0,
  instrumento_prorrogavel_leg VARCHAR(50) DEFAULT NULL,
  instrumento_situacao TINYINT(1) DEFAULT 1,
  instrumento_situacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_valor TINYINT(1) DEFAULT 1,
  instrumento_valor_leg VARCHAR(50) DEFAULT NULL,
  instrumento_valor_contrapartida TINYINT(1) DEFAULT 1,
  instrumento_valor_contrapartida_leg VARCHAR(50) DEFAULT NULL,
  instrumento_moeda TINYINT(1) DEFAULT 1,
  instrumento_moeda_leg VARCHAR(50) DEFAULT NULL,
  instrumento_demandante TINYINT(1) DEFAULT 1,
  instrumento_demandante_leg VARCHAR(50) DEFAULT NULL,
  instrumento_cia TINYINT(1) DEFAULT 1,
  instrumento_cias TINYINT(1) DEFAULT 1,
  instrumento_dept TINYINT(1) DEFAULT 1,
  instrumento_depts TINYINT(1) DEFAULT 1,
  instrumento_responsavel TINYINT(1) DEFAULT 1,
  instrumento_designados TINYINT(1) DEFAULT 1,
  instrumento_supervisor TINYINT(1) DEFAULT 1,
  instrumento_autoridade TINYINT(1) DEFAULT 1,
  instrumento_cliente TINYINT(1) DEFAULT 1,
  instrumento_fiscal TINYINT(1) DEFAULT 1,
  instrumento_fiscal_leg VARCHAR(50) DEFAULT NULL,
  instrumento_fiscal_substituto TINYINT(1) DEFAULT 1,
  instrumento_fiscal_substituto_leg VARCHAR(50) DEFAULT NULL,
  instrumento_adtivo TINYINT(1) DEFAULT 1,
  instrumento_adtivo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_prazo_prorrogacao TINYINT(1) DEFAULT 1,
  instrumento_prazo_prorrogacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_acrescimo TINYINT(1) DEFAULT 1,
  instrumento_acrescimo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_supressao TINYINT(1) DEFAULT 1,
  instrumento_supressao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_detalhamento TINYINT(1) DEFAULT 1,
  instrumento_detalhamento_leg VARCHAR(50) DEFAULT NULL,
  instrumento_objeto TINYINT(1) DEFAULT 1,
  instrumento_objeto_leg VARCHAR(50) DEFAULT NULL,
  instrumento_justificativa TINYINT(1) DEFAULT 1,
  instrumento_justificativa_leg VARCHAR(50) DEFAULT NULL,
  instrumento_resultado_esperado TINYINT(1) DEFAULT 1,
  instrumento_resultado_esperado_leg VARCHAR(50) DEFAULT NULL,
  instrumento_vantagem_economica TINYINT(1) DEFAULT 1,
  instrumento_vantagem_economica_leg VARCHAR(50) DEFAULT NULL,
  instrumento_datas TINYINT(1) DEFAULT 1,
  instrumento_datas_leg VARCHAR(50) DEFAULT NULL,
  instrumento_data_celebracao TINYINT(1) DEFAULT 1,
  instrumento_data_celebracao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_data_inicio TINYINT(1) DEFAULT 1,
  instrumento_data_inicio_leg VARCHAR(50) DEFAULT NULL,
  instrumento_data_termino TINYINT(1) DEFAULT 1,
  instrumento_data_termino_leg VARCHAR(50) DEFAULT NULL,
  instrumento_data_publicacao TINYINT(1) DEFAULT 1,
  instrumento_data_publicacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_protocolo TINYINT(1) DEFAULT 1,
  instrumento_protocolo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_licitacao TINYINT(1) DEFAULT 1,
  instrumento_licitacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_edital_nr TINYINT(1) DEFAULT 1,
  instrumento_edital_nr_leg VARCHAR(50) DEFAULT NULL,
  instrumento_processo TINYINT(1) DEFAULT 1,
  instrumento_processo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_dados TINYINT(1) DEFAULT 1,
  instrumento_dados_leg VARCHAR(50) DEFAULT NULL,
  instrumento_porcentagem TINYINT(1) DEFAULT 1,
  instrumento_porcentagem_leg VARCHAR(50) DEFAULT NULL,
  instrumento_contatos TINYINT(1) DEFAULT 1,
  instrumento_recursos TINYINT(1) DEFAULT 1,
  instrumento_relacionados TINYINT(1) DEFAULT 1,
  instrumento_financeiro TINYINT(1) DEFAULT 1,
  instrumento_financeiro_leg VARCHAR(50) DEFAULT NULL,
  instrumento_financeiro_projeto_leg VARCHAR(50) DEFAULT NULL,
  instrumento_financeiro_tarefa TINYINT(1) DEFAULT 1,
  instrumento_financeiro_tarefa_leg VARCHAR(50) DEFAULT NULL,
  instrumento_financeiro_fonte TINYINT(1) DEFAULT 1,
  instrumento_financeiro_fonte_leg VARCHAR(50) DEFAULT NULL,
  instrumento_financeiro_regiao TINYINT(1) DEFAULT 1,
  instrumento_financeiro_regiao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_financeiro_classificacao TINYINT(1) DEFAULT 1,
  instrumento_financeiro_classificacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_usuario TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_codigo TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_codigo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_fonte TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_fonte_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_regiao TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_regiao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_tipo TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_tipo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_bdi TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_bdi_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_moeda TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_nd TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_nd_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_categoria_economica_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_grupo_despesa_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_modalidade_aplicacao_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_data_limite TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_data_limite_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_pi TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_pi_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_ptres TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_ptres_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_acrescimo TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_acrescimo_leg VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_acrescimo_leg2 VARCHAR(50) DEFAULT NULL,
  instrumento_avulso_custo_exercicio TINYINT(1) DEFAULT 1,
  instrumento_avulso_custo_exercicio_leg VARCHAR(50) DEFAULT NULL,
  instrumento_local_entrega TINYINT(1) DEFAULT 1,
  instrumento_local_entrega_leg VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (instrumento_campo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


INSERT INTO instrumento_campo (instrumento_campo_id, instrumento_campo_nome, instrumento_campo_ordem, instrumento_identificacao, instrumento_identificacao_leg, instrumento_nome, instrumento_nome_leg, instrumento_entidade, instrumento_entidade_leg, instrumento_entidade_cnpj, instrumento_entidade_cnpj_leg, instrumento_tipo, instrumento_tipo_leg, instrumento_numero, instrumento_numero_leg, instrumento_ano, instrumento_ano_leg, instrumento_prorrogavel, instrumento_prorrogavel_leg, instrumento_situacao, instrumento_situacao_leg, instrumento_valor, instrumento_valor_leg, instrumento_valor_contrapartida, instrumento_valor_contrapartida_leg, instrumento_moeda, instrumento_moeda_leg, instrumento_demandante, instrumento_demandante_leg, instrumento_cia, instrumento_cias, instrumento_dept, instrumento_depts, instrumento_responsavel, instrumento_designados, instrumento_supervisor, instrumento_autoridade, instrumento_cliente, instrumento_fiscal, instrumento_fiscal_leg, instrumento_fiscal_substituto, instrumento_fiscal_substituto_leg, instrumento_adtivo, instrumento_adtivo_leg, instrumento_prazo_prorrogacao, instrumento_prazo_prorrogacao_leg, instrumento_acrescimo, instrumento_acrescimo_leg, instrumento_supressao, instrumento_supressao_leg, instrumento_detalhamento, instrumento_detalhamento_leg, instrumento_objeto, instrumento_objeto_leg, instrumento_justificativa, instrumento_justificativa_leg, instrumento_resultado_esperado, instrumento_resultado_esperado_leg, instrumento_vantagem_economica, instrumento_vantagem_economica_leg, instrumento_datas, instrumento_datas_leg, instrumento_data_celebracao, instrumento_data_celebracao_leg, instrumento_data_inicio, instrumento_data_inicio_leg, instrumento_data_termino, instrumento_data_termino_leg, instrumento_data_publicacao, instrumento_data_publicacao_leg, instrumento_protocolo, instrumento_protocolo_leg, instrumento_licitacao, instrumento_licitacao_leg, instrumento_edital_nr, instrumento_edital_nr_leg, instrumento_processo, instrumento_processo_leg, instrumento_dados, instrumento_dados_leg, instrumento_porcentagem, instrumento_porcentagem_leg, instrumento_contatos, instrumento_recursos, instrumento_relacionados, instrumento_financeiro, instrumento_financeiro_leg, instrumento_financeiro_projeto_leg, instrumento_financeiro_tarefa, instrumento_financeiro_tarefa_leg, instrumento_financeiro_fonte, instrumento_financeiro_fonte_leg, instrumento_financeiro_regiao, instrumento_financeiro_regiao_leg, instrumento_financeiro_classificacao, instrumento_financeiro_classificacao_leg, instrumento_avulso_custo, instrumento_avulso_custo_leg, instrumento_avulso_custo_usuario, instrumento_avulso_custo_codigo, instrumento_avulso_custo_codigo_leg, instrumento_avulso_custo_fonte, instrumento_avulso_custo_fonte_leg, instrumento_avulso_custo_regiao, instrumento_avulso_custo_regiao_leg, instrumento_avulso_custo_tipo, instrumento_avulso_custo_tipo_leg, instrumento_avulso_custo_bdi, instrumento_avulso_custo_bdi_leg, instrumento_avulso_custo_moeda, instrumento_avulso_custo_nd, instrumento_avulso_custo_nd_leg, instrumento_avulso_custo_categoria_economica_leg, instrumento_avulso_custo_grupo_despesa_leg, instrumento_avulso_custo_modalidade_aplicacao_leg, instrumento_avulso_custo_data_limite, instrumento_avulso_custo_data_limite_leg, instrumento_avulso_custo_pi, instrumento_avulso_custo_pi_leg, instrumento_avulso_custo_ptres, instrumento_avulso_custo_ptres_leg, instrumento_avulso_custo_acrescimo, instrumento_avulso_custo_acrescimo_leg, instrumento_avulso_custo_acrescimo_leg2, instrumento_avulso_custo_exercicio, instrumento_avulso_custo_exercicio_leg, instrumento_local_entrega, instrumento_local_entrega_leg) VALUES
  (1,'Instrumento',1,1,'Identificação',1,'Nome',1,'Entidade',1,'CNPJ da entidade',1,'Tipo',1,'Número',1,'Ano',1,'Prorrogável',1,'Situação',1,'Valor contrato vigente',1,'Valor da contrapartida',1,'Moeda',1,'Dados do setor demandante',1,1,1,1,1,1,1,1,1,1,'Fiscal',1,'Fiscal substituto',1,'Objeto do aditivo',1,'Prazo de prorrogação',1,'Percentual de acréscimo',1,'Percentual de supressão',1,'Detalhamento',1,'Objeto',1,'Justificativa',1,'Resultados esperados',1,'Vantagem econômica',1,'Datas (preenchimento exclusivo da CAC/GAQ)',1,'Data de celebração',1,'Data de início',1,'Data de término',1,'Data de publicação',1,'Protocolo',1,'Licitação',1,'Número do edital',1,'Número do processo',1,'Dados Extra',1,'Realizado',1,1,1,1,'Dados Orçamentários','Região / Projeto Atividade',1,'Medida / Tarefa',1,'Fonte',1,'Região',1,'Classificação da despesa',1,'Descrição dos itens',1,1,'Código',1,'Fonte',1,'Região',1,'Unidade de medida',1,'BDI',1,1,'Elemento de despesa','Categoria econômica','Grupo de despesa','Modalidade de aplicação',1,'Data de recebimento',1,'PI',1,'PTRes',1,'Quant. acréscimo supressão','Perc. acréscimo supressão',1,'exercício',1,'Local de entrega');
 

ALTER TABLE instrumento_avulso_custo DROP COLUMN instrumento_avulso_custo_percentagem;
ALTER TABLE instrumento DROP COLUMN instrumento_edital_ano;
ALTER TABLE instrumento DROP COLUMN instrumento_data_requerimento;
ALTER TABLE instrumento_avulso_custo DROP COLUMN instrumento_avulso_custo_metodo;
ALTER TABLE instrumento DROP COLUMN instrumento_cliente_data;
ALTER TABLE instrumento DROP COLUMN instrumento_cliente_aprovado;
ALTER TABLE instrumento DROP COLUMN instrumento_cliente_obs;
ALTER TABLE instrumento DROP COLUMN instrumento_cliente_ativo;
ALTER TABLE instrumento DROP COLUMN instrumento_supervisor_data;
ALTER TABLE instrumento DROP COLUMN instrumento_supervisor_aprovado;
ALTER TABLE instrumento DROP COLUMN instrumento_supervisor_obs;
ALTER TABLE instrumento DROP COLUMN instrumento_supervisor_ativo;
ALTER TABLE instrumento DROP COLUMN instrumento_autoridade_data;
ALTER TABLE instrumento DROP COLUMN instrumento_autoridade_aprovado;
ALTER TABLE instrumento DROP COLUMN instrumento_autoridade_obs;
ALTER TABLE instrumento DROP COLUMN instrumento_autoridade_ativo;


ALTER TABLE instrumento ADD COLUMN instrumento_campo INTEGER(100) UNSIGNED DEFAULT 1;
ALTER TABLE instrumento ADD KEY instrumento_campo (instrumento_campo);
ALTER TABLE instrumento ADD CONSTRAINT instrumento_campo FOREIGN KEY (instrumento_campo) REFERENCES instrumento_campo (instrumento_campo_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE instrumento MODIFY instrumento_campo INTEGER(100) UNSIGNED DEFAULT 1 AFTER instrumento_id;

