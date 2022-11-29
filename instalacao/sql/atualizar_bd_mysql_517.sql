SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.21';
UPDATE versao SET ultima_atualizacao_bd='2019-02-15';
UPDATE versao SET ultima_atualizacao_codigo='2019-02-15';
UPDATE versao SET versao_bd=517;


UPDATE menu_item SET menu_item_permissao_extra=null WHERE menu_item_chave='projetos' AND menu_item_chave_pai IS NULL;
