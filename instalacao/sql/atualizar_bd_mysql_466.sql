SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2018-01-28';
UPDATE versao SET ultima_atualizacao_codigo='2018-01-28';
UPDATE versao SET versao_bd=466;

ALTER TABLE ssti_config ADD COLUMN ssti_config_gerente_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_gestor_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_seorp_secretario_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_seorp_coordenador_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_gereo_gerente_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_gereo_coordenador_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_coged_analista_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE ssti_config ADD COLUMN ssti_config_coged_coordenador_nome VARCHAR(255) DEFAULT NULL;


UPDATE ssti_config SET ssti_config_gerente_nome='Gerente Executivo';
UPDATE ssti_config SET ssti_config_gestor_nome='Gestor Solicitante';
UPDATE ssti_config SET ssti_config_seorp_secretario_nome='Secretário Executivo da GPLAN';
UPDATE ssti_config SET ssti_config_seorp_coordenador_nome='Coordenador da GPLAN';
UPDATE ssti_config SET ssti_config_gereo_gerente_nome='Gerente Executivo da GENOR';
UPDATE ssti_config SET ssti_config_gereo_coordenador_nome='Coordenador da GENOR';
UPDATE ssti_config SET ssti_config_coged_analista_nome='Analista SECTI-COGED';
UPDATE ssti_config SET ssti_config_coged_coordenador_nome='Coordenador SECTI-COGED';


ALTER TABLE ssti ADD COLUMN ssti_cancelada_data DATE DEFAULT NULL;
ALTER TABLE ssti ADD COLUMN ssti_cancelada_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD COLUMN ssti_cancelada_justificativa MEDIUMTEXT;
ALTER TABLE ssti ADD KEY ssti_cancelada_usuario (ssti_cancelada_usuario);
ALTER TABLE ssti ADD CONSTRAINT ssti_cancelada_usuario FOREIGN KEY (ssti_cancelada_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;


DROP TABLE IF EXISTS ssti_arquivo;

CREATE TABLE ssti_arquivo (
  ssti_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ssti_arquivo_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_arquivo_ordem INTEGER(11) DEFAULT 0,
  ssti_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  ssti_arquivo_data DATETIME DEFAULT NULL,
  ssti_arquivo_nome VARCHAR(150) DEFAULT NULL,
  ssti_arquivo_nome_real VARCHAR(255) DEFAULT NULL,
	ssti_arquivo_local VARCHAR (255) DEFAULT NULL,
	ssti_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  ssti_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (ssti_arquivo_id),
  KEY ssti_arquivo_ssti (ssti_arquivo_ssti),
  KEY ssti_arquivo_usuario (ssti_arquivo_usuario),
  CONSTRAINT ssti_arquivo_ssti FOREIGN KEY (ssti_arquivo_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_arquivo_usuario FOREIGN KEY (ssti_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;