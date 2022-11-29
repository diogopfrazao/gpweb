SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-09-28';
UPDATE versao SET ultima_atualizacao_codigo='2020-09-28';
UPDATE versao SET versao_bd=584;


ALTER TABLE tr_custo DROP FOREIGN KEY tr_custo_tr;
ALTER TABLE tr_custo ADD CONSTRAINT tr_custo_tr FOREIGN KEY (tr_custo_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE;
DELETE FROM tr_custo WHERE tr_custo_tr IS NULL;

ALTER TABLE os_custo DROP FOREIGN KEY os_custo_os;
ALTER TABLE os_custo ADD CONSTRAINT os_custo_os FOREIGN KEY (os_custo_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE;
DELETE FROM os_custo WHERE os_custo_os IS NULL;

ALTER TABLE instrumento_custo DROP FOREIGN KEY instrumento_custo_instrumento;
ALTER TABLE instrumento_custo ADD CONSTRAINT instrumento_custo_instrumento FOREIGN KEY (instrumento_custo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;
DELETE FROM instrumento_custo WHERE instrumento_custo_instrumento IS NULL;