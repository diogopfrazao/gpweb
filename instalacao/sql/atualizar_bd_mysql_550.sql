SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-03-04';
UPDATE versao SET ultima_atualizacao_codigo='2020-03-04';
UPDATE versao SET versao_bd=550;


ALTER TABLE laudo CHANGE COLUMN laudo_ranking_sugestao2 laudo_ranking_sugestao_valor_aceito INT(10) NULL DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_ranking_valor_aceito INT(10) NULL DEFAULT NULL;

update campo_formulario set campo_formulario_campo='laudo_ranking_aceitou_sugestao' where campo_formulario_tipo = 'cogess' and campo_formulario_campo = 'laudo_ranking_sugestao2';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
('cogess','laudo_ranking_sugestao_valor_aceito','Sugestão histórica de ranking definida durante a primeira avaliação', 1),
('cogess','laudo_ranking_valor_aceito','Ranking histórico definido como ranking durante a primeira avaliação', 1);