SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.01';
UPDATE versao SET ultima_atualizacao_bd='2017-03-01';
UPDATE versao SET ultima_atualizacao_codigo='2017-03-01';
UPDATE versao SET versao_bd=394;


ALTER TABLE plano_gestao ADD COLUMN pg_aprovado TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_plano_gestao TINYINT(1) DEFAULT 0;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='indicadores';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='checklists';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='planos_acao';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='iniciativas';



INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('indicadores','pratica_indicador_cia','Organiza??o respons?vel',0),
	('indicadores','pratica_indicador_cias','Organiza??es envolvidas',0),
	('indicadores','pratica_indicador_dept','Se??o respons?vel',1),
	('indicadores','pratica_indicador_depts','Se??es envolvidas',0),
	('indicadores','pratica_indicador_responsavel','Respons?vel',1),
	('indicadores','pratica_indicador_designados','Designados',0),
	('indicadores','pratica_indicador_aprovado','Aprovado',1),
	('indicadores','pratica_indicador_cor','Cor',1),
	('indicadores','pratica_indicador_relacionado','Relacionado',1),
	('indicadores','pratica_indicador_codigo','C?digo',0),
	('indicadores','pratica_indicador_tendencia','Ted?ncia',1),
	('indicadores','pratica_indicador_pontuacao','Pontua??o',1),
	('indicadores','pratica_indicador_valor','Valor',1),
	('indicadores','pratica_indicador_meta','Meta',1),
	('indicadores','pratica_indicador_unidade','Unidade de Medida',1),
	('indicadores','pratica_indicador_data_meta','Data da Meta',1),
	('indicadores','pratica_indicador_agrupar','Periodicidade',1),
	('indicadores','pratica_indicador_acumulacao','Tipo de Acumula??o',1),
	('indicadores','pratica_indicador_data_alteracao','Data da ?ltima altera??o',1),
	('indicadores','pratica_indicador_marcadores','Quantidade de Marcadores',1),
	('indicadores','pratica_indicador_requisito_descricao','Descri??o',0),
	('indicadores','pratica_indicador_requisito_oque','O Que Fazer',0),
	('indicadores','pratica_indicador_requisito_onde','Onde Fazer',0),
	('indicadores','pratica_indicador_requisito_quando','Quando Fazer',0),
	('indicadores','pratica_indicador_requisito_como','Como Fazer',0),
	('indicadores','pratica_indicador_requisito_porque','Por Que Fazer',0),
	('indicadores','pratica_indicador_requisito_quanto','Quanto Custa',0),
	('indicadores','pratica_indicador_requisito_quem','Quem Faz',0),
	('indicadores','pratica_indicador_requisito_melhorias','Melhorias',0),
	('checklists','checklist_cia','Organiza??o respons?vel',0),
	('checklists','checklist_cias','Organiza??es envolvidas',0),
	('checklists','checklist_dept','Se??o respons?vel',1),
	('checklists','checklist_depts','Se??es envolvidas',0),
	('checklists','checklist_responsavel','Respons?vel',1),
	('checklists','checklist_designados','Designados',0),
	('checklists','checklist_aprovado','Aprovado',1),
	('checklists','checklist_cor','Cor',1),
	('checklists','checklist_relacionado','Relacionado',1),
	('checklists','checklist_descricao','Descri??o',1),
	('checklists','checklist_quantidade','Quantidade de Linhas',1),
	('acoes','plano_acao_cia','Organiza??o respons?vel',0),
	('acoes','plano_acao_cias','Organiza??es envolvidas',0),
	('acoes','plano_acao_dept','Se??o respons?vel',1),
	('acoes','plano_acao_depts','Se??es envolvidas',0),
	('acoes','plano_acao_responsavel','Respons?vel',1),
	('acoes','plano_acao_designados','Designados',0),
	('acoes','plano_acao_aprovado','Aprovado',1),
	('acoes','plano_acao_cor','Cor',1),
	('acoes','plano_acao_relacionado','Relacionado',1),
	('acoes','plano_acao_descricao','Descri??o',1),
	('acoes','plano_acao_inicio','In?cio',1),
	('acoes','plano_acao_fim','T?rmino',1),
	('acoes','plano_acao_percentagem','Percentagem',1),
	('acoes','plano_acao_codigo','C?digo',0),
	('acoes','plano_acao_quantidade','Quantidade de Linhas',1),
	('acoes','plano_acao_ano','Ano',0),
	('brainstorms','brainstorm_cor','Cor',1),
	('brainstorms','brainstorm_aprovado','Aprovado',1),
	('brainstorms','brainstorm_cia','Organiza??o respons?vel',0),
	('brainstorms','brainstorm_cias','Organiza??es envolvidas',0),
	('brainstorms','brainstorm_dept','Se??o respons?vel',1),
	('brainstorms','brainstorm_depts','Se??es envolvidas',0),
	('brainstorms','brainstorm_responsavel','Respons?vel',1),
	('brainstorms','brainstorm_designados','Designados',1),
	('brainstorms','brainstorm_descricao','Descri??o',1),
	('brainstorms','brainstorm_relacionado','Relacionado',1),
	('brainstorms','brainstorm_data','Data',1),
	('causa_efeitos','causa_efeito_cor','Cor',1),
	('causa_efeitos','causa_efeito_aprovado','Aprovado',1),
	('causa_efeitos','causa_efeito_cia','Organiza??o respons?vel',0),
	('causa_efeitos','causa_efeito_cias','Organiza??es envolvidas',0),
	('causa_efeitos','causa_efeito_dept','Se??o respons?vel',1),
	('causa_efeitos','causa_efeito_depts','Se??es envolvidas',0),
	('causa_efeitos','causa_efeito_responsavel','Respons?vel',1),
	('causa_efeitos','causa_efeito_designados','Designados',1),
	('causa_efeitos','causa_efeito_descricao','Descri??o',1),
	('causa_efeitos','causa_efeito_relacionado','Relacionado',1),
	('causa_efeitos','causa_efeito_data','Data',1),
	('guts','gut_cor','Cor',1),
	('guts','gut_aprovado','Aprovado',1),
	('guts','gut_cia','Organiza??o respons?vel',0),
	('guts','gut_cias','Organiza??es envolvidas',0),
	('guts','gut_dept','Se??o respons?vel',1),
	('guts','gut_depts','Se??es envolvidas',0),
	('guts','gut_responsavel','Respons?vel',1),
	('guts','gut_designados','Designados',1),
	('guts','gut_descricao','Descri??o',1),
	('guts','gut_relacionado','Relacionado',1),
	('guts','gut_data','Data',1),
	('canvass','canvas_cias','Organiza??es envolvidas',0),
	('tgns','tgn_cias','Organiza??es envolvidas',0),
	('paineis','painel_cor','Cor',1),
	('paineis','painel_aprovado','Aprovado',1),
	('paineis','painel_cia','Organiza??o respons?vel',0),
	('paineis','painel_cias','Organiza??es envolvidas',0),
	('paineis','painel_dept','Se??o respons?vel',1),
	('paineis','painel_depts','Se??es envolvidas',0),
	('paineis','painel_responsavel','Respons?vel',1),
	('paineis','painel_designados','Designados',0),
	('paineis','painel_descricao','Descri??o',1),
	('paineis','painel_relacionado','Relacionado',1),
	('paineis_composicao','painel_composicao_cor','Cor',1),
	('paineis_composicao','painel_composicao_aprovado','Aprovado',1),
	('paineis_composicao','painel_composicao_cia','Organiza??o respons?vel',0),
	('paineis_composicao','painel_composicao_cias','Organiza??es envolvidas',0),
	('paineis_composicao','painel_composicao_dept','Se??o respons?vel',1),
	('paineis_composicao','painel_composicao_depts','Se??es envolvidas',0),
	('paineis_composicao','painel_composicao_responsavel','Respons?vel',1),
	('paineis_composicao','painel_composicao_designados','Designados',0),
	('paineis_composicao','painel_composicao_descricao','Descri??o',1),
	('paineis_composicao','painel_composicao_relacionado','Relacionado',1),
	('paineis_odometro','painel_odometro_cor','Cor',1),
	('paineis_odometro','painel_odometro_aprovado','Aprovado',1),
	('paineis_odometro','painel_odometro_cia','Organiza??o respons?vel',0),
	('paineis_odometro','painel_odometro_cias','Organiza??es envolvidas',0),
	('paineis_odometro','painel_odometro_dept','Se??o respons?vel',1),
	('paineis_odometro','painel_odometro_depts','Se??es envolvidas',0),
	('paineis_odometro','painel_odometro_responsavel','Respons?vel',1),
	('paineis_odometro','painel_odometro_designados','Designados',0),
	('paineis_odometro','painel_odometro_descricao','Descri??o',1),
	('paineis_odometro','painel_odometro_relacionado','Relacionado',1),
	('paineis_slideshow','painel_slideshow_cor','Cor',1),
	('paineis_slideshow','painel_slideshow_cia','Organiza??o respons?vel',0),
	('paineis_slideshow','painel_slideshow_cias','Organiza??es envolvidas',0),
	('paineis_slideshow','painel_slideshow_dept','Se??o respons?vel',1),
	('paineis_slideshow','painel_slideshow_depts','Se??es envolvidas',0),
	('paineis_slideshow','painel_slideshow_responsavel','Respons?vel',1),
	('paineis_slideshow','painel_slideshow_designados','Designados',0),
	('paineis_slideshow','painel_slideshow_descricao','Descri??o',1),
	('planos_gestao','plano_gestao_cor','Cor',1),
	('planos_gestao','plano_gestao_aprovado','Aprovado',1),
	('planos_gestao','plano_gestao_cia','Organiza??o respons?vel',0),
	('planos_gestao','plano_gestao_cias','Organiza??es envolvidas',0),
	('planos_gestao','plano_gestao_dept','Se??o respons?vel',1),
	('planos_gestao','plano_gestao_depts','Se??es envolvidas',0),
	('planos_gestao','plano_gestao_responsavel','Respons?vel',1),
	('planos_gestao','plano_gestao_designados','Designados',1),
	('planos_gestao','plano_gestao_descricao','Descri??o',1),
	('planos_gestao','plano_gestao_inicio','In?cio',1),
	('planos_gestao','plano_gestao_fim','T?rmino',1),
	('planejamento','priorizacao', 'Prioriza??o', 1),
	('monitoramentos','monitoramento_cia','Organiza??o respons?vel',0),
	('monitoramentos','monitoramento_cias','Organiza??es envolvidas',0),
	('monitoramentos','monitoramento_dept','Se??o respons?vel',1),
	('monitoramentos','monitoramento_depts','Se??es envolvidas',0),
	('monitoramentos','monitoramento_usuario','Respons?vel',1),
	('monitoramentos','monitoramento_designados','Designados',0),
	('monitoramentos','monitoramento_aprovado','Aprovado',1),
	('monitoramentos','monitoramento_cor','Cor',1),
	('monitoramentos','monitoramento_relacionado','Relacionado',1),
	('monitoramentos','monitoramento_descricao','Descri??o',1),
	('monitoramentos','monitoramento_oque','O Que Fazer',0),
	('monitoramentos','monitoramento_onde','Onde Fazer',0),
	('monitoramentos','monitoramento_quando','Quando Fazer',0),
	('monitoramentos','monitoramento_como','Como Fazer',0),
	('monitoramentos','monitoramento_porque','Por Que Fazer',0),
	('monitoramentos','monitoramento_quanto','Quanto Custa',0),
	('monitoramentos','monitoramento_quem','Quem Faz',0),
	('monitoramentos','monitoramento_controle','Controle',0),
	('monitoramentos','monitoramento_melhorias','Melhorias',0),
	('monitoramentos','monitoramento_desde_quando','Desde quando',0),
	('monitoramentos','monitoramento_metodo_aprendizado','Metodo de aprendizado',0),
	('riscos','risco_cor','Cor',1),
	('riscos','risco_cia','Organiza??o respons?vel',0),
	('riscos','risco_cias','Organiza??es envolvidas',0),
	('riscos','risco_dept','Se??o respons?vel',1),
	('riscos','risco_depts','Se??es envolvidas',0),
	('riscos','risco_usuario','Respons?vel',1),	
	('riscos','risco_designados','Designados',0),		
	('riscos','risco_inicio','In?cio',0),
	('riscos','risco_fim','T?rmino',0),
	('riscos','risco_gatilho','Gatilho',0),
	('riscos','risco_acao_proposta','A??o Proposta',0),
	('riscos','risco_descricao','Descri??o',0),
	('riscos','risco_aprovado','Aprovado',1),
	('riscos','risco_relacionado','Relacionado',1),
	('riscos','risco_tipo_pontuacao','Sistema de Percentagem',0),
	('riscos','risco_percentagem','Percentagem',1),
	('riscos','risco_gut','G.U.T.',1),
	('riscos','risco_resposta_risco','Respostas ao risco atreladas.',1),
	('risco_respostas','risco_resposta_cor','Cor',1),
	('risco_respostas','risco_resposta_cia','Organiza??o respons?vel',0),
	('risco_respostas','risco_resposta_cias','Organiza??es envolvidas',0),
	('risco_respostas','risco_resposta_dept','Se??o respons?vel',1),
	('risco_respostas','risco_resposta_depts','Se??es envolvidas',0),
	('risco_respostas','risco_resposta_usuario','Respons?vel',1),	
	('risco_respostas','risco_resposta_designados','Designados',0),		
	('risco_respostas','risco_resposta_inicio','In?cio',0),
	('risco_respostas','risco_resposta_fim','T?rmino',0),
	('risco_respostas','risco_resposta_acao_proposta','A??o Proposta',0),
	('risco_respostas','risco_resposta_descricao','Descri??o',0),
	('risco_respostas','risco_resposta_aprovado','Aprovado',1),
	('risco_respostas','risco_resposta_relacionado','Relacionado',1),
	('risco_respostas','risco_resposta_tipo_pontuacao','Sistema de Percentagem',0),
	('risco_respostas','risco_resposta_percentagem','Percentagem',1);