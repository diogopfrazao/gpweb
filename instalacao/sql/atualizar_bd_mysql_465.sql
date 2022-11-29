SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2018-01-24';
UPDATE versao SET ultima_atualizacao_codigo='2018-01-24';
UPDATE versao SET versao_bd=465;

ALTER TABLE usuarios ADD COLUMN usuario_assinatura_local VARCHAR(255) DEFAULT NULL;
ALTER TABLE usuarios CHANGE usuario_assinatura usuario_assinatura_nome VARCHAR(255) DEFAULT NULL;

