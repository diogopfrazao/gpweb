SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.6'; 
UPDATE versao SET ultima_atualizacao_bd='2012-01-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-01-01'; 
UPDATE versao SET versao_bd=88;

ALTER TABLE plano_acao_item_gastos DROP FOREIGN KEY plano_acao_item_gastos_fk;
ALTER TABLE plano_acao_item_gastos ADD CONSTRAINT plano_acao_item_gastos_fk FOREIGN KEY (plano_acao_item_gastos_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE plano_acao_item_custos DROP FOREIGN KEY plano_acao_item_custos_fk;
ALTER TABLE plano_acao_item_custos ADD CONSTRAINT plano_acao_item_custos_fk FOREIGN KEY (plano_acao_item_custos_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE;


DELETE FROM artefatos_tipo WHERE artefato_tipo_id IN (5,6,7,8,9,10,11,12,13);

INSERT INTO artefatos_tipo (artefato_tipo_id, artefato_tipo_nome, artefato_tipo_campos, artefato_tipo_descricao, artefato_tipo_imagem) VALUES 
  (5,'Plano de Qualidade (PQ)',0x613A393A7B733A353A2263616D706F223B613A393A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32373A2270726F6A65746F5F7175616C69646164655F64657363726963616F223B7D693A363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7175616C69646164655F656E7472656761223B7D693A373B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7175616C69646164655F7573756172696F223B7D693A383B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7175616C69646164655F7573756172696F223B7D693A393B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A32323A2270726F6A65746F5F7175616C69646164655F64617461223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2235223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A393B7D,'',''),
  (6,'Plano de Comunica��o (PC)',0x613A393A7B733A353A2263616D706F223B613A393A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F636F6D756E69636163616F5F64657363726963616F223B7D693A363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32363A2270726F6A65746F5F636F6D756E69636163616F5F6576656E746F223B7D693A373B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A32373A2270726F6A65746F5F636F6D756E69636163616F5F7573756172696F223B7D693A383B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A32373A2270726F6A65746F5F636F6D756E69636163616F5F7573756172696F223B7D693A393B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A32343A2270726F6A65746F5F636F6D756E69636163616F5F64617461223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2236223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A393B7D,'',''),
  (7,'Gerenciamento de Risco(GR)',0x613A393A7B733A353A2263616D706F223B613A393A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32333A2270726F6A65746F5F726973636F5F64657363726963616F223B7D693A363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726973636F5F7469706F223B7D693A373B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A32313A2270726F6A65746F5F726973636F5F7573756172696F223B7D693A383B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A32313A2270726F6A65746F5F726973636F5F7573756172696F223B7D693A393B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726973636F5F64617461223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2237223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A393B7D,'',''),
  (8,'Plano de Gerenciamento do Projeto (PGP)',0x613A393A7B733A353A2263616D706F223B613A32373A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33333A2270726F6A65746F5F656D626173616D656E746F5F6A757374696669636174697661223B7D693A363B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32383A2270726F6A65746F5F656D626173616D656E746F5F6F626A657469766F223B7D693A373B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32363A2270726F6A65746F5F656D626173616D656E746F5F6573636F706F223B7D693A383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31393A226573747275747572615F616E616C6974696361223B7D693A393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A22646963696F6E6172696F5F656170223B7D693A31303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33303A2270726F6A65746F5F656D626173616D656E746F5F6E616F5F6573636F706F223B7D693A31313B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F656D626173616D656E746F5F7072656D6973736173223B7D693A31323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33303A2270726F6A65746F5F656D626173616D656E746F5F726573747269636F6573223B7D693A31333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31363A2263726F6E6F6772616D615F6D6172636F223B7D693A31343B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A393A226F7263616D656E746F223B7D693A31353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32373A2270726F6A65746F5F7175616C69646164655F64657363726963616F223B7D693A31363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7175616C69646164655F656E7472656761223B7D693A31373B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31393A226F7267616E6F6772616D615F70726F6A65746F223B7D693A31383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A226571756970655F70726F6A65746F223B7D693A31393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A22726573706F6E736162696C696461646573223B7D693A32303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F636F6D756E69636163616F5F64657363726963616F223B7D693A32313B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32363A2270726F6A65746F5F636F6D756E69636163616F5F6576656E746F223B7D693A32323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32333A2270726F6A65746F5F726973636F5F64657363726963616F223B7D693A32333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726973636F5F7469706F223B7D693A32343B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A22617175697369636F65735F70726F6A65746F223B7D693A32353B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A33313A2270726F6A65746F5F656D626173616D656E746F5F726573706F6E736176656C223B7D693A32363B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A33313A2270726F6A65746F5F656D626173616D656E746F5F726573706F6E736176656C223B7D693A32373B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A32343A2270726F6A65746F5F656D626173616D656E746F5F64617461223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2238223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A32373B7D,'',''),
  (9,'Li��es Aprendidas (LA)',0x613A393A7B733A353A2263616D706F223B613A353A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A353A226C6963616F223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2239223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A353B7D,'',''),
  (10,'Termo de Encerramento de Projeto (TEP)',0x613A393A7B733A353A2263616D706F223B613A31303A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A33303A22656E63657272616D656E746F5F6461646F735F726573706F6E736176656C223B7D693A363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32383A2270726F6A65746F5F656E63657272616D656E746F5F6465636973616F223B7D693A373B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33343A2270726F6A65746F5F656E63657272616D656E746F5F6A757374696669636174697661223B7D693A383B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A33323A2270726F6A65746F5F656E63657272616D656E746F5F726573706F6E736176656C223B7D693A393B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A33323A2270726F6A65746F5F656E63657272616D656E746F5F726573706F6E736176656C223B7D693A31303B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A32353A2270726F6A65746F5F656E63657272616D656E746F5F64617461223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A323A223130223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A31303B7D,'',''),
  (11,'Termo de Recebimento de Produto/Servi�o (TRPS)',0x613A393A7B733A353A2263616D706F223B613A31333A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32363A2270726F6A65746F5F7265636562696D656E746F5F6E756D65726F223B7D693A363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A227265636562696D656E746F5F6461646F735F636C69656E7465223B7D693A373B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32393A227265636562696D656E746F5F6461646F735F726573706F6E736176656C223B7D693A383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32343A2270726F6A65746F5F7265636562696D656E746F5F7469706F223B7D693A393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7265636562696D656E746F5F6C69737461223B7D693A31303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33303A2270726F6A65746F5F7265636562696D656E746F5F6F62736572766163616F223B7D693A31313B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A33313A2270726F6A65746F5F7265636562696D656E746F5F726573706F6E736176656C223B7D693A31323B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A33313A2270726F6A65746F5F7265636562696D656E746F5F726573706F6E736176656C223B7D693A31333B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A33323A2270726F6A65746F5F7265636562696D656E746F5F646174615F656E7472656761223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A323A223131223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A31333B7D,'',''),
  (12,'Formul�rio de Solicita��o de Mudan�as (FSM)',0x613A393A7B733A353A2263616D706F223B613A32363A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31393A226E756D65726F5F747265735F64696769746F73223B733A353A226461646F73223B733A32323A2270726F6A65746F5F6D7564616E63615F6E756D65726F223B7D693A363B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A32303A2270726F6A65746F5F6D7564616E63615F64617461223B7D693A373B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F636F6E7461746F223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6D7564616E63615F636C69656E7465223B7D693A383B613A323A7B733A343A227469706F223B733A31323A22646570745F636F6E7461746F223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6D7564616E63615F636C69656E7465223B7D693A393B613A323A7B733A343A227469706F223B733A31333A22656D61696C5F636F6E7461746F223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6D7564616E63615F636C69656E7465223B7D693A31303B613A323A7B733A343A227469706F223B733A31363A2274656C65666F6E655F636F6E7461746F223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6D7564616E63615F636C69656E7465223B7D693A31313B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F6D7564616E63615F6A757374696669636174697661223B7D693A31323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33313A2270726F6A65746F5F6D7564616E63615F706172656365725F7465636E69636F223B7D693A31333B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32343A2270726F6A65746F5F6D7564616E63615F736F6C75636F6573223B7D693A31343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33343A2270726F6A65746F5F6D7564616E63615F696D706163746F5F63726F6E6F6772616D61223B7D693A31353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F6D7564616E63615F696D706163746F5F637573746F223B7D693A31363B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32363A2270726F6A65746F5F6D7564616E63615F6E6F766F5F726973636F223B7D693A31373B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33313A2270726F6A65746F5F6D7564616E63615F6F7574726F735F696D706163746F73223B7D693A31383B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6D7564616E63615F736F6C7563616F223B7D693A31393B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6D7564616E63615F70617265636572223B7D693A32303B613A323A7B733A343A227469706F223B733A383A226D61726361725F78223B733A353A226461646F73223B733A33373A2270726F6A65746F5F6D7564616E63615F7265717569736974616E74655F6170726F76616461223B7D693A32313B613A323A7B733A343A227469706F223B733A383A226D61726361725F78223B733A353A226461646F73223B733A33383A2270726F6A65746F5F6D7564616E63615F7265717569736974616E74655F726570726F76616461223B7D693A32323B613A323A7B733A343A227469706F223B733A383A226D61726361725F78223B733A353A226461646F73223B733A33383A2270726F6A65746F5F6D7564616E63615F61646D696E697374726163616F5F6170726F76616461223B7D693A32333B613A323A7B733A343A227469706F223B733A383A226D61726361725F78223B733A353A226461646F73223B733A33393A2270726F6A65746F5F6D7564616E63615F61646D696E697374726163616F5F726570726F76616461223B7D693A32343B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A32363A2270726F6A65746F5F6D7564616E63615F6175746F726964616465223B7D693A32353B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A32363A2270726F6A65746F5F6D7564616E63615F6175746F726964616465223B7D693A32363B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A33303A2270726F6A65746F5F6D7564616E63615F646174615F6170726F766163616F223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A323A223132223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A32363B7D,'',''),
  (13,'Ata de Reuni�o',0x613A393A7B733A353A2263616D706F223B613A31383A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6174615F646174615F696E6963696F223B7D693A363B613A323A7B733A343A227469706F223B733A31323A22686F72615F64655F64617461223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6174615F646174615F696E6963696F223B7D693A373B613A323A7B733A343A227469706F223B733A31323A22686F72615F64655F64617461223B733A353A226461646F73223B733A32303A2270726F6A65746F5F6174615F646174615F66696D223B7D693A383B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6174615F6C6F63616C223B7D693A393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F6174615F7061727469636970616E746573223B7D693A31303B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6174615F7061757461223B7D693A31313B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31383A2270726F6A65746F5F6174615F72656C61746F223B7D693A31323B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6174615F61636F6573223B7D693A31333B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A33313A2270726F6A65746F5F6174615F70726F78696D615F646174615F696E6963696F223B7D693A31343B613A323A7B733A343A227469706F223B733A31323A22686F72615F64655F64617461223B733A353A226461646F73223B733A33313A2270726F6A65746F5F6174615F70726F78696D615F646174615F696E6963696F223B7D693A31353B613A323A7B733A343A227469706F223B733A31323A22686F72615F64655F64617461223B733A353A226461646F73223B733A32383A2270726F6A65746F5F6174615F70726F78696D615F646174615F66696D223B7D693A31363B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32353A2270726F6A65746F5F6174615F70726F78696D615F6C6F63616C223B7D693A31373B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F6174615F70617574615F70726F78696D61223B7D693A31383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32333A2270726F6A65746F5F6174615F617373696E617475726173223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A323A223133223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A31383B7D,'','');