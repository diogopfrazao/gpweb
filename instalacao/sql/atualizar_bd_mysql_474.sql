SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-03-13';
UPDATE versao SET ultima_atualizacao_codigo='2018-03-13';
UPDATE versao SET versao_bd=474;

ALTER TABLE laudo ADD COLUMN laudo_ranking_sugestao INTEGER(10) DEFAULT 0;
ALTER TABLE laudo ADD COLUMN laudo_ranking_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE laudo ADD KEY laudo_ranking_dept (laudo_ranking_dept);
ALTER TABLE laudo ADD CONSTRAINT laudo_ranking_dept FOREIGN KEY (laudo_ranking_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE;