SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-17';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-17';
UPDATE versao SET versao_bd=488;

ALTER TABLE laudo MODIFY laudo_tipo_demanda INTEGER(10) DEFAULT 0;



ALTER TABLE laudo DROP COLUMN laudo_demanda_legal;
ALTER TABLE laudo DROP COLUMN laudo_retorno_financeiro;



ALTER TABLE laudo ADD COLUMN laudo_ponto_funcao decimal(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE laudo ADD COLUMN laudo_tempo_desenvolvimento_numerico decimal(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE laudo ADD COLUMN laudo_tempo_desenvolvimento_escala VARCHAR(20) DEFAULT NULL;
ALTER TABLE laudo ADD COLUMN laudo_custo_total decimal(20,5) UNSIGNED DEFAULT 0;














	