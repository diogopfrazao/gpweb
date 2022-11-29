SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-02-20';
UPDATE versao SET ultima_atualizacao_codigo='2020-02-20';
UPDATE versao SET versao_bd=548;

ALTER TABLE instrumento ADD COLUMN instrumento_garantia_contratual_modalidade VARCHAR(255) DEFAULT NULL AFTER instrumento_data_termino;
ALTER TABLE instrumento ADD COLUMN instrumento_garantia_contratual_percentual decimal(20,5) DEFAULT NULL AFTER instrumento_garantia_contratual_modalidade;
ALTER TABLE instrumento ADD COLUMN instrumento_garantia_contratual_vencimento DATE DEFAULT NULL AFTER instrumento_garantia_contratual_percentual;


ALTER TABLE instrumento_campo ADD COLUMN instrumento_garantia_contratual TINYINT(1) DEFAULT 1 AFTER instrumento_data_publicacao_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_garantia_contratual_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_garantia_contratual;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_garantia_contratual_modalidade TINYINT(1) DEFAULT 1 AFTER instrumento_garantia_contratual_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_garantia_contratual_modalidade_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_garantia_contratual_modalidade;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_garantia_contratual_percentual TINYINT(1) DEFAULT 1 AFTER instrumento_garantia_contratual_modalidade_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_garantia_contratual_vencimento TINYINT(1) DEFAULT 1 AFTER instrumento_garantia_contratual_percentual;

UPDATE instrumento_campo SET instrumento_garantia_contratual_leg='Garantia contratual';
UPDATE instrumento_campo SET instrumento_garantia_contratual_modalidade_leg='Modalidade escolhida';
UPDATE instrumento_campo SET instrumento_garantia_contratual=1, instrumento_garantia_contratual_modalidade=1, instrumento_garantia_contratual_percentual=1, instrumento_garantia_contratual_vencimento=1 WHERE instrumento_campo_id=1;