SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.21';
UPDATE versao SET ultima_atualizacao_bd='2018-12-05';
UPDATE versao SET ultima_atualizacao_codigo='2018-12-05';
UPDATE versao SET versao_bd=514;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projetos','projeto_encerramento','Data de encerramento', 0);
