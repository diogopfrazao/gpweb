SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-02';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-02';
UPDATE versao SET versao_bd=532;

ALTER TABLE tr_fornecedor ADD COLUMN tr_fornecedor_lote INTEGER(10) DEFAULT NULL;
ALTER TABLE tr_fornecedor DROP COLUMN tr_fornecedor_data_encerramento;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('tr_modalidade','','',NULL),
	('tr_modalidade','PREG�O PRESENCIAL','1',NULL),
	('tr_modalidade','PREG�O ELETR�NICO','2',NULL),
	('tr_modalidade','COMPRA DIRETA','3',NULL),
	('tr_modalidade','ADES�O CARONA','4',NULL),
	('tr_modalidade','PEDIDO DE UTILIZA��O/SEGES','5',NULL),
	('tr_modalidade','INEXIGIBILIDADE','6',NULL),
	('tr_modalidade','DISPENSA DE LICITA��O','7',NULL),
	('tr_modalidade','CONCORR�NCIA P�BLICA','8',NULL),
	('tr_modalidade','TOMADA DE PRE�O','9',NULL),
	('tr_modalidade','CARTA CONVITE','10',NULL);