SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.10';
UPDATE versao SET ultima_atualizacao_bd='2017-10-15';
UPDATE versao SET ultima_atualizacao_codigo='2017-10-15';
UPDATE versao SET versao_bd=452;

ALTER TABLE objetivo CHANGE objetivo_nome objetivo_nome TEXT;
ALTER TABLE metas CHANGE pg_meta_nome pg_meta_nome TEXT;
ALTER TABLE agrupamento CHANGE agrupamento_nome agrupamento_nome TEXT;
ALTER TABLE swot CHANGE swot_nome swot_nome TEXT;
ALTER TABLE operativo CHANGE operativo_nome operativo_nome TEXT;
ALTER TABLE problema CHANGE problema_nome problema_nome TEXT;
ALTER TABLE programa CHANGE programa_nome programa_nome TEXT;
ALTER TABLE tgn CHANGE tgn_nome tgn_nome TEXT;
ALTER TABLE risco CHANGE risco_nome risco_nome TEXT;
ALTER TABLE risco_resposta CHANGE risco_resposta_nome risco_resposta_nome TEXT;
ALTER TABLE monitoramento CHANGE monitoramento_nome monitoramento_nome TEXT;
ALTER TABLE painel_odometro CHANGE painel_odometro_nome painel_odometro_nome TEXT;
ALTER TABLE painel CHANGE painel_nome painel_nome TEXT;
ALTER TABLE painel_slideshow CHANGE painel_slideshow_nome painel_slideshow_nome TEXT;
ALTER TABLE painel_composicao CHANGE painel_composicao_nome painel_composicao_nome TEXT;
ALTER TABLE brainstorm CHANGE brainstorm_nome brainstorm_nome TEXT;
ALTER TABLE canvas CHANGE canvas_nome canvas_nome TEXT;
ALTER TABLE tema CHANGE tema_nome tema_nome TEXT;
ALTER TABLE licao CHANGE licao_nome licao_nome TEXT;

ALTER TABLE custo DROP KEY custo_nome;
ALTER TABLE custo CHANGE custo_nome custo_nome TEXT;