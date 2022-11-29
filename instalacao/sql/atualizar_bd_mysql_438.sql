SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-07-31';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-31';
UPDATE versao SET versao_bd=438;

ALTER TABLE usuarios DROP COLUMN usuario_superior;


CALL PROC_DROP_FOREIGN_KEY('estrategias','estrategias_fk5');
CALL PROC_DROP_FOREIGN_KEY('praticas','praticas_fk2');
CALL PROC_DROP_FOREIGN_KEY('arquivo','arquivo_fk');
CALL PROC_DROP_FOREIGN_KEY('eventos','eventos_fk2');
CALL PROC_DROP_FOREIGN_KEY('links','links_fk1');









CALL PROC_DROP_FOREIGN_KEY('tema','tema_fk1');
CALL PROC_DROP_KEY('tema', 'tema_superior');
ALTER TABLE tema DROP COLUMN tema_superior;

CALL PROC_DROP_FOREIGN_KEY('demandas','demandas_superior');
CALL PROC_DROP_KEY('demandas', 'demanda_superior');
ALTER TABLE demandas DROP COLUMN demanda_superior;

CALL PROC_DROP_FOREIGN_KEY('perspectivas','perspectivas_fk4');
CALL PROC_DROP_KEY('perspectivas', 'pg_perspectiva_superior');
ALTER TABLE perspectivas DROP COLUMN pg_perspectiva_superior;

CALL PROC_DROP_FOREIGN_KEY('objetivo','objetivo_superior');
CALL PROC_DROP_KEY('objetivo', 'objetivo_superior');
ALTER TABLE objetivo DROP COLUMN objetivo_superior;

CALL PROC_DROP_FOREIGN_KEY('fator','fator_superior');
CALL PROC_DROP_KEY('fator', 'fator_superior');
ALTER TABLE fator DROP COLUMN fator_superior;

CALL PROC_DROP_FOREIGN_KEY('estrategias','estrategias_fk6');
CALL PROC_DROP_KEY('estrategias', 'pg_estrategia_superior');
ALTER TABLE estrategias DROP COLUMN pg_estrategia_superior;

CALL PROC_DROP_FOREIGN_KEY('metas','metas_fk9');
CALL PROC_DROP_KEY('metas', 'pg_meta_superior');
ALTER TABLE metas DROP COLUMN pg_meta_superior;

CALL PROC_DROP_FOREIGN_KEY('praticas','praticas_superior');
CALL PROC_DROP_KEY('praticas', 'pratica_superior');
ALTER TABLE praticas DROP COLUMN pratica_superior;

CALL PROC_DROP_FOREIGN_KEY('arquivo','arquivo_superior');
CALL PROC_DROP_KEY('arquivo', 'arquivo_superior');
ALTER TABLE arquivo DROP COLUMN arquivo_superior;

CALL PROC_DROP_FOREIGN_KEY('arquivo_historico','arquivo_historico_superior');
CALL PROC_DROP_KEY('arquivo_historico', 'arquivo_superior');
ALTER TABLE arquivo_historico DROP COLUMN arquivo_superior;

CALL PROC_DROP_FOREIGN_KEY('eventos','evento_superior');
CALL PROC_DROP_KEY('eventos', 'evento_superior');
ALTER TABLE eventos DROP COLUMN evento_superior;
ALTER TABLE baseline_eventos DROP COLUMN evento_superior;

CALL PROC_DROP_FOREIGN_KEY('links','links_superior');
CALL PROC_DROP_KEY('links', 'idx_link_superior');
ALTER TABLE links DROP COLUMN link_superior;

CALL PROC_DROP_FOREIGN_KEY('me','me_superior');
CALL PROC_DROP_KEY('me', 'me_superior');
ALTER TABLE me DROP COLUMN me_superior;

CALL PROC_DROP_FOREIGN_KEY('beneficio','beneficio_superior');
CALL PROC_DROP_KEY('beneficio', 'beneficio_superior');
ALTER TABLE beneficio DROP COLUMN beneficio_superior;

CALL PROC_DROP_FOREIGN_KEY('programa','programa_superior');
CALL PROC_DROP_KEY('programa', 'programa_superior');
ALTER TABLE programa DROP COLUMN programa_superior;

CALL PROC_DROP_FOREIGN_KEY('tgn','tgn_superior');
CALL PROC_DROP_KEY('tgn', 'tgn_superior');
ALTER TABLE tgn DROP COLUMN tgn_superior;

CALL PROC_DROP_FOREIGN_KEY('risco','risco_fk1');
CALL PROC_DROP_KEY('risco', 'risco_superior');
ALTER TABLE risco DROP COLUMN risco_superior;

CALL PROC_DROP_FOREIGN_KEY('risco_resposta','risco_resposta_superior');
CALL PROC_DROP_KEY('risco_resposta', 'risco_resposta_superior');
ALTER TABLE risco_resposta DROP COLUMN risco_resposta_superior;

CALL PROC_DROP_FOREIGN_KEY('canvas','canvas_superior');
CALL PROC_DROP_KEY('canvas', 'canvas_superior');
ALTER TABLE canvas DROP COLUMN canvas_superior;



ALTER TABLE objetivo DROP COLUMN objetivo_composicao;
DROP TABLE IF EXISTS objetivo_composicao;

ALTER TABLE me DROP COLUMN me_composicao;
DROP TABLE IF EXISTS me_composicao;

ALTER TABLE estrategias DROP COLUMN pg_estrategia_composicao;
DROP TABLE IF EXISTS estrategias_composicao;

ALTER TABLE praticas DROP COLUMN pratica_composicao;
DROP TABLE IF EXISTS pratica_composicao;