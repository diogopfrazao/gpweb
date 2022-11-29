SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.13';
UPDATE versao SET ultima_atualizacao_bd='2017-11-19';
UPDATE versao SET ultima_atualizacao_codigo='2017-11-19';
UPDATE versao SET versao_bd=459;

UPDATE sisvalores SET sisvalor_valor='Diverso' WHERE sisvalor_titulo='TipoRecurso' AND sisvalor_valor='Ferramental';
UPDATE preferencia_modulo SET preferencia_modulo_descricao='Caixa de entrada de mensagens' WHERE preferencia_modulo_modulo='email' AND preferencia_modulo_arquivo='lista_msg';


ALTER TABLE plano_gestao_arquivos DROP FOREIGN KEY plano_gestao_arquivos_fk;
ALTER TABLE plano_gestao_arquivos DROP FOREIGN KEY plano_gestao_arquivos_fk1;
ALTER TABLE plano_gestao_arquivos DROP KEY pg_arquivo_usuario;
ALTER TABLE plano_gestao_arquivos DROP KEY pg_arquivo_pg_id;

ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_pg_id plano_gestao_arquivo_plano_gestao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_usuario plano_gestao_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_campo plano_gestao_arquivo_campo VARCHAR(50) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_ordem plano_gestao_arquivo_ordem INTEGER(11) DEFAULT 0;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_endereco plano_gestao_arquivo_endereco VARCHAR(150) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_data plano_gestao_arquivo_data DATETIME DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_nome plano_gestao_arquivo_nome VARCHAR(150) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_tipo plano_gestao_arquivo_tipo VARCHAR(50) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivo_extensao plano_gestao_arquivo_extensao VARCHAR(50) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivos CHANGE pg_arquivos_id plano_gestao_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT;

RENAME TABLE plano_gestao_arquivos TO plano_gestao_arquivo;

ALTER TABLE plano_gestao_arquivo ADD COLUMN plano_gestao_arquivo_nome_real VARCHAR(255) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivo ADD COLUMN plano_gestao_arquivo_local VARCHAR (255) DEFAULT NULL;
ALTER TABLE plano_gestao_arquivo ADD COLUMN plano_gestao_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE plano_gestao_arquivo ADD KEY plano_gestao_arquivo_plano_gestao (plano_gestao_arquivo_plano_gestao);
ALTER TABLE plano_gestao_arquivo ADD KEY plano_gestao_arquivo_usuario (plano_gestao_arquivo_usuario);

