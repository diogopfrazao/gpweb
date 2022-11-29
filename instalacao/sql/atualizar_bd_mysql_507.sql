SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.18';
UPDATE versao SET ultima_atualizacao_bd='2018-08-16';
UPDATE versao SET ultima_atualizacao_codigo='2018-08-16';
UPDATE versao SET versao_bd=507;

DELETE FROM favorito_trava WHERE favorito_trava_ssti IS NOT NULL;

ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ssti_estrategia TINYINT(1) DEFAULT 0;
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ssti_processo TINYINT(1) DEFAULT 0;
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ssti_coagd TINYINT(1) DEFAULT 0;
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ssti_coges TINYINT(1) DEFAULT 0;
ALTER TABLE favorito_trava ADD COLUMN favorito_trava_ssti_cport TINYINT(1) DEFAULT 0;