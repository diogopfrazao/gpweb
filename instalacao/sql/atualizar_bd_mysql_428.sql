SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-07-03';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-03';
UPDATE versao SET versao_bd=428;

ALTER TABLE favorito ADD COLUMN favorito_plano_acao_item TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_beneficio TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_painel_slideshow TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_projeto_viabilidade TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_projeto_abertura TINYINT(1) DEFAULT 0;
ALTER TABLE favorito ADD COLUMN favorito_pg TINYINT(1) DEFAULT 0;