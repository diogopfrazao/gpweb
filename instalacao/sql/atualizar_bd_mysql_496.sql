SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-11';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-11';
UPDATE versao SET versao_bd=496;

ALTER TABLE ssti DROP COLUMN ssti_construido;
ALTER TABLE laudo ADD COLUMN laudo_criacao DATETIME DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_aprovado_data DATETIME DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_paralisado_data DATE DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_paralisado_motivo INTEGER(10) UNSIGNED DEFAULT NULL;

DROP TABLE IF EXISTS laudo_paralisado;

CREATE TABLE laudo_paralisado (
  laudo_paralisado_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  laudo_paralisado_laudo INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_paralisado_usuario_inicio INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_paralisado_usuario_fim INTEGER(100) UNSIGNED DEFAULT NULL,
  laudo_paralisado_inicio DATETIME DEFAULT NULL,
  laudo_paralisado_fim DATETIME DEFAULT NULL,
  laudo_paralisado_motivo INTEGER(10) UNSIGNED DEFAULT NULL,
  laudo_paralisado_horas DECIMAL(20,5) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (laudo_paralisado_id),
  KEY laudo_paralisado_laudo (laudo_paralisado_laudo),
  KEY laudo_paralisado_usuario_inicio (laudo_paralisado_usuario_inicio),
  KEY laudo_paralisado_usuario_fim (laudo_paralisado_usuario_fim),
  CONSTRAINT laudo_paralisado_laudo FOREIGN KEY (laudo_paralisado_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT laudo_paralisado_usuario_inicio FOREIGN KEY (laudo_paralisado_usuario_inicio) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT laudo_paralisado_usuario_fim FOREIGN KEY (laudo_paralisado_usuario_fim) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;