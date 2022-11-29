SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-10-02';
UPDATE versao SET ultima_atualizacao_codigo='2019-10-02';
UPDATE versao SET versao_bd=533;

ALTER TABLE tr ADD COLUMN tr_geo TINYINT(1) DEFAULT 0;

ALTER TABLE tr CHANGE tr_concrato_vigente tr_contrato_vigente  varchar(255) DEFAULT NULL;
ALTER TABLE tr CHANGE tr_concrato_ano tr_contrato_ano INTEGER(10) DEFAULT NULL;


ALTER TABLE instrumento CHANGE instrumento_prazo_prorrogacao instrumento_prazo_prorrogacao INTEGER(10) DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_prazo_prorrogacao_tipo INTEGER(1) DEFAULT NULL
