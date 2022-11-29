SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-04-04';
UPDATE versao SET ultima_atualizacao_codigo='2018-04-04';
UPDATE versao SET versao_bd=483;

ALTER TABLE pratica_indicador DROP FOREIGN KEY pratica_indicador_requisito;

ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_requisito FOREIGN KEY (pratica_indicador_requisito) REFERENCES pratica_indicador_requisito (pratica_indicador_requisito_id) ON DELETE SET NULL ON UPDATE CASCADE;