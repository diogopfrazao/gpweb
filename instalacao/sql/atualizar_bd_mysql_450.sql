SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.10';
UPDATE versao SET ultima_atualizacao_bd='2017-10-02';
UPDATE versao SET ultima_atualizacao_codigo='2017-10-02';
UPDATE versao SET versao_bd=450;


ALTER TABLE plano_acao_contatos DROP FOREIGN KEY plano_acao_contatos_fk;
ALTER TABLE plano_acao_contatos DROP FOREIGN KEY plano_acao_contatos_fk1;
ALTER TABLE plano_acao_contatos DROP KEY plano_acao_id;
ALTER TABLE plano_acao_contatos DROP KEY contato_id;
ALTER TABLE plano_acao_contatos CHANGE plano_acao_id plano_acao_contato_acao INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE plano_acao_contatos CHANGE contato_id plano_acao_contato_contato INTEGER(100) UNSIGNED NOT NULL;
RENAME TABLE plano_acao_contatos TO plano_acao_contato;
ALTER TABLE plano_acao_contato ADD KEY plano_acao_contato_acao (plano_acao_contato_acao);
ALTER TABLE plano_acao_contato ADD KEY plano_acao_contato_contato (plano_acao_contato_contato);
ALTER TABLE plano_acao_contato ADD CONSTRAINT plano_acao_contato_contato FOREIGN KEY (plano_acao_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_contato ADD CONSTRAINT plano_acao_contato_acao FOREIGN KEY (plano_acao_contato_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE plano_acao_depts DROP FOREIGN KEY plano_acao_depts_fk;
ALTER TABLE plano_acao_depts DROP FOREIGN KEY plano_acao_depts_fk1;
ALTER TABLE plano_acao_depts DROP KEY plano_acao_id;
ALTER TABLE plano_acao_depts DROP KEY dept_id;
ALTER TABLE plano_acao_depts CHANGE plano_acao_id plano_acao_dept_acao INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE plano_acao_depts CHANGE dept_id plano_acao_dept_dept INTEGER(100) UNSIGNED NOT NULL;
RENAME TABLE plano_acao_depts TO plano_acao_dept;
ALTER TABLE plano_acao_dept ADD KEY plano_acao_dept_acao (plano_acao_dept_acao);
ALTER TABLE plano_acao_dept ADD KEY plano_acao_dept_dept (plano_acao_dept_dept);
ALTER TABLE plano_acao_dept ADD CONSTRAINT plano_acao_dept_dept FOREIGN KEY (plano_acao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_dept ADD CONSTRAINT plano_acao_dept_acao FOREIGN KEY (plano_acao_dept_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE plano_acao_usuarios DROP FOREIGN KEY plano_acao_usuarios_fk;
ALTER TABLE plano_acao_usuarios DROP FOREIGN KEY plano_acao_usuarios_fk1;
ALTER TABLE plano_acao_usuarios DROP KEY plano_acao_id;
ALTER TABLE plano_acao_usuarios DROP KEY usuario_id;
ALTER TABLE plano_acao_usuarios CHANGE plano_acao_id plano_acao_usuario_acao INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE plano_acao_usuarios CHANGE usuario_id plano_acao_usuario_usuario INTEGER(100) UNSIGNED NOT NULL;
RENAME TABLE plano_acao_usuarios TO plano_acao_usuario;
ALTER TABLE plano_acao_usuario ADD KEY plano_acao_usuario_acao (plano_acao_usuario_acao);
ALTER TABLE plano_acao_usuario ADD KEY plano_acao_usuario_usuario (plano_acao_usuario_usuario);
ALTER TABLE plano_acao_usuario ADD CONSTRAINT plano_acao_usuario_usuario FOREIGN KEY (plano_acao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_usuario ADD CONSTRAINT plano_acao_usuario_acao FOREIGN KEY (plano_acao_usuario_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE plano_acao_item_designados DROP FOREIGN KEY plano_acao_item_designados_fk;
ALTER TABLE plano_acao_item_designados DROP FOREIGN KEY plano_acao_item_designados_fk1;
ALTER TABLE plano_acao_item_designados DROP KEY plano_acao_item_id;
ALTER TABLE plano_acao_item_designados DROP KEY usuario_id;
ALTER TABLE plano_acao_item_designados CHANGE plano_acao_item_id plano_acao_item_usuario_item INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE plano_acao_item_designados CHANGE usuario_id plano_acao_item_usuario_usuario INTEGER(100) UNSIGNED NOT NULL;
RENAME TABLE plano_acao_item_designados TO plano_acao_item_usuario;
ALTER TABLE plano_acao_item_usuario ADD KEY plano_acao_item_usuario_item (plano_acao_item_usuario_item);
ALTER TABLE plano_acao_item_usuario ADD KEY plano_acao_item_usuario_usuario (plano_acao_item_usuario_usuario);
ALTER TABLE plano_acao_item_usuario ADD CONSTRAINT plano_acao_item_usuario_usuario FOREIGN KEY (plano_acao_item_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_usuario ADD CONSTRAINT plano_acao_item_usuario_item FOREIGN KEY (plano_acao_item_usuario_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

RENAME TABLE evento_arquivos TO evento_arquivo;

ALTER TABLE evento_depts DROP FOREIGN KEY evento_depts_fk;
ALTER TABLE evento_depts DROP FOREIGN KEY evento_depts_fk1;
ALTER TABLE evento_depts DROP KEY evento_id;
ALTER TABLE evento_depts DROP KEY dept_id;
ALTER TABLE evento_depts CHANGE evento_id evento_dept_evento INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE evento_depts CHANGE dept_id evento_dept_dept INTEGER(100) UNSIGNED NOT NULL;
RENAME TABLE evento_depts TO evento_dept;
ALTER TABLE evento_dept ADD KEY evento_dept_evento (evento_dept_evento);
ALTER TABLE evento_dept ADD KEY evento_dept_dept (evento_dept_dept);
ALTER TABLE evento_dept ADD CONSTRAINT evento_dept_dept FOREIGN KEY (evento_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_dept ADD CONSTRAINT evento_dept_evento FOREIGN KEY (evento_dept_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;



DROP TABLE IF EXISTS evento_usuario;

CREATE TABLE evento_usuario (
  evento_usuario_evento INTEGER(100) UNSIGNED NOT NULL,
  evento_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (evento_usuario_evento, evento_usuario_usuario),
  KEY evento_usuario_evento (evento_usuario_evento),
  KEY evento_usuario_usuario (evento_usuario_usuario),
  CONSTRAINT evento_usuario_evento FOREIGN KEY (evento_usuario_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_usuario_usuario FOREIGN KEY (evento_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


ALTER TABLE evento_usuarios DROP FOREIGN KEY evento_usuarios_fk;
ALTER TABLE evento_usuarios DROP FOREIGN KEY evento_usuarios_fk1;
ALTER TABLE evento_usuarios DROP KEY uek2;
ALTER TABLE evento_usuarios DROP KEY evento_id;
ALTER TABLE evento_usuarios DROP KEY usuario_id;
ALTER TABLE evento_usuarios CHANGE usuario_id evento_participante_usuario INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE evento_usuarios CHANGE evento_id evento_participante_evento INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE evento_usuarios CHANGE aceito evento_participante_aceito TINYINT(1) DEFAULT 0;
ALTER TABLE evento_usuarios CHANGE data evento_participante_data DATETIME DEFAULT NULL;
ALTER TABLE evento_usuarios CHANGE duracao evento_participante_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE evento_usuarios CHANGE percentual evento_participante_percentual INTEGER(3) UNSIGNED DEFAULT 100;
RENAME TABLE evento_usuarios TO evento_participante;
ALTER TABLE evento_participante ADD KEY evento_participante_usuario (evento_participante_usuario);
ALTER TABLE evento_participante ADD KEY evento_participante_evento (evento_participante_evento);
ALTER TABLE evento_participante ADD CONSTRAINT evento_participante_usuario FOREIGN KEY (evento_participante_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_participante ADD CONSTRAINT evento_participante_evento FOREIGN KEY (evento_participante_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_evento_usuarios DROP FOREIGN KEY baseline_evento_usuarios;
ALTER TABLE baseline_evento_usuarios CHANGE usuario_id evento_participante_usuario INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE baseline_evento_usuarios CHANGE evento_id evento_participante_evento INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE baseline_evento_usuarios CHANGE aceito evento_participante_aceito TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_evento_usuarios CHANGE data evento_participante_data DATETIME DEFAULT NULL;
ALTER TABLE baseline_evento_usuarios CHANGE duracao evento_participante_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_evento_usuarios CHANGE percentual evento_participante_percentual INTEGER(3) UNSIGNED DEFAULT 100;
RENAME TABLE baseline_evento_usuarios TO baseline_evento_participante;
ALTER TABLE baseline_evento_participante ADD CONSTRAINT baseline_evento_participante FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;

