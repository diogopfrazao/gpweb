SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-28';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-28';
UPDATE versao SET versao_bd=501;

UPDATE config SET config_tipo='text' WHERE config_tipo='quantidade' AND config_grupo='odometro';

ALTER TABLE projetos ADD COLUMN projeto_fase INTEGER(10) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN projeto_fase INTEGER(10) UNSIGNED DEFAULT 0;


ALTER TABLE log ADD COLUMN log_reg_mudanca_fase INTEGER(10) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_log ADD COLUMN log_reg_mudanca_fase INTEGER(10) UNSIGNED DEFAULT 0;


DROP TABLE IF EXISTS estrategias_log;
DROP TABLE IF EXISTS plano_acao_log;
DROP TABLE IF EXISTS pratica_indicador_log;
DROP TABLE IF EXISTS pratica_log;
DROP TABLE IF EXISTS patrocinadores_log;
DROP TABLE IF EXISTS swot_log;
DROP TABLE IF EXISTS problema_log;
DROP TABLE IF EXISTS canvas_log;
DROP TABLE IF EXISTS risco_log;
DROP TABLE IF EXISTS risco_resposta_log;
DROP TABLE IF EXISTS instrumento_log;
DROP TABLE IF EXISTS tema_log;

DELETE FROM sisvalores WHERE sisvalor_titulo='projetoFase';		
INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('projetoFase','','0',NULL),
	('projetoFase','Iniciação','1',NULL),
	('projetoFase','Planejamento','2',NULL),
	('projetoFase','Desenvolvimento','3',NULL),
	('projetoFase','Homologação','4',NULL),
	('projetoFase','Implantação','5',NULL),
	('projetoFase','Piloto','6',NULL),
	('projetoFase','Operação Assistida','7',NULL),
	('projetoFase','Encerramento','8',NULL);
	
	
INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('projeto','projeto_fase','Fase', 1),	
	('projetos','projeto_fase','Fase', 0);	
	