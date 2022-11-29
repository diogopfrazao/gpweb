SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-05-11';
UPDATE versao SET ultima_atualizacao_codigo='2018-05-11';
UPDATE versao SET versao_bd=487;

ALTER TABLE ssti ADD COLUMN ssti_multa TINYINT(1) DEFAULT 0;
ALTER TABLE ssti ADD COLUMN ssti_multa_valor decimal(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE ssti ADD COLUMN ssti_multa_unidade INTEGER(10) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD COLUMN ssti_outra_sancao TINYINT(1) DEFAULT 0;




DROP TABLE IF EXISTS ssti_estrategia;

CREATE TABLE ssti_estrategia (
  ssti_estrategia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ssti_estrategia_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_estrategia_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_estrategia_data DATETIME DEFAULT NULL,
  ssti_estrategia_conformidade_estrategia TINYINT(1) DEFAULT 0,
  ssti_estrategia_conformidade_demanda TINYINT(1) DEFAULT 0, 
  ssti_estrategia_observacao MEDIUMTEXT,
  ssti_estrategia_apto TINYINT(1) DEFAULT 0,
  PRIMARY KEY (ssti_estrategia_id),
  KEY ssti_estrategia_ssti (ssti_estrategia_ssti),
  KEY ssti_estrategia_usuario (ssti_estrategia_usuario),
  CONSTRAINT ssti_estrategia_ssti FOREIGN KEY (ssti_estrategia_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_estrategia_usuario FOREIGN KEY (ssti_estrategia_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ssti_processo;

CREATE TABLE ssti_processo (
  ssti_processo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ssti_processo_ssti INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_processo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  ssti_processo_data DATETIME DEFAULT NULL,
  ssti_processo_solicitante_responsavel TINYINT(1) DEFAULT 0,
  ssti_processo_gestores_envolvidos TINYINT(1) DEFAULT 0, 
  ssti_processo_cria_processo TINYINT(1) DEFAULT 0, 
  ssti_processo_observacao MEDIUMTEXT,
  ssti_processo_apto TINYINT(1) DEFAULT 0, 
  PRIMARY KEY (ssti_processo_id),
  KEY ssti_processo_ssti (ssti_processo_ssti),
  KEY ssti_processo_usuario (ssti_processo_usuario),
  CONSTRAINT ssti_processo_ssti FOREIGN KEY (ssti_processo_ssti) REFERENCES ssti (ssti_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ssti_processo_usuario FOREIGN KEY (ssti_processo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



ALTER TABLE ssti ADD COLUMN ssti_estrategia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD COLUMN ssti_processo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ssti ADD KEY ssti_estrategia (ssti_estrategia);
ALTER TABLE ssti ADD KEY ssti_processo (ssti_processo);
ALTER TABLE ssti ADD CONSTRAINT ssti_estrategia FOREIGN KEY (ssti_estrategia) REFERENCES ssti_estrategia (ssti_estrategia_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE ssti ADD CONSTRAINT ssti_processo FOREIGN KEY (ssti_processo) REFERENCES ssti_processo (ssti_processo_id) ON DELETE SET NULL ON UPDATE CASCADE;




ALTER TABLE laudo ADD COLUMN laudo_cancelado_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE laudo ADD KEY laudo_cancelado_usuario (laudo_cancelado_usuario);
ALTER TABLE laudo ADD CONSTRAINT laudo_cancelado_usuario FOREIGN KEY (laudo_cancelado_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE laudo ADD COLUMN laudo_cancelado_data DATE DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_cancelado_justificativa MEDIUMTEXT;