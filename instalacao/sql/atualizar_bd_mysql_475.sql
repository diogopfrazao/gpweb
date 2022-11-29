SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.15';
UPDATE versao SET ultima_atualizacao_bd='2018-03-13';
UPDATE versao SET ultima_atualizacao_codigo='2018-03-13';
UPDATE versao SET versao_bd=475;

UPDATE menu_item SET menu_item_permissao_extra=null WHERE menu_item_chave='afazer';