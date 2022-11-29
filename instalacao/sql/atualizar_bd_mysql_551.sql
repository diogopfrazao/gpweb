SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-03-04';
UPDATE versao SET ultima_atualizacao_codigo='2020-03-04';
UPDATE versao SET versao_bd=551;

DELETE FROM instrumento_config;
INSERT INTO instrumento_config (instrumento_config_id, instrumento_config_exibe_funcao, instrumento_config_exibe_tipo_parecer, instrumento_config_exibe_linha2, instrumento_config_linha2_legenda, instrumento_config_exibe_linha3, instrumento_config_linha3_legenda, instrumento_config_exibe_linha4, instrumento_config_linha4_legenda) VALUES
  (1,1,1,1,'CPF',1,'Função',1,'Organização');
  
  
  
DROP TABLE IF EXISTS os_config;

CREATE TABLE os_config (
	os_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	os_config_exibe_funcao TINYINT DEFAULT 0,
	os_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	os_config_exibe_linha2 TINYINT DEFAULT 0,
	os_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	os_config_exibe_linha3 TINYINT DEFAULT 0,
	os_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	os_config_exibe_linha4 TINYINT DEFAULT 0,
	os_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	PRIMARY KEY (os_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;  

INSERT INTO os_config (os_config_id, os_config_exibe_funcao, os_config_exibe_tipo_parecer, os_config_exibe_linha2, os_config_linha2_legenda, os_config_exibe_linha3, os_config_linha3_legenda, os_config_exibe_linha4, os_config_linha4_legenda) VALUES
  (1,1,1,1,'CPF',1,'Função',1,'Organização');
