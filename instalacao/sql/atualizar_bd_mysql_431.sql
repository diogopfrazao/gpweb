SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.07';
UPDATE versao SET ultima_atualizacao_bd='2017-07-11';
UPDATE versao SET ultima_atualizacao_codigo='2017-07-11';
UPDATE versao SET versao_bd=431;

UPDATE config SET config_valor='maps.google.com/maps/api/js?sensor=false' WHERE config_nome='google_map';