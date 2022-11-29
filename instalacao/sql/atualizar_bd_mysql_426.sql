SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-06-27';
UPDATE versao SET ultima_atualizacao_codigo='2017-06-27';
UPDATE versao SET versao_bd=426;

INSERT INTO artefato_campo (artefato_campo_arquivo, artefato_campo_campo, artefato_campo_descricao) VALUES
	('termo_abertura.html','projeto_abertura_requisito','requisito');
