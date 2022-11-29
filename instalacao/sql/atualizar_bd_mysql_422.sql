SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-26';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-26';
UPDATE versao SET versao_bd=422;

ALTER TABLE campos_customizados_valores DROP COLUMN valor_modulo;

ALTER TABLE campos_customizados_valores ADD CONSTRAINT valor_campo_id FOREIGN KEY (valor_campo_id) REFERENCES campos_customizados_estrutura (campo_id)  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE agenda ADD COLUMN agenda_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE agenda ADD KEY agenda_cia (agenda_cia);
ALTER TABLE agenda ADD CONSTRAINT agenda_cia FOREIGN KEY (agenda_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;