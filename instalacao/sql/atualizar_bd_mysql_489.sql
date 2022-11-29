SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-05-21';
UPDATE versao SET ultima_atualizacao_codigo='2018-05-21';
UPDATE versao SET versao_bd=489;

ALTER TABLE laudo ADD COLUMN laudo_sistema_principal INTEGER (10) DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_sistema_alternativo MEDIUMTEXT;
ALTER TABLE laudo ADD COLUMN laudo_equipe INTEGER (10) DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_pontuacao DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE laudo ADD COLUMN laudo_ranking_data DATETIME;

ALTER TABLE nd MODIFY nd_pai INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE nd ADD KEY nd_pai(nd_pai);
ALTER TABLE nd ADD CONSTRAINT nd_pai FOREIGN KEY (nd_pai) REFERENCES nd (nd_id) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM nd WHERE nd_texto IS NULL;















	