SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-04-14';
UPDATE versao SET ultima_atualizacao_codigo='2020-04-14';
UPDATE versao SET versao_bd=555;


DROP TABLE IF EXISTS os_modificacao;

CREATE TABLE os_modificacao (
  os_modificacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  os_modificacao_os INTEGER(100) UNSIGNED DEFAULT NULL,
  os_modificacao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  os_modificacao_tipo INTEGER(10) UNSIGNED DEFAULT 0,
  os_modificacao_data DATETIME DEFAULT NULL,
  os_modificacao_descricao MEDIUMTEXT,
  PRIMARY KEY (os_modificacao_id),
  KEY os_modificacao_os (os_modificacao_os),
  KEY os_modificacao_usuario (os_modificacao_usuario),
  CONSTRAINT os_modificacao_usuario FOREIGN KEY (os_modificacao_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT os_modificacao_os FOREIGN KEY (os_modificacao_os) REFERENCES os (os_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('OSmodificacaoTipo','Cancelada','0',NULL),
	('OSmodificacaoTipo','Cancelada parcialmente','1',NULL),
	('OSmodificacaoTipo','Data Retificada','2',NULL),
	('OSmodificacaoTipo','Valor Retificado','3',NULL),
	('OSmodificacaoTipo','Item Retificado','4',NULL);
	

ALTER TABLE os ADD COLUMN	os_fiscal_titular INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE os ADD COLUMN	os_fiscal_substituto INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE os ADD KEY os_fiscal_titular (os_fiscal_titular);
ALTER TABLE os ADD KEY os_fiscal_substituto (os_fiscal_substituto);
ALTER TABLE os ADD CONSTRAINT os_fiscal_titular FOREIGN KEY (os_fiscal_titular) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE os ADD CONSTRAINT os_fiscal_substituto FOREIGN KEY (os_fiscal_substituto) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE os ADD COLUMN	os_telefone VARCHAR(15) DEFAULT NULL;