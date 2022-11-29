SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-28';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-28';
UPDATE versao SET versao_bd=569;

ALTER TABLE instrumento_custo ADD COLUMN instrumento_custo_tr INTEGER(100) UNSIGNED DEFAULT NULL AFTER instrumento_custo_tarefa;
ALTER TABLE instrumento_custo ADD KEY instrumento_custo_tr (instrumento_custo_tr);
ALTER TABLE instrumento_custo ADD CONSTRAINT instrumento_custo_tr FOREIGN KEY (instrumento_custo_tr) REFERENCES tr_avulso_custo (tr_avulso_custo_id) ON DELETE CASCADE ON UPDATE CASCADE;
