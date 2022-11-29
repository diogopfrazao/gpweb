SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-01';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-01';
UPDATE versao SET versao_bd=492;



ALTER TABLE ssti CHANGE ssti_paralizado_data ssti_paralisado_data DATE DEFAULT NULL;
ALTER TABLE ssti CHANGE ssti_paralizado_justificativa ssti_paralisado_justificativa MEDIUMTEXT;
ALTER TABLE ssti CHANGE ssti_paralizado_motivo  ssti_paralisado_motivo INTEGER(10) UNSIGNED DEFAULT NULL;

ALTER TABLE ssti DROP FOREIGN KEY ssti_paralizado_usuario;
ALTER TABLE ssti DROP KEY ssti_paralizado_usuario;
ALTER TABLE ssti CHANGE ssti_paralizado_usuario ssti_paralisado_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD KEY ssti_paralisado_usuario (ssti_paralisado_usuario);
ALTER TABLE ssti ADD CONSTRAINT ssti_paralisado_usuario FOREIGN KEY (ssti_paralisado_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;



UPDATE ssti_arquivo SET ssti_arquivo_status='paralizado' WHERE ssti_arquivo_status='paralizado';
UPDATE campo_formulario SET campo_formulario_campo='laudo_paralisado', campo_formulario_descricao='Paralisado' WHERE campo_formulario_campo='laudo_paralizado';
