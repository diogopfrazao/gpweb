SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-05-25';
UPDATE versao SET ultima_atualizacao_codigo='2020-05-25';
UPDATE versao SET versao_bd=561;

UPDATE ssti INNER JOIN ssti_estrategia on ssti_id = ssti_estrategia_ssti set ssti_estrategia = ssti_estrategia_id where ssti_estrategia IS NULL;
UPDATE ssti INNER JOIN ssti_processo on ssti_id = ssti_processo_ssti set ssti_processo = ssti_processo_id where ssti_processo IS NULL;