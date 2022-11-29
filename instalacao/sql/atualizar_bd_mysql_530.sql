SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-02';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-02';
UPDATE versao SET versao_bd=530;

DROP TABLE IF EXISTS instrumento_config;

CREATE TABLE instrumento_config (
	instrumento_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	instrumento_config_exibe_funcao TINYINT DEFAULT 0,
	instrumento_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	instrumento_config_exibe_linha2 TINYINT DEFAULT 0,
	instrumento_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	instrumento_config_exibe_linha3 TINYINT DEFAULT 0,
	instrumento_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	instrumento_config_exibe_linha4 TINYINT DEFAULT 0,
	instrumento_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	PRIMARY KEY (instrumento_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

