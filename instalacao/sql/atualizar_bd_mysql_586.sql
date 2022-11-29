SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-10-05';
UPDATE versao SET ultima_atualizacao_codigo='2020-10-05';
UPDATE versao SET versao_bd=586;


DROP TABLE IF EXISTS instrumento_processo;

CREATE TABLE instrumento_processo (
	instrumento_processo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	instrumento_processo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_processo_processo varchar(50) DEFAULT NULL,
  instrumento_processo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_processo_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY instrumento_processo_id (instrumento_processo_id),
	KEY instrumento_processo_instrumento (instrumento_processo_instrumento),
	CONSTRAINT instrumento_processo_instrumento FOREIGN KEY (instrumento_processo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS instrumento_edital;

CREATE TABLE instrumento_edital (
	instrumento_edital_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	instrumento_edital_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_edital_edital varchar(50) DEFAULT NULL,
  instrumento_edital_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_edital_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY instrumento_edital_id (instrumento_edital_id),
	KEY instrumento_edital_instrumento (instrumento_edital_instrumento),
	CONSTRAINT instrumento_edital_instrumento FOREIGN KEY (instrumento_edital_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS os_processo;

CREATE TABLE os_processo (
	os_processo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	os_processo_os INTEGER(100) UNSIGNED DEFAULT NULL,
	os_processo_processo varchar(50) DEFAULT NULL,
  os_processo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  os_processo_uuid varchar(36) DEFAULT NULL,
	PRIMARY KEY os_processo_id (os_processo_id),
	KEY os_processo_os (os_processo_os),
	CONSTRAINT os_processo_os FOREIGN KEY (os_processo_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;