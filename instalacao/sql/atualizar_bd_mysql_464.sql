SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2018-01-24';
UPDATE versao SET ultima_atualizacao_codigo='2018-01-24';
UPDATE versao SET versao_bd=464;

UPDATE menu_item SET menu_item_parametros='''m=praticas&a=brainstorm_lista''' WHERE menu_item_chave='praticas_ferramenta_brainstorm';
UPDATE menu_item SET menu_item_parametros='''m=praticas&a=causa_efeito_lista''' WHERE menu_item_chave='praticas_ferramenta_ishikawa';
UPDATE menu_item SET menu_item_parametros='''m=praticas&a=gut_lista''' WHERE menu_item_chave='praticas_ferramenta_gut';