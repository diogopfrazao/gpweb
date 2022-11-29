SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2019-11-04';
UPDATE versao SET ultima_atualizacao_codigo='2019-11-04';
UPDATE versao SET versao_bd=539;

UPDATE menu_item SET menu_item_parametros='''m=praticas&a=log_lista''' WHERE menu_item_chave='log' AND menu_item_chave_pai='praticas';