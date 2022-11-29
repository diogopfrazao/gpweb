SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-05-22';
UPDATE versao SET ultima_atualizacao_codigo='2018-05-22';
UPDATE versao SET versao_bd=490;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
('painel_odometro_tipo','odometro','odometro','select'),
('painel_odometro_ponto0','0','odometro','quantidade'),
('painel_odometro_ponto1','50','odometro','quantidade'),
('painel_odometro_ponto2','80','odometro','quantidade'),
('painel_odometro_ponto3','100','odometro','quantidade');


INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
	('painel_odometro_tipo','odometro'),
	('painel_odometro_tipo','simples');
	
ALTER TABLE ssti ADD COLUMN ssti_paralizado_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD KEY ssti_paralizado_usuario (ssti_paralizado_usuario);
ALTER TABLE ssti ADD CONSTRAINT ssti_paralizado_usuario FOREIGN KEY (ssti_paralizado_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE ssti ADD COLUMN ssti_paralizado_data DATE DEFAULT NULL;
ALTER TABLE ssti ADD COLUMN ssti_paralizado_justificativa MEDIUMTEXT;	


ALTER TABLE laudo DROP FOREIGN KEY laudo_paralizado_usuario;
ALTER TABLE laudo DROP KEY laudo_paralizado_usuario;
ALTER TABLE laudo DROP COLUMN laudo_paralizado_usuario;
ALTER TABLE laudo DROP COLUMN laudo_paralizado_data;
ALTER TABLE laudo DROP COLUMN laudo_paralizado_justificativa;


ALTER TABLE laudo DROP FOREIGN KEY laudo_cancelado_usuario;
ALTER TABLE laudo DROP KEY laudo_cancelado_usuario;
ALTER TABLE laudo DROP COLUMN laudo_cancelado_usuario;
ALTER TABLE laudo DROP COLUMN laudo_cancelado_data;
ALTER TABLE laudo DROP COLUMN laudo_cancelado_justificativa;



ALTER TABLE ssti ADD COLUMN ssti_cancelada_motivo INTEGER(10) UNSIGNED DEFAULT NULL;	
ALTER TABLE ssti ADD COLUMN ssti_paralizado_motivo INTEGER(10) UNSIGNED DEFAULT NULL;	


ALTER TABLE ssti_arquivo ADD COLUMN ssti_arquivo_status VARCHAR(20) DEFAULT NULL;
















	