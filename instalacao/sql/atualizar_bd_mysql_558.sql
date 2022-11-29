UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-05-15';
UPDATE versao SET ultima_atualizacao_codigo='2020-05-15';
UPDATE versao SET versao_bd=558;

DELETE FROM os WHERE os_cia IS NULL;