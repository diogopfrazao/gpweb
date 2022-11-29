SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-07-31';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-31';
UPDATE versao SET versao_bd=437;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='tema' AND campo_formulario_campo='tema_superior';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='objetivo' AND campo_formulario_campo='objetivo_superior';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='perspectiva' AND campo_formulario_campo='pg_perspectiva_superior';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='me' AND campo_formulario_campo='me_superior';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='objetivo' AND campo_formulario_campo='objetivo_composicao';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='me' AND campo_formulario_campo='me_composicao';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='estrategia' AND campo_formulario_campo='pg_estrategia_composicao';
DELETE FROM campo_formulario WHERE campo_formulario_tipo='pratica' AND campo_formulario_campo='pratica_composicao';