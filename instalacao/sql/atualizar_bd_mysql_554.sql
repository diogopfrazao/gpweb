SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-04-02';
UPDATE versao SET ultima_atualizacao_codigo='2020-04-02';
UPDATE versao SET versao_bd=554;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('instrumentos','instrumento_total_ne','Total de Empenhos',0),
	('instrumentos','instrumento_total_ns','Total de Liquidações',0),
	('instrumentos','instrumento_total_ob','Total de Pagamentos',0),
	('instrumentos','instrumento_total_extra','Total de Planilha',0),
	('instrumentos','instrumento_total_os','Total de OS',0);