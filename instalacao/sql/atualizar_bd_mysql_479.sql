SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-03-18';
UPDATE versao SET ultima_atualizacao_codigo='2018-03-18';
UPDATE versao SET versao_bd=479;

ALTER TABLE laudo ADD COLUMN laudo_tem_objetivo TINYINT(1) DEFAULT 0;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='laudos' AND campo_formulario_campo IN ('laudo_status', 'laudo_moeda', 'laudo_cor', 'laudo_percentagem', 'laudo_stakeholders'); 
DELETE FROM campo_formulario WHERE campo_formulario_tipo='laudo' AND campo_formulario_campo IN ('laudo_cor', 'moeda', 'laudo_percentagem'); 