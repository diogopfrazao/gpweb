SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-06-15';
UPDATE versao SET ultima_atualizacao_codigo='2020-06-15';
UPDATE versao SET versao_bd=566;

ALTER TABLE tr_avulso_custo ADD COLUMN tr_avulso_custo_custo_atual DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER tr_avulso_custo_custo;
ALTER TABLE os_avulso_custo ADD COLUMN os_avulso_custo_custo_atual DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER os_avulso_custo_custo;

ALTER TABLE tr ADD COLUMN tr_valor DECIMAL(20,5) UNSIGNED DEFAULT 0;
ALTER TABLE tr ADD COLUMN tr_valor_atual DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER tr_valor;
ALTER TABLE os ADD COLUMN os_valor_atual DECIMAL(20,5) UNSIGNED DEFAULT 0 AFTER os_valor;

ALTER TABLE os_avulso_custo MODIFY os_avulso_custo_percentagem TINYINT(1) DEFAULT 0;

ALTER TABLE tr_avulso_custo ADD COLUMN tr_avulso_custo_acrescimo DECIMAL(20,5) DEFAULT 0;
ALTER TABLE os_avulso_custo ADD COLUMN os_avulso_custo_acrescimo DECIMAL(20,5) DEFAULT 0;

ALTER TABLE meta_meta MODIFY meta_meta_valor_meta_boa DECIMAL(20,5) DEFAULT 0;
ALTER TABLE meta_meta MODIFY meta_meta_valor_meta_regular DECIMAL(20,5) DEFAULT 0;
ALTER TABLE meta_meta MODIFY meta_meta_valor_meta_ruim DECIMAL(20,5) DEFAULT 0;

ALTER TABLE pratica_indicador_meta MODIFY pratica_indicador_meta_valor_referencial DECIMAL(20,5) DEFAULT 0;
ALTER TABLE pratica_indicador_meta MODIFY pratica_indicador_meta_valor_meta_boa DECIMAL(20,5) DEFAULT 0;
ALTER TABLE pratica_indicador_meta MODIFY pratica_indicador_meta_valor_meta_regular DECIMAL(20,5) DEFAULT 0;
ALTER TABLE pratica_indicador_meta MODIFY pratica_indicador_meta_valor_meta_ruim DECIMAL(20,5) DEFAULT 0;

ALTER TABLE checklist_dados MODIFY pratica_indicador_valor_valor DECIMAL(20,5) DEFAULT 0;

ALTER TABLE instrumento MODIFY instrumento_garantia_contratual_percentual decimal(20,5) DEFAULT 0;

ALTER TABLE instrumento_avulso_custo MODIFY instrumento_avulso_custo_acrescimo DECIMAL(20,5) DEFAULT 0;

ALTER TABLE alerta MODIFY alerta_valor_min DECIMAL(20,5) DEFAULT 0;
ALTER TABLE alerta MODIFY alerta_valor_max DECIMAL(20,5) DEFAULT 0;

ALTER TABLE pagamento MODIFY pagamento_valor DECIMAL(20,5) DEFAULT 0;
ALTER TABLE baseline_pagamento MODIFY pagamento_valor DECIMAL(20,5) DEFAULT 0;

UPDATE tr_avulso_custo SET tr_avulso_custo_acrescimo=0 WHERE tr_avulso_custo_acrescimo IS NULL;
UPDATE os_avulso_custo SET os_avulso_custo_acrescimo=0 WHERE os_avulso_custo_acrescimo IS NULL;
UPDATE meta_meta SET meta_meta_valor_meta_boa=0 WHERE meta_meta_valor_meta_boa IS NULL;
UPDATE meta_meta SET meta_meta_valor_meta_regular=0 WHERE meta_meta_valor_meta_regular IS NULL;
UPDATE meta_meta SET meta_meta_valor_meta_ruim=0 WHERE meta_meta_valor_meta_ruim IS NULL;
UPDATE pratica_indicador_meta SET pratica_indicador_meta_valor_referencial=0 WHERE pratica_indicador_meta_valor_referencial IS NULL;
UPDATE pratica_indicador_meta SET pratica_indicador_meta_valor_meta_boa=0 WHERE pratica_indicador_meta_valor_meta_boa IS NULL;
UPDATE pratica_indicador_meta SET pratica_indicador_meta_valor_meta_regular=0 WHERE pratica_indicador_meta_valor_meta_regular IS NULL;
UPDATE pratica_indicador_meta SET pratica_indicador_meta_valor_meta_ruim=0 WHERE pratica_indicador_meta_valor_meta_ruim IS NULL;
UPDATE checklist_dados SET pratica_indicador_valor_valor=0 WHERE pratica_indicador_valor_valor IS NULL;
UPDATE instrumento SET instrumento_garantia_contratual_percentual=0 WHERE instrumento_garantia_contratual_percentual IS NULL;
UPDATE instrumento_avulso_custo SET instrumento_avulso_custo_acrescimo=0 WHERE instrumento_avulso_custo_acrescimo IS NULL;
UPDATE alerta SET alerta_valor_min=0 WHERE alerta_valor_min IS NULL;
UPDATE alerta SET alerta_valor_max=0 WHERE alerta_valor_max IS NULL;
UPDATE pagamento SET pagamento_valor=0 WHERE pagamento_valor IS NULL;
UPDATE baseline_pagamento SET pagamento_valor=0 WHERE pagamento_valor IS NULL;

DROP TABLE IF EXISTS financeiro_importacao;

CREATE TABLE financeiro_importacao (
  financeiro_importacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	financeiro_importacao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_os INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_importacao_data DATETIME,
  financeiro_importacao_qnt INTEGER(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (financeiro_importacao_id),
  KEY financeiro_importacao_projeto (financeiro_importacao_projeto),
  KEY financeiro_importacao_tarefa (financeiro_importacao_tarefa),
  KEY financeiro_importacao_acao (financeiro_importacao_acao),
  KEY financeiro_importacao_acao_item (financeiro_importacao_acao_item),
  KEY financeiro_importacao_tr (financeiro_importacao_tr),
  KEY financeiro_importacao_instrumento (financeiro_importacao_instrumento),
  KEY financeiro_importacao_os (financeiro_importacao_os),
  CONSTRAINT financeiro_importacao_projeto FOREIGN KEY (financeiro_importacao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_importacao_tarefa FOREIGN KEY (financeiro_importacao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_importacao_acao FOREIGN KEY (financeiro_importacao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_importacao_acao_item FOREIGN KEY (financeiro_importacao_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_importacao_tr FOREIGN KEY (financeiro_importacao_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_importacao_instrumento FOREIGN KEY (financeiro_importacao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_importacao_os FOREIGN KEY (financeiro_importacao_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;