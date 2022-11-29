SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-07-25';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-25';
UPDATE versao SET versao_bd=435;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('atas','ata_participantes','Participantes',1),
	('atas','ata_participantes_externos','Participantes externos',0);
	
	
DROP TABLE IF EXISTS ata_participante;