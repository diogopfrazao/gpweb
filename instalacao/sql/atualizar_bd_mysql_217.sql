SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.13'; 
UPDATE versao SET ultima_atualizacao_bd='2014-04-03'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-04-03'; 
UPDATE versao SET versao_bd=217;

ALTER TABLE preferencia CHANGE filtroevento filtroevento VARCHAR(20) DEFAULT 'todos_aceitos'; 