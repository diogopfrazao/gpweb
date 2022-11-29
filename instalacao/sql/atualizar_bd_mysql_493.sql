SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-03';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-03';
UPDATE versao SET versao_bd=493;



DROP TABLE causa_efeito_estrategias;
DROP TABLE causa_efeito_fatores;
DROP TABLE causa_efeito_indicadores;
DROP TABLE causa_efeito_metas;
DROP TABLE causa_efeito_objetivos;
DROP TABLE causa_efeito_perspectivas;
DROP TABLE causa_efeito_praticas;
DROP TABLE causa_efeito_projetos;
DROP TABLE causa_efeito_tarefas;
DROP TABLE causa_efeito_tema;


DROP TABLE gut_estrategias;
DROP TABLE gut_fatores;
DROP TABLE gut_indicadores;
DROP TABLE gut_metas;
DROP TABLE gut_objetivos;
DROP TABLE gut_perspectivas;
DROP TABLE gut_praticas;
DROP TABLE gut_projetos;
DROP TABLE gut_tarefas;
DROP TABLE gut_tema;

DROP TABLE brainstorm_estrategias;
DROP TABLE brainstorm_fatores;
DROP TABLE brainstorm_indicadores;
DROP TABLE brainstorm_metas;
DROP TABLE brainstorm_objetivos;
DROP TABLE brainstorm_perspectivas;
DROP TABLE brainstorm_praticas;
DROP TABLE brainstorm_projetos;
DROP TABLE brainstorm_tarefas;
DROP TABLE brainstorm_tema;