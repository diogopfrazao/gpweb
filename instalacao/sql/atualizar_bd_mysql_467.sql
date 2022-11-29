SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2018-01-28';
UPDATE versao SET ultima_atualizacao_codigo='2018-01-28';
UPDATE versao SET versao_bd=467;

ALTER TABLE ssti ADD COLUMN ssti_laudo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD KEY ssti_laudo (ssti_laudo);
ALTER TABLE ssti ADD CONSTRAINT ssti_laudo FOREIGN KEY (ssti_laudo) REFERENCES laudo (laudo_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE laudo ADD COLUMN laudo_ssti INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE laudo ADD KEY laudo_ssti (laudo_ssti);
ALTER TABLE laudo ADD CONSTRAINT laudo_ssti FOREIGN KEY (laudo_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ssti ADD COLUMN ssti_construido TINYINT(1) DEFAULT 0;

ALTER TABLE laudo ADD COLUMN laudo_paralizado_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE laudo ADD KEY laudo_paralizado_usuario (laudo_paralizado_usuario);
ALTER TABLE laudo ADD CONSTRAINT laudo_paralizado_usuario FOREIGN KEY (laudo_paralizado_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE laudo ADD COLUMN laudo_paralizado_data DATE DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_paralizado_justificativa MEDIUMTEXT;

DROP TABLE IF EXISTS laudo_arquivo;

CREATE TABLE laudo_arquivo (
  laudo_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  laudo_arquivo_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_arquivo_ordem INTEGER(11) DEFAULT 0,
  laudo_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  laudo_arquivo_data DATETIME DEFAULT NULL,
  laudo_arquivo_nome VARCHAR(150) DEFAULT NULL,
  laudo_arquivo_nome_real VARCHAR(255) DEFAULT NULL,
	laudo_arquivo_local VARCHAR (255) DEFAULT NULL,
	laudo_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  laudo_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (laudo_arquivo_id),
  KEY laudo_arquivo_laudo (laudo_arquivo_laudo),
  KEY laudo_arquivo_usuario (laudo_arquivo_usuario),
  CONSTRAINT laudo_arquivo_laudo FOREIGN KEY (laudo_arquivo_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_arquivo_usuario FOREIGN KEY (laudo_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


UPDATE perfil_submodulo SET perfil_submodulo_descricao='Equipe da GENOR' WHERE perfil_submodulo_submodulo='equipe_gereo';
UPDATE perfil_submodulo SET perfil_submodulo_descricao='Equipe da GPLAN' WHERE perfil_submodulo_submodulo='equipe_seorp';


UPDATE campo_formulario SET campo_formulario_ativo=1 WHERE campo_formulario_campo='laudo_ranking' AND campo_formulario_tipo='laudos';
UPDATE campo_formulario SET campo_formulario_ativo=1 WHERE campo_formulario_campo='laudo_comite' AND campo_formulario_tipo='laudos';


ALTER TABLE laudo ADD COLUMN laudo_projeto INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE laudo ADD KEY laudo_projeto (laudo_projeto);
ALTER TABLE laudo ADD CONSTRAINT laudo_projeto FOREIGN KEY (laudo_projeto) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE;