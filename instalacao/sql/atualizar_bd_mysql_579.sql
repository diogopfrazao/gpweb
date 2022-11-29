SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-08-06';
UPDATE versao SET ultima_atualizacao_codigo='2020-08-06';
UPDATE versao SET versao_bd=579;

ALTER TABLE os ADD COLUMN os_processo VARCHAR(100) DEFAULT NULL AFTER os_empenho;
ALTER TABLE os ADD COLUMN os_cnpj VARCHAR(18) DEFAULT NULL AFTER os_processo;

ALTER TABLE os DROP COLUMN os_empenho;
ALTER TABLE os DROP COLUMN os_pedido_empenho;




DELETE FROM campo_formulario WHERE campo_formulario_campo='os_fornecedor';


ALTER TABLE os DROP COLUMN os_fornecedor;
ALTER TABLE os DROP COLUMN os_fornecedor_endereco;
ALTER TABLE os DROP COLUMN os_fornecedor_fone;
ALTER TABLE os DROP COLUMN os_fornecedor_cep;
ALTER TABLE os DROP COLUMN os_fornecedor_municipio;
ALTER TABLE os DROP COLUMN os_fornecedor_estado;