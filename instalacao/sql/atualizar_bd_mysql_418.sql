SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-12';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-12';
UPDATE versao SET versao_bd=418;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('planos_gestao','plano_gestao_relacionado','Relacionado',1),
	('perspectivas','pg_perspectiva_relacionado','Relacionado',1);

