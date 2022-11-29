SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-07-03';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-03';
UPDATE versao SET versao_bd=429;

ALTER TABLE usuariogrupo DROP FOREIGN KEY usuariogrupo_fk;
ALTER TABLE usuariogrupo DROP FOREIGN KEY usuariogrupo_fk1;

ALTER TABLE usuariogrupo DROP KEY usuario_id;
ALTER TABLE usuariogrupo DROP KEY grupo_id;

ALTER TABLE usuariogrupo CHANGE usuario_id grupo_usuario_usuario INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE usuariogrupo CHANGE grupo_id grupo_usuario_grupo INTEGER(100) UNSIGNED NOT NULL;

ALTER TABLE usuariogrupo ADD KEY grupo_usuario_usuario (grupo_usuario_usuario);
ALTER TABLE usuariogrupo ADD KEY grupo_usuario_grupo (grupo_usuario_grupo);
ALTER TABLE usuariogrupo ADD CONSTRAINT grupo_usuario_grupo FOREIGN KEY (grupo_usuario_grupo) REFERENCES grupo (grupo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuariogrupo ADD CONSTRAINT grupo_usuario_usuario FOREIGN KEY (grupo_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;

RENAME TABLE usuariogrupo TO grupo_usuario;











ALTER TABLE grupo_permissao DROP FOREIGN KEY grupo_permissao_fk;
ALTER TABLE grupo_permissao DROP FOREIGN KEY grupo_permissao_fk1;

ALTER TABLE grupo_permissao DROP KEY usuario_id;
ALTER TABLE grupo_permissao DROP KEY grupo_id;

ALTER TABLE grupo_permissao CHANGE usuario_id grupo_permissao_usuario INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE grupo_permissao CHANGE grupo_id grupo_permissao_grupo INTEGER(100) UNSIGNED NOT NULL;

ALTER TABLE grupo_permissao ADD KEY grupo_permissao_usuario (grupo_permissao_usuario);
ALTER TABLE grupo_permissao ADD KEY grupo_permissao_grupo (grupo_permissao_grupo);
ALTER TABLE grupo_permissao ADD CONSTRAINT grupo_permissao_grupo FOREIGN KEY (grupo_permissao_grupo) REFERENCES grupo (grupo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo_permissao ADD CONSTRAINT grupo_permissao_usuario FOREIGN KEY (grupo_permissao_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE grupo_permissao ADD PRIMARY KEY (grupo_permissao_grupo, grupo_permissao_usuario);