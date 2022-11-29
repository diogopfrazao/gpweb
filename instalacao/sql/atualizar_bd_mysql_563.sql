SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-01';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-01';
UPDATE versao SET versao_bd=563;

ALTER TABLE instrumento_campo ADD COLUMN instrumento_avulso_custo_custo_atual TINYINT(1) DEFAULT 1 AFTER instrumento_avulso_custo_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_valor_atual TINYINT(1) DEFAULT 1 AFTER instrumento_valor;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_valor_atual_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_valor_atual;
UPDATE instrumento_campo SET instrumento_valor_atual_leg='Valor atual do contrato vigente';


ALTER TABLE instrumento ADD COLUMN instrumento_valor_atual DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER instrumento_valor;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('instrumentos','instrumento_valor_atual','Valor Atual',1);