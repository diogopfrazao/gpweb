SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.10';
UPDATE versao SET ultima_atualizacao_bd='2017-10-19';
UPDATE versao SET ultima_atualizacao_codigo='2017-10-19';
UPDATE versao SET versao_bd=453;


ALTER TABLE licao_usuarios DROP FOREIGN KEY licao_usuarios_fk;
ALTER TABLE licao_usuarios DROP FOREIGN KEY licao_usuarios_fk1;
ALTER TABLE licao_usuarios DROP KEY licao_id;
ALTER TABLE licao_usuarios DROP KEY usuario_id;
ALTER TABLE licao_usuarios CHANGE licao_id licao_usuario_licao INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE licao_usuarios CHANGE usuario_id licao_usuario_usuario INTEGER(100) UNSIGNED NOT NULL;
RENAME TABLE licao_usuarios TO licao_usuario;
ALTER TABLE licao_usuario ADD KEY licao_usuario_licao (licao_usuario_licao);
ALTER TABLE licao_usuario ADD KEY licao_usuario_usuario (licao_usuario_usuario);
ALTER TABLE licao_usuario ADD CONSTRAINT licao_usuario_usuario FOREIGN KEY (licao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE licao_usuario ADD CONSTRAINT licao_usuario_licao FOREIGN KEY (licao_usuario_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE;
