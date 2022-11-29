SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2018-02-04';
UPDATE versao SET ultima_atualizacao_codigo='2018-02-04';
UPDATE versao SET versao_bd=468;

ALTER TABLE ssti ADD COLUMN ssti_pdti TINYINT(1) DEFAULT 0;
UPDATE campo_formulario SET campo_formulario_ativo=1 WHERE campo_formulario_campo='ssti_pdti' AND campo_formulario_tipo='sstis';