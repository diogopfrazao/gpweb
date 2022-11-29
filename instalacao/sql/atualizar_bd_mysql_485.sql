SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.16';
UPDATE versao SET ultima_atualizacao_bd='2018-04-22';
UPDATE versao SET ultima_atualizacao_codigo='2018-04-22';
UPDATE versao SET versao_bd=485;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('projetos','projeto_aprovado','Aprovado',1),
	('projetos','projeto_numero_tarefas','Número de tarefas',1);
	
	
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_formula_simples_variacao TINYINT(1) DEFAULT 0;	



DROP TABLE IF EXISTS pratica_indicador_valor_simples;

CREATE TABLE pratica_indicador_valor_simples (
	pratica_indicador_valor_simples_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_valor_simples_valor_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_valor_simples_formula_simples_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_valor_simples_valor DECIMAL(20,5) DEFAULT 0,
  PRIMARY KEY (pratica_indicador_valor_simples_id),
  KEY pratica_indicador_valor_simples_valor_id (pratica_indicador_valor_simples_valor_id),
  KEY pratica_indicador_valor_simples_formula_simples_id (pratica_indicador_valor_simples_formula_simples_id),
  CONSTRAINT pratica_indicador_valor_simples_formula_simples_id FOREIGN KEY (pratica_indicador_valor_simples_formula_simples_id) REFERENCES pratica_indicador_formula_simples (pratica_indicador_formula_simples_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_valor_simples_valor_id FOREIGN KEY (pratica_indicador_valor_simples_valor_id) REFERENCES pratica_indicador_valor (pratica_indicador_valor_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
