SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-07-23';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-23';
UPDATE versao SET versao_bd=434;


ALTER TABLE agenda DROP COLUMN agenda_privado;
ALTER TABLE agenda DROP COLUMN agenda_modificacao;

ALTER TABLE agenda ADD COLUMN agenda_recorrencia_pai INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda ADD KEY agenda_recorrencia_pai (agenda_recorrencia_pai);
ALTER TABLE agenda ADD CONSTRAINT agenda_recorrencia_pai FOREIGN KEY (agenda_recorrencia_pai) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE agenda ADD COLUMN agenda_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda ADD KEY agenda_principal_indicador (agenda_principal_indicador);
ALTER TABLE agenda ADD CONSTRAINT agenda_principal_indicador FOREIGN KEY (agenda_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE agenda ADD COLUMN agenda_oque TEXT;
ALTER TABLE agenda ADD COLUMN agenda_onde TEXT;
ALTER TABLE agenda ADD COLUMN agenda_quando TEXT;
ALTER TABLE agenda ADD COLUMN agenda_como TEXT;
ALTER TABLE agenda ADD COLUMN agenda_porque TEXT;
ALTER TABLE agenda ADD COLUMN agenda_quanto TEXT;
ALTER TABLE agenda ADD COLUMN agenda_quem TEXT;
ALTER TABLE agenda ADD COLUMN agenda_url VARCHAR(255) DEFAULT NULL;
ALTER TABLE agenda ADD COLUMN agenda_icone VARCHAR(20) DEFAULT 'obj/agenda';
ALTER TABLE agenda ADD COLUMN agenda_ativo INTEGER(1) DEFAULT 1;
ALTER TABLE agenda ADD COLUMN agenda_uid VARCHAR(255) DEFAULT NULL;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('agenda','agenda_como','Como',1),
	('agenda','agenda_descricao','Descrição',1),
	('agenda','agenda_oque','O que',1),
	('agenda','agenda_onde','Onde',1),
	('agenda','agenda_porque','Porque',1),
	('agenda','agenda_quando','Quando',1),
	('agenda','agenda_quanto','Quanto',1),
	('agenda','agenda_quem','Quem',1);