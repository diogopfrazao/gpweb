SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-05-28';
UPDATE versao SET ultima_atualizacao_codigo='2020-05-28';
UPDATE versao SET versao_bd=562;


ALTER TABLE instrumento_avulso_custo ADD COLUMN instrumento_avulso_custo_custo_atual DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER instrumento_avulso_custo_custo;