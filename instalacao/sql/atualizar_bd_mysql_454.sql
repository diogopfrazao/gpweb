SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.11';
UPDATE versao SET ultima_atualizacao_bd='2017-10-23';
UPDATE versao SET ultima_atualizacao_codigo='2017-10-23';
UPDATE versao SET versao_bd=454;

DELETE FROM campo_formulario WHERE campo_formulario_campo='recurso_hora_custo';
UPDATE campo_formulario SET campo_formulario_descricao='Valor' WHERE campo_formulario_campo='recurso_custo';

ALTER TABLE problema CHANGE problema_inicio problema_inicio INTEGER(100) UNSIGNED NOT NULL;
