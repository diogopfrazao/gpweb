SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.09';
UPDATE versao SET ultima_atualizacao_bd='2017-09-14';
UPDATE versao SET ultima_atualizacao_codigo='2017-09-14';
UPDATE versao SET versao_bd=449;

UPDATE sisvalores SET sisvalor_valor='Administrativo' WHERE sisvalor_titulo='Template' AND sisvalor_valor='Gerencial';
