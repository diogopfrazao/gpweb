SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-03-18';
UPDATE versao SET ultima_atualizacao_codigo='2018-03-18';
UPDATE versao SET versao_bd=478;

ALTER TABLE ssti ADD COLUMN ssti_tem_objetivo TINYINT(1) DEFAULT 0;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='sstis' AND campo_formulario_campo IN ('ssti_status', 'ssti_moeda', 'ssti_cor', 'ssti_percentagem'); 
DELETE FROM campo_formulario WHERE campo_formulario_tipo='ssti' AND campo_formulario_campo IN ('ssti_cor', 'moeda', 'ssti_percentagem'); 