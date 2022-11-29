SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-09';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-09';
UPDATE versao SET versao_bd=535;

ALTER TABLE instrumento ADD COLUMN instrumento_supressao DECIMAL(20,5) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento CHANGE instrumento_acrescimo instrumento_acrescimo DECIMAL(20,5) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_casa_significativa INTEGER UNSIGNED DEFAULT 2;