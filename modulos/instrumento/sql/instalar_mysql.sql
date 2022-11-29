SET FOREIGN_KEY_CHECKS=0;

DELETE FROM preferencia_modulo WHERE preferencia_modulo_modulo='instrumento';
INSERT INTO preferencia_modulo (preferencia_modulo_modulo, preferencia_modulo_arquivo, preferencia_modulo_descricao) VALUES 
 ('instrumento','instrumento_lista','instrumentos');
 
INSERT INTO alerta (alerta_campo, alerta_ativo, alerta_tem_valor, alerta_valor_min, alerta_valor_max, alerta_email, alerta_msg, alerta_sms, alerta_instantaneo, alerta_legenda, alerta_grupo, alerta_ordem, alerta_responsavel, alerta_designado, alerta_tem_dias, alerta_incluir) VALUES 
	('instrumento_alerta',0,0,NULL,NULL,1,1,0,0,'Enviar alerta se estiver a X dias do vencimento','instrumento',1,1,0,1, NULL); 