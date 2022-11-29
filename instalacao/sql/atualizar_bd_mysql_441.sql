SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-08-08';
UPDATE versao SET ultima_atualizacao_codigo='2017-08-08';
UPDATE versao SET versao_bd=441;


UPDATE preferencia_modulo SET preferencia_modulo_descricao='trs' WHERE preferencia_modulo_arquivo='tr_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Campos de Matriz SWOT' WHERE preferencia_modulo_arquivo='swot_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Beneficiários' WHERE preferencia_modulo_arquivo='familia_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='pendencias' WHERE preferencia_modulo_arquivo='problema_lista';

UPDATE preferencia_modulo SET preferencia_modulo_descricao='patrocinadores' WHERE preferencia_modulo_arquivo='index' AND preferencia_modulo_modulo='patrocinadores';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Planos operativos' WHERE preferencia_modulo_arquivo='operativo_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Avisos' WHERE preferencia_modulo_arquivo='aviso_lista';




UPDATE preferencia_modulo SET preferencia_modulo_descricao='Recursos' WHERE preferencia_modulo_arquivo='index' AND preferencia_modulo_modulo='recursos';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Links' WHERE preferencia_modulo_arquivo='index' AND preferencia_modulo_modulo='links';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Arquivos' WHERE preferencia_modulo_arquivo='index' AND preferencia_modulo_modulo='arquivos';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='tarefas' WHERE preferencia_modulo_arquivo='index' AND preferencia_modulo_modulo='tarefas';


UPDATE preferencia_modulo SET preferencia_modulo_descricao='Lições aprendidas' WHERE preferencia_modulo_arquivo='licao_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Estudos de viabilidade' WHERE preferencia_modulo_arquivo='viabilidade_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Demandas' WHERE preferencia_modulo_arquivo='demanda_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='projetos' WHERE preferencia_modulo_arquivo='index' AND preferencia_modulo_modulo='projetos';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Planos de ação' WHERE preferencia_modulo_arquivo='plano_acao_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Checklists' WHERE preferencia_modulo_arquivo='checklist_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='metas' WHERE preferencia_modulo_arquivo='meta_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='praticas' WHERE preferencia_modulo_arquivo='pratica_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='iniciativas' WHERE preferencia_modulo_arquivo='estrategias_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='fatores' WHERE preferencia_modulo_arquivo='fator_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='objetivos' WHERE preferencia_modulo_arquivo='obj_estrategicos_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='temas' WHERE preferencia_modulo_arquivo='tema_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='perspectivas' WHERE preferencia_modulo_arquivo='perspectiva_lista';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Indicadores' WHERE preferencia_modulo_arquivo='indicador_lista';