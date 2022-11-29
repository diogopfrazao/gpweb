SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.09';
UPDATE versao SET ultima_atualizacao_bd='2017-09-02';
UPDATE versao SET ultima_atualizacao_codigo='2017-09-02';
UPDATE versao SET versao_bd=448;

DELETE FROM sisvalores WHERE sisvalor_titulo='NivelAcesso';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('NivelAcesso','Público','0',NULL),
	('NivelAcesso','Protegido I','1',NULL),
	('NivelAcesso','Protegido II','5',NULL),
	('NivelAcesso','Protegido III','4',NULL),
	('NivelAcesso','Participantes I','2',NULL),
	('NivelAcesso','Participantes II','6',NULL),
	('NivelAcesso','Participantes III','3',NULL);