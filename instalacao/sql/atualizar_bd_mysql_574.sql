SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-07-20';
UPDATE versao SET ultima_atualizacao_codigo='2020-07-20';
UPDATE versao SET versao_bd=574;

DROP TABLE IF EXISTS financeiro_estorno_rel_ne_fiplan;

CREATE TABLE financeiro_estorno_rel_ne_fiplan (
  financeiro_estorno_rel_ne_fiplan_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  financeiro_estorno_rel_ne_fiplan_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_os INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_ne_estorno INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_data_aprovou DATETIME,
  financeiro_estorno_rel_ne_fiplan_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ne_fiplan_valor DECIMAL(20,3) DEFAULT 0,
  PRIMARY KEY (financeiro_estorno_rel_ne_fiplan_id),
  KEY financeiro_estorno_rel_ne_fiplan_projeto (financeiro_estorno_rel_ne_fiplan_projeto),
  KEY financeiro_estorno_rel_ne_fiplan_tarefa (financeiro_estorno_rel_ne_fiplan_tarefa),
  KEY financeiro_estorno_rel_ne_fiplan_acao (financeiro_estorno_rel_ne_fiplan_acao),
  KEY financeiro_estorno_rel_ne_fiplan_acao_item (financeiro_estorno_rel_ne_fiplan_acao_item),
  KEY financeiro_estorno_rel_ne_fiplan_ne_estorno (financeiro_estorno_rel_ne_fiplan_ne_estorno),
  KEY financeiro_estorno_rel_ne_fiplan_responsavel (financeiro_estorno_rel_ne_fiplan_responsavel),
  KEY financeiro_estorno_rel_ne_fiplan_aprovou (financeiro_estorno_rel_ne_fiplan_aprovou),
  KEY financeiro_estorno_rel_ne_fiplan_tr (financeiro_estorno_rel_ne_fiplan_tr),
  KEY financeiro_estorno_rel_ne_fiplan_instrumento (financeiro_estorno_rel_ne_fiplan_instrumento),
  KEY financeiro_estorno_rel_ne_fiplan_os (financeiro_estorno_rel_ne_fiplan_os),
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_projeto FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_tarefa FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_acao FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_acao_item FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_tr FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_instrumento FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_os FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_ne_estorno FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_ne_estorno) REFERENCES financeiro_estorno_ne_fiplan (financeiro_estorno_ne_fiplan_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_responsavel FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ne_fiplan_aprovou FOREIGN KEY (financeiro_estorno_rel_ne_fiplan_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS financeiro_estorno_rel_ns_fiplan;

CREATE TABLE financeiro_estorno_rel_ns_fiplan (
  financeiro_estorno_rel_ns_fiplan_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  financeiro_estorno_rel_ns_fiplan_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_os INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_ns_estorno INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_data_aprovou DATETIME,
  financeiro_estorno_rel_ns_fiplan_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ns_fiplan_valor DECIMAL(20,3) DEFAULT 0,
  PRIMARY KEY (financeiro_estorno_rel_ns_fiplan_id),
  KEY financeiro_estorno_rel_ns_fiplan_projeto (financeiro_estorno_rel_ns_fiplan_projeto),
  KEY financeiro_estorno_rel_ns_fiplan_tarefa (financeiro_estorno_rel_ns_fiplan_tarefa),
  KEY financeiro_estorno_rel_ns_fiplan_acao (financeiro_estorno_rel_ns_fiplan_acao),
  KEY financeiro_estorno_rel_ns_fiplan_acao_item (financeiro_estorno_rel_ns_fiplan_acao_item),
  KEY financeiro_estorno_rel_ns_fiplan_ns_estorno (financeiro_estorno_rel_ns_fiplan_ns_estorno),
  KEY financeiro_estorno_rel_ns_fiplan_responsavel (financeiro_estorno_rel_ns_fiplan_responsavel),
  KEY financeiro_estorno_rel_ns_fiplan_aprovou (financeiro_estorno_rel_ns_fiplan_aprovou),
  KEY financeiro_estorno_rel_ns_fiplan_tr (financeiro_estorno_rel_ns_fiplan_tr),
  KEY financeiro_estorno_rel_ns_fiplan_instrumento (financeiro_estorno_rel_ns_fiplan_instrumento),
  KEY financeiro_estorno_rel_ns_fiplan_os (financeiro_estorno_rel_ns_fiplan_os),
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_projeto FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_tarefa FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_acao FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_acao_item FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_tr FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_instrumento FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_os FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_ns_estorno FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_ns_estorno) REFERENCES financeiro_estorno_ns_fiplan (financeiro_estorno_ns_fiplan_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_responsavel FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ns_fiplan_aprovou FOREIGN KEY (financeiro_estorno_rel_ns_fiplan_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS financeiro_estorno_rel_ob_fiplan;

CREATE TABLE financeiro_estorno_rel_ob_fiplan (
  financeiro_estorno_rel_ob_fiplan_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  financeiro_estorno_rel_ob_fiplan_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_os INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_ob_estorno INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_data_aprovou DATETIME,
  financeiro_estorno_rel_ob_fiplan_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_estorno_rel_ob_fiplan_valor DECIMAL(20,3) DEFAULT 0,
  PRIMARY KEY (financeiro_estorno_rel_ob_fiplan_id),
  KEY financeiro_estorno_rel_ob_fiplan_projeto (financeiro_estorno_rel_ob_fiplan_projeto),
  KEY financeiro_estorno_rel_ob_fiplan_tarefa (financeiro_estorno_rel_ob_fiplan_tarefa),
  KEY financeiro_estorno_rel_ob_fiplan_acao (financeiro_estorno_rel_ob_fiplan_acao),
  KEY financeiro_estorno_rel_ob_fiplan_acao_item (financeiro_estorno_rel_ob_fiplan_acao_item),
  KEY financeiro_estorno_rel_ob_fiplan_ob_estorno (financeiro_estorno_rel_ob_fiplan_ob_estorno),
  KEY financeiro_estorno_rel_ob_fiplan_responsavel (financeiro_estorno_rel_ob_fiplan_responsavel),
  KEY financeiro_estorno_rel_ob_fiplan_aprovou (financeiro_estorno_rel_ob_fiplan_aprovou),
  KEY financeiro_estorno_rel_ob_fiplan_tr (financeiro_estorno_rel_ob_fiplan_tr),
  KEY financeiro_estorno_rel_ob_fiplan_instrumento (financeiro_estorno_rel_ob_fiplan_instrumento),
  KEY financeiro_estorno_rel_ob_fiplan_os (financeiro_estorno_rel_ob_fiplan_os),
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_projeto FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_tarefa FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_acao FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_acao_item FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_tr FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_instrumento FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_os FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_ob_estorno FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_ob_estorno) REFERENCES financeiro_estorno_ob_fiplan (financeiro_estorno_ob_fiplan_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_responsavel FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT financeiro_estorno_rel_ob_fiplan_aprovou FOREIGN KEY (financeiro_estorno_rel_ob_fiplan_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;