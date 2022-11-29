SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-02';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-02';
UPDATE versao SET versao_bd=531;

ALTER TABLE tr ADD COLUMN tr_modalidade VARCHAR(255) DEFAULT NULL;
ALTER TABLE tr ADD COLUMN tr_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE tr ADD COLUMN tr_encerramento DATE DEFAULT NULL;

ALTER TABLE tr ADD COLUMN tr_concrato_vigente VARCHAR(255) DEFAULT NULL;
ALTER TABLE tr ADD COLUMN tr_contrato_encerramento DATE DEFAULT NULL;
ALTER TABLE tr ADD COLUMN tr_concrato_ano INTEGER(4) DEFAULT NULL;