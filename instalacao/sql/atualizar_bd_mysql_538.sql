SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-16';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-16';
UPDATE versao SET versao_bd=538;

ALTER TABLE instrumento ADD COLUMN instrumento_situacao_atual TEXT AFTER instrumento_resultado_esperado;

ALTER TABLE instrumento_campo ADD COLUMN instrumento_situacao_atual TINYINT(1) DEFAULT 1 AFTER instrumento_resultado_esperado_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_situacao_atual_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_situacao_atual;

UPDATE instrumento_campo SET instrumento_situacao_atual=1, instrumento_situacao_atual_leg='Situação atual';

ALTER TABLE instrumento ADD COLUMN instrumento_fim_contrato DATE DEFAULT NULL AFTER instrumento_valor_contrapartida;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_fim_contrato TINYINT(1) DEFAULT 1 AFTER instrumento_valor_contrapartida_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_fim_contrato_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_fim_contrato;
UPDATE instrumento_campo SET instrumento_fim_contrato=1, instrumento_fim_contrato_leg='Término';