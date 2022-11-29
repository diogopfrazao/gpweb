SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-05-13';
UPDATE versao SET ultima_atualizacao_codigo='2020-05-13';
UPDATE versao SET versao_bd=557;
	
ALTER TABLE instrumento ADD COLUMN instrumento_entidade_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_entidade_codigo TINYINT(1) DEFAULT 1;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_entidade_codigo_leg VARCHAR(50) DEFAULT NULL;
UPDATE instrumento_campo SET instrumento_entidade_codigo=1;
UPDATE instrumento_campo SET instrumento_entidade_codigo_leg='Código do Credor no FIPLAN';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('instrumentos','instrumento_entidade_codigo','Código do Credor',0);