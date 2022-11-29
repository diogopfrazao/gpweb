SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-20';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-20';
UPDATE versao SET versao_bd=421;


ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE beneficio ADD COLUMN beneficio_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE painel_slideshow ADD COLUMN painel_slideshow_aprovado TINYINT(1) DEFAULT 0;


ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_ativo TINYINT(1) DEFAULT 1;


DROP TABLE IF EXISTS plano_acao_item_contato;

CREATE TABLE plano_acao_item_contato (
  plano_acao_item_contato_plano_acao_item INTEGER(100) UNSIGNED NOT NULL,
  plano_acao_item_contato_contato INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_item_contato_plano_acao_item, plano_acao_item_contato_contato),
  KEY plano_acao_item_contato_plano_acao_item (plano_acao_item_contato_plano_acao_item),
  KEY plano_acao_item_contato_contato (plano_acao_item_contato_contato),
  CONSTRAINT plano_acao_item_contato_contato FOREIGN KEY (plano_acao_item_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_contato_plano_acao_item FOREIGN KEY (plano_acao_item_contato_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;