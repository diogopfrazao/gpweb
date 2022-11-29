SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-04-14';
UPDATE versao SET ultima_atualizacao_codigo='2020-04-14';
UPDATE versao SET versao_bd=556;
	
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_nc TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_nc TINYINT(1) DEFAULT 0;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('nes','NUMR_EMP','Nota de Empenho', 1),
	('nes','CD_UNIDADE_ORCAMENTARIA','Unidade Orçamentária', 1),
	('nes','DS_PAOE','Projeto/Atividade', 1),
	('nes','SITUACAO_EMP','Situação', 1),
	('nes','VALR_EMP','Valor', 1),
	('nes','DTEMISSAO','Data de Emissão', 0),
	('nes','CONE','Número', 0),
	('nes','COITEMNE','Espécie', 0),
	('nes','COUGEMIT','Emitente', 0),
	('nes','COFAVORECIDO','Credor', 0),
	('nes','VLCAMBIO','Taxa de câmbio', 0),
	('nes','DSOBSNE','Observação', 0),
	('nes','COUOEMIT','Classificação', 0),
	('nes','COTPNE','Tipo', 0),
	('nes','COMODLICITACAO','Modalidade de Licitação', 0),
	('nes','COAMPAROLEGAL','Amparo', 0),
	('nes','COINCISO','Inciso', 0),
	('nes','NUPROCESSO','Processo', 0),
	('nes','COUFBENEF','UF/Municípo Beneficiado', 0),
	('nes','COORIGEMMATERIAL','Origem do Material', 0),
	('nes','DSREFDISPENSA','Referência da Dispensa', 0),
	('nes','NUORIGINAL','Número Original', 0);
	
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ne TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_ne TINYINT(1) DEFAULT 0;

	
INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0),
	('nss','ns_XXXX','XXXXX', 0);
	
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ns TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_ns TINYINT(1) DEFAULT 0;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0),
	('obs','ob_XXXX','XXXXX', 0);
	
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ob TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_ob TINYINT(1) DEFAULT 0;
