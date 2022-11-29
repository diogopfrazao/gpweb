SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-19';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-19';
UPDATE versao SET versao_bd=567;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('trs','tr_ultimo_registro','Data do último registro de ocorrência',0),
	('oss','os_ultimo_registro','Data do último registro de ocorrência',0),
	('instrumentos','instrumento_ultimo_registro','Data do último registro de ocorrência',0);