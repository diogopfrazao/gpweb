SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.02';
UPDATE versao SET ultima_atualizacao_bd='2017-03-27';
UPDATE versao SET ultima_atualizacao_codigo='2017-03-27';
UPDATE versao SET versao_bd=397;


ALTER TABLE favorito_trava ADD COLUMN favorito_trava_aviso TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_aviso TINYINT(1) DEFAULT 0;
ALTER TABLE assinatura_atesta ADD COLUMN assinatura_atesta_aviso TINYINT(1) DEFAULT 0;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('avisos','aviso_cor','Cor',1),
	('avisos','aviso_cia','Organiza��o respons�vel',0),
	('avisos','aviso_cias','Organiza��es envolvidas',0),
	('avisos','aviso_dept','Se��o respons�vel',1),
	('avisos','aviso_depts','Se��es envolvidas',0),
	('avisos','aviso_usuario','Respons�vel',1),	
	('avisos','aviso_para_cias','Organiza��es avisadas',0),
	('avisos','aviso_para_depts','Se��es avisadas',1),
	('avisos','aviso_designados','Designados',0),	
	('avisos','aviso_repetitivo','Repetitivo',1),	
	('avisos','aviso_inicio','In�cio',1),
	('avisos','aviso_fim','T�rmino',1),
	('avisos','aviso_aprovado','Aprovado',1),
	('avisos','aviso_descricao','Texto',0),
	('avisos','aviso_descricao_icone','Texto (�cone)',0),
	('trs','tr_cor','Cor',1),
	('trs','tr_cia','Organiza��o respons�vel',0),
	('trs','tr_cias','Organiza��es envolvidas',0),
	('trs','tr_dept','Se��o respons�vel',1),
	('trs','tr_depts','Se��es envolvidas',0),
	('trs','tr_responsavel','Respons�vel',1),	
	('trs','tr_designados','Designados',0),	
	('trs','tr_aprovado','Aprovado',1),
	('trs','tr_relacionado','Relacionado',1),
	('trs','tr_numero','N�mero',1),
	('trs','tr_data','Data de cria��o',0),
	('trs','tr_acao','Componente/A��o',0),
	('trs','tr_valor','Valor',0);
	
	
ALTER TABLE tr ADD COLUMN tr_data DATE DEFAULT NULL;