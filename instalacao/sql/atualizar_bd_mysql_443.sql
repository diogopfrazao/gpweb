SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-08-13';
UPDATE versao SET ultima_atualizacao_codigo='2017-08-13';
UPDATE versao SET versao_bd=443;



UPDATE instrumento SET instrumento_situacao=5 WHERE instrumento_situacao=7;

DELETE FROM sisvalores WHERE sisvalor_titulo='SituacaoInstrumento' AND sisvalor_valor_id='7';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('SituacaoInstrumentoCor','da908c','1',NULL),
	('SituacaoInstrumentoCor','a0cc9d','2',NULL),
	('SituacaoInstrumentoCor','c5eff4','3',NULL),
	('SituacaoInstrumentoCor','7a73d9','4',NULL),
	('SituacaoInstrumentoCor','e4ee8b','5',NULL),
	('SituacaoInstrumentoCor','e3c383','6',NULL);