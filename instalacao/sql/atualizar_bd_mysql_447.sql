SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.08';
UPDATE versao SET ultima_atualizacao_bd='2017-08-15';
UPDATE versao SET ultima_atualizacao_codigo='2017-08-15';
UPDATE versao SET versao_bd=447;

ALTER TABLE ata_acao CHANGE ata_acao_duracao ata_acao_duracao DECIMAL(20,5) unsigned DEFAULT 0;
ALTER TABLE ata_acao CHANGE ata_acao_percentagem ata_acao_percentagem DECIMAL(20,5) unsigned DEFAULT 0;