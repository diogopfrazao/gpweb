SET FOREIGN_KEY_CHECKS=0;

DELETE FROM preferencia_modulo WHERE preferencia_modulo_modulo='instrumento';
DELETE FROM alerta WHERE alerta_campo='instrumento_alerta';