SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-06';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-06';
UPDATE versao SET versao_bd=534;


ALTER TABLE instrumento_avulso_custo ADD COLUMN instrumento_avulso_custo_acrescimo DECIMAL(20,5) DEFAULT NULL;
ALTER TABLE instrumento_avulso_custo DROP COLUMN instrumento_avulso_custo_siag;
