SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-05-11';
UPDATE versao SET ultima_atualizacao_codigo='2018-05-11';
UPDATE versao SET versao_bd=486;


CALL PROC_DROP_FOREIGN_KEY('tr_custo','tr_custo_avulso');
ALTER TABLE tr_custo ADD CONSTRAINT tr_custo_avulso FOREIGN KEY (tr_custo_avulso) REFERENCES tr_avulso_custo (tr_avulso_custo_id) ON DELETE CASCADE ON UPDATE CASCADE;