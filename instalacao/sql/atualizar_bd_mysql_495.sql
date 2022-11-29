SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-06-07';
UPDATE versao SET ultima_atualizacao_codigo='2018-06-07';
UPDATE versao SET versao_bd=495;

ALTER TABLE ssti ADD COLUMN ssti_externo TINYINT(1) DEFAULT 0;
ALTER TABLE laudo ADD COLUMN laudo_externo TINYINT(1) DEFAULT 0;



ALTER TABLE ssti_arquivo ADD COLUMN ssti_arquivo_laudo int(100) unsigned DEFAULT NULL;
ALTER TABLE ssti_arquivo ADD KEY ssti_arquivo_laudo (ssti_arquivo_laudo);
ALTER TABLE ssti_arquivo ADD CONSTRAINT ssti_arquivo_laudo FOREIGN KEY (ssti_arquivo_laudo) REFERENCES laudo (laudo_id) ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE laudo_arquivo;

