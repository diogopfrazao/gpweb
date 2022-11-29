SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-12';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-12';
UPDATE versao SET versao_bd=420;


ALTER TABLE assinatura_atesta CHANGE assinatura_atesta_viabilidade assinatura_atesta_projeto_viabilidade TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta CHANGE assinatura_atesta_abertura assinatura_atesta_projeto_abertura TINYINT(1) DEFAULT 0;

ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_acao_item TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_beneficio TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_painel_slideshow TINYINT(1) DEFAULT 0;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('paineis_slideshow','painel_slideshow_relacionado','Relacionado',1),
	('planejamento','moeda','Moeda',1),
	('beneficio','moeda','Moeda',1),
	('painel_slideshow','moeda','Moeda',1);


ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_moeda INTEGER(100) UNSIGNED DEFAULT 1; 
ALTER TABLE plano_acao_item ADD KEY plano_acao_item_moeda (plano_acao_item_moeda);
ALTER TABLE plano_acao_item ADD CONSTRAINT plano_acao_item_moeda FOREIGN KEY (plano_acao_item_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE;  

ALTER TABLE plano_gestao ADD COLUMN pg_moeda INTEGER(100) UNSIGNED DEFAULT 1; 
ALTER TABLE plano_gestao ADD KEY pg_moeda (pg_moeda);
ALTER TABLE plano_gestao ADD CONSTRAINT pg_moeda FOREIGN KEY (pg_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE; 






ALTER TABLE plano_acao_item_depts DROP FOREIGN KEY plano_acao_item_depts_fk;
ALTER TABLE plano_acao_item_depts DROP FOREIGN KEY plano_acao_item_depts_fk1;

ALTER TABLE plano_acao_item_depts DROP KEY plano_acao_item_id;
ALTER TABLE plano_acao_item_depts DROP KEY dept_id;



ALTER TABLE plano_acao_item_depts CHANGE plano_acao_item_id plano_acao_item_dept_plano_acao_item INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE plano_acao_item_depts CHANGE dept_id plano_acao_item_dept_dept INTEGER(100) UNSIGNED NOT NULL;

RENAME TABLE plano_acao_item_depts TO plano_acao_item_dept;

ALTER TABLE plano_acao_item_dept ADD KEY plano_acao_item_dept_plano_acao_item (plano_acao_item_dept_plano_acao_item);
ALTER TABLE plano_acao_item_dept ADD KEY plano_acao_item_dept_dept (plano_acao_item_dept_dept);
ALTER TABLE plano_acao_item_dept ADD CONSTRAINT plano_acao_item_dept_dept FOREIGN KEY (plano_acao_item_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_dept ADD CONSTRAINT plano_acao_item_dept_plano_acao_item FOREIGN KEY (plano_acao_item_dept_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_dept INTEGER(100) UNSIGNED DEFAULT NULL; 
ALTER TABLE plano_acao_item ADD KEY plano_acao_item_dept (plano_acao_item_dept);
ALTER TABLE plano_acao_item ADD CONSTRAINT plano_acao_item_dept FOREIGN KEY (plano_acao_item_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE; 

DROP TABLE IF EXISTS plano_acao_item_cia;

CREATE TABLE plano_acao_item_cia (
  plano_acao_item_cia_plano_acao_item INTEGER(100) UNSIGNED NOT NULL,
  plano_acao_item_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_item_cia_plano_acao_item, plano_acao_item_cia_cia),
  KEY plano_acao_item_cia_plano_acao_item (plano_acao_item_cia_plano_acao_item),
  KEY plano_acao_item_cia_cia (plano_acao_item_cia_cia),
  CONSTRAINT plano_acao_item_cia_cia FOREIGN KEY (plano_acao_item_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_cia_plano_acao_item FOREIGN KEY (plano_acao_item_cia_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
