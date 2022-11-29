SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-27';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-27';
UPDATE versao SET versao_bd=424;

ALTER TABLE baseline_eventos ADD COLUMN evento_moeda INTEGER(100) UNSIGNED DEFAULT 1; 

UPDATE perspectivas SET pg_perspectiva_tipo_pontuacao=null WHERE pg_perspectiva_tipo_pontuacao='indicador';
UPDATE estrategias SET pg_estrategia_tipo_pontuacao=null WHERE pg_estrategia_tipo_pontuacao='indicador';
UPDATE fator SET fator_tipo_pontuacao=null WHERE fator_tipo_pontuacao='indicador';
UPDATE metas SET pg_meta_tipo_pontuacao=null WHERE pg_meta_tipo_pontuacao='indicador';
UPDATE me SET me_tipo_pontuacao=null WHERE me_tipo_pontuacao='indicador';
UPDATE objetivo SET objetivo_tipo_pontuacao=null WHERE objetivo_tipo_pontuacao='indicador';
UPDATE risco SET risco_tipo_pontuacao=null WHERE risco_tipo_pontuacao='indicador';
UPDATE risco_resposta SET risco_resposta_tipo_pontuacao=null WHERE risco_resposta_tipo_pontuacao='indicador';
UPDATE tema SET tema_tipo_pontuacao=null WHERE tema_tipo_pontuacao='indicador';


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_agendas','30','qnt','text'),
	('qnt_eventos','30','qnt','text');
	
	
	
INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('eventos','evento_cor','Cor',1),
	('eventos','evento_descricao','Descrição',1),
	('eventos','evento_oque','O que',0),
	('eventos','evento_onde','Onde',0),
	('eventos','evento_porque','Porque',0),
	('eventos','evento_como','Como',0),
	('eventos','evento_quando','Quando',0),
	('eventos','evento_quanto','Quanto',0),
	('eventos','evento_quem','Quem',0),	
	('eventos','evento_inicio','Início',1),	
	('eventos','evento_fim','Término',1),	
	('eventos','evento_cia','Organização responsável',0),
	('eventos','evento_cias','Organizações envolvidas',0),
	('eventos','evento_relacionado','Relacionado',1),
	('eventos','evento_dono','Responsável',1),
	('eventos','evento_designados','Participantes',1),
	('eventos','evento_dept','Seção responsável',1),
	('eventos','evento_depts','Seções envolvidas',0);
	
	
	
