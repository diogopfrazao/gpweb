SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-16';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-16';
UPDATE versao SET versao_bd=537;

ALTER TABLE instrumento_campo ADD COLUMN instrumento_cor TINYINT(1) DEFAULT 1 AFTER instrumento_porcentagem_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_cor_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_cor;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_ativo TINYINT(1) DEFAULT 1 AFTER instrumento_cor_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_ativo_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_ativo;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_acesso TINYINT(1) DEFAULT 1 AFTER instrumento_ativo_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_principal_indicador TINYINT(1) DEFAULT 1 AFTER instrumento_acesso;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_principal_indicador_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_principal_indicador;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_aprovado TINYINT(1) DEFAULT 1 AFTER instrumento_principal_indicador_leg;
ALTER TABLE instrumento_campo ADD COLUMN instrumento_aprovado_leg VARCHAR(50) DEFAULT NULL AFTER instrumento_aprovado;

UPDATE instrumento_campo SET instrumento_cor=1, instrumento_cor_leg='Cor', instrumento_ativo=1, instrumento_ativo_leg='Ativo', instrumento_acesso=1, instrumento_principal_indicador=1, instrumento_principal_indicador_leg='Principal indicador', instrumento_aprovado=1, instrumento_aprovado_leg='Aprovado';