ALTER TABLE plano_gestao_arquivo ADD CONSTRAINT plano_gestao_arquivo_plano_gestao FOREIGN KEY (plano_gestao_arquivo_plano_gestao) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_arquivo ADD CONSTRAINT plano_gestao_arquivo_usuario FOREIGN KEY (plano_gestao_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE IF EXISTS agenda_arquivos;

ALTER TABLE anexos DROP FOREIGN KEY anexos_fk;
ALTER TABLE anexos DROP FOREIGN KEY anexos_fk1;
ALTER TABLE anexos DROP FOREIGN KEY anexos_fk2;
ALTER TABLE anexos DROP FOREIGN KEY anexos_fk3;
ALTER TABLE anexos DROP KEY msg_id;
ALTER TABLE anexos DROP KEY usuario_id;
ALTER TABLE anexos DROP KEY modelo;
ALTER TABLE anexos DROP KEY anexos_fk3;

ALTER TABLE anexos CHANGE msg_id anexo_msg INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE anexos CHANGE usuario_id anexo_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE anexos CHANGE modelo anexo_modelo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE anexos CHANGE chave_publica anexo_chave_publica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE anexos CHANGE nome anexo_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE anexos CHANGE caminho anexo_caminho VARCHAR(255) DEFAULT NULL;
ALTER TABLE anexos CHANGE tipo_doc anexo_tipo_doc VARCHAR(80) DEFAULT NULL;
ALTER TABLE anexos CHANGE doc_nr anexo_doc_nr VARCHAR(10) DEFAULT NULL;
ALTER TABLE anexos CHANGE nome_de anexo_nome_de VARCHAR(50) DEFAULT NULL;
ALTER TABLE anexos CHANGE funcao_de anexo_funcao_de VARCHAR(30) DEFAULT NULL;
ALTER TABLE anexos CHANGE data_envio anexo_data_envio DATETIME DEFAULT NULL;
ALTER TABLE anexos CHANGE assinatura anexo_assinatura TEXT;
ALTER TABLE anexos CHANGE nome_fantasia anexo_nome_fantasia VARCHAR(255) DEFAULT NULL;

ALTER TABLE anexos ADD KEY anexo_msg (anexo_msg);
ALTER TABLE anexos ADD KEY anexo_usuario (anexo_usuario);
ALTER TABLE anexos ADD KEY anexo_modelo (anexo_modelo);
ALTER TABLE anexos ADD KEY anexo_chave_publica (anexo_chave_publica);

ALTER TABLE anexos ADD CONSTRAINT anexo_msg FOREIGN KEY (anexo_msg) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexo_usuario FOREIGN KEY (anexo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexo_modelo FOREIGN KEY (anexo_modelo) REFERENCES modelos (modelo_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexo_chave_publica FOREIGN KEY (anexo_chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE anexos ADD COLUMN anexo_nome_real VARCHAR(255) DEFAULT NULL;
ALTER TABLE anexos ADD COLUMN anexo_local VARCHAR(255) DEFAULT NULL;
ALTER TABLE anexos ADD COLUMN anexo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE anexos ADD COLUMN anexo_tipo VARCHAR(50) DEFAULT NULL;
ALTER TABLE anexos ADD COLUMN anexo_extensao VARCHAR(50) DEFAULT NULL;
ALTER TABLE anexos ADD COLUMN anexo_ordem INTEGER(11) DEFAULT 0;

RENAME TABLE anexos TO anexo;

ALTER TABLE modelos_anexos DROP FOREIGN KEY modelos_anexos_fk;
ALTER TABLE modelos_anexos DROP FOREIGN KEY modelos_anexos_fk1;
ALTER TABLE modelos_anexos DROP FOREIGN KEY modelos_anexos_fk2;

ALTER TABLE modelos_anexos DROP KEY usuario_id;
ALTER TABLE modelos_anexos DROP KEY modelo_id;
ALTER TABLE modelos_anexos DROP KEY chave_publica;

ALTER TABLE modelos_anexos CHANGE modelo_id modelo_anexo_modelo INTEGER(100) unsigned DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE usuario_id modelo_anexo_usuario INTEGER(100) unsigned DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE chave_publica modelo_anexo_chave_publica INTEGER(100) unsigned DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE nome modelo_anexo_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE caminho modelo_anexo_caminho VARCHAR(255) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE tipo_doc modelo_anexo_tipo_doc VARCHAR(80) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE doc_nr modelo_anexo_doc_nr VARCHAR(10) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE nome_de modelo_anexo_nome_de VARCHAR(50) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE funcao_de modelo_anexo_funcao_de VARCHAR(50) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE data_envio modelo_anexo_data_envio DATETIME DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE assinatura modelo_anexo_assinatura TEXT;
ALTER TABLE modelos_anexos CHANGE nome_fantasia modelo_anexo_nome_fantasia VARCHAR(255) DEFAULT NULL;
ALTER TABLE modelos_anexos CHANGE idunico modelo_anexo_uuid VARCHAR(36) DEFAULT NULL;

ALTER TABLE modelos_anexos ADD KEY modelo_anexo_modelo (modelo_anexo_modelo);
ALTER TABLE modelos_anexos ADD KEY modelo_anexo_usuario (modelo_anexo_usuario);
ALTER TABLE modelos_anexos ADD KEY modelo_anexo_chave_publica (modelo_anexo_chave_publica);

ALTER TABLE modelos_anexos ADD CONSTRAINT modelo_anexo_modelo FOREIGN KEY (modelo_anexo_modelo) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos_anexos ADD CONSTRAINT modelo_anexo_usuario FOREIGN KEY (modelo_anexo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE modelos_anexos ADD CONSTRAINT modelo_anexo_chave_publica FOREIGN KEY (modelo_anexo_chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE modelos_anexos ADD COLUMN modelo_anexo_nome_real VARCHAR(255) DEFAULT NULL;
ALTER TABLE modelos_anexos ADD COLUMN modelo_anexo_local VARCHAR(255) DEFAULT NULL;
ALTER TABLE modelos_anexos ADD COLUMN modelo_anexo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelos_anexos ADD COLUMN modelo_anexo_tipo VARCHAR(50) DEFAULT NULL;
ALTER TABLE modelos_anexos ADD COLUMN modelo_anexo_extensao VARCHAR(50) DEFAULT NULL;
ALTER TABLE modelos_anexos ADD COLUMN modelo_anexo_ordem INTEGER(11) DEFAULT 0;

RENAME TABLE modelos_anexos TO modelo_anexo;


ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_modelo TINYINT(1) DEFAULT 0;
ALTER TABLE modelos ADD COLUMN modelo_aprovado TINYINT(1) DEFAULT 0;


DROP TABLE evento_arquivo;

