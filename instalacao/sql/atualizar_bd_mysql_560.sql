SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-05-19';
UPDATE versao SET ultima_atualizacao_codigo='2020-05-19';
UPDATE versao SET versao_bd=560;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('trs','tr_total_ne','Total de Empenhos',0),
	('trs','tr_total_ns','Total de Liquidações',0),
	('trs','tr_total_ob','Total de Pagamentos',0),
	('trs','tr_total_extra','Total de Planilha',0),
	('trs','tr_total_ne_ano','Total de Empenhos (ano atual)',0),
	('trs','tr_total_ns_ano','Total de Liquidações (ano atual)',0),
	('trs','tr_total_ob_ano','Total de Pagamentos (ano atual)',0),
	('trs','tr_total_extra_ano','Total de Planilha (ano atual)',0),
	('oss','os_total_ne','Total de Empenhos',0),
	('oss','os_total_ns','Total de Liquidações',0),
	('oss','os_total_ob','Total de Pagamentos',0),
	('oss','os_total_extra','Total de Planilha',0),
	('oss','os_total_ne_ano','Total de Empenhos (ano atual)',0),
	('oss','os_total_ns_ano','Total de Liquidações (ano atual)',0),
	('oss','os_total_ob_ano','Total de Pagamentos (ano atual)',0),
	('oss','os_total_extra_ano','Total de Planilha (ano atual)',0),
	('instrumentos','instrumento_total_ne_ano','Total de Empenhos (ano atual)',0),
	('instrumentos','instrumento_total_ns_ano','Total de Liquidações (ano atual)',0),
	('instrumentos','instrumento_total_ob_ano','Total de Pagamentos (ano atual)',0),
	('instrumentos','instrumento_total_extra_ano','Total de Planilha (ano atual)',0),
	('instrumentos','instrumento_total_os_ano','Total de OS (ano atual)',0);
	
	
DELETE FROM campo_formulario WHERE campo_formulario_tipo='oss' AND campo_formulario_campo='os_nome';
	



