SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.27';
UPDATE versao SET ultima_atualizacao_bd='2020-09-29';
UPDATE versao SET ultima_atualizacao_codigo='2020-09-29';
UPDATE versao SET versao_bd=585;


DROP TABLE IF EXISTS financeiro_erro;

CREATE TABLE financeiro_erro (
  financeiro_erro_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  financeiro_erro_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_erro_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_erro_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_erro_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_erro_tr INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_erro_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
	financeiro_erro_os INTEGER(100) UNSIGNED DEFAULT NULL,
  financeiro_erro_data DATETIME,
  financeiro_erro_sql TEXT,
  financeiro_erro_obs TEXT,
  PRIMARY KEY (financeiro_erro_id),
  KEY financeiro_erro_projeto (financeiro_erro_projeto),
  KEY financeiro_erro_tarefa (financeiro_erro_tarefa),
  KEY financeiro_erro_acao (financeiro_erro_acao),
  KEY financeiro_erro_acao_item (financeiro_erro_acao_item),
  KEY financeiro_erro_tr (financeiro_erro_tr),
  KEY financeiro_erro_instrumento (financeiro_erro_instrumento),
  KEY financeiro_erro_os (financeiro_erro_os),
  CONSTRAINT financeiro_erro_projeto FOREIGN KEY (financeiro_erro_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_erro_tarefa FOREIGN KEY (financeiro_erro_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_erro_acao FOREIGN KEY (financeiro_erro_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_erro_acao_item FOREIGN KEY (financeiro_erro_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_erro_tr FOREIGN KEY (financeiro_erro_tr) REFERENCES tr (tr_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_erro_instrumento FOREIGN KEY (financeiro_erro_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT financeiro_erro_os FOREIGN KEY (financeiro_erro_os) REFERENCES os (os_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;