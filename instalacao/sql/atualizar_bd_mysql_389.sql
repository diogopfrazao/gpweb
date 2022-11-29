SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.63';
UPDATE versao SET ultima_atualizacao_bd='2017-01-15';
UPDATE versao SET ultima_atualizacao_codigo='2017-01-15';
UPDATE versao SET versao_bd=389;


DROP TABLE IF EXISTS recurso_tarefa;

CREATE TABLE recurso_tarefa (
  recurso_tarefa_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  recurso_tarefa_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_evento INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_insercao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  recurso_tarefa_inicio DATETIME,
  recurso_tarefa_fim DATETIME,
  recurso_tarefa_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0,
  recurso_tarefa_aprovou INTEGER(100)UNSIGNED DEFAULT NULL,
  recurso_tarefa_aprovado TINYINT(1) DEFAULT NULL,
  recurso_tarefa_data DATETIME,
  recurso_tarefa_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0.000,
	recurso_tarefa_valor_hora DECIMAL(20,5) UNSIGNED DEFAULT 0,
	recurso_tarefa_obs TEXT,
	recurso_tarefa_corrido TINYINT(1) NOT NULL DEFAULT 0,
  recurso_tarefa_percentual INTEGER UNSIGNED NULL DEFAULT 100,
  recurso_tarefa_ordem INT(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (recurso_tarefa_id),
  KEY recurso_tarefa_recurso (recurso_tarefa_recurso),
  KEY recurso_tarefa_tarefa (recurso_tarefa_tarefa),
  KEY recurso_tarefa_evento (recurso_tarefa_evento),
  KEY recurso_tarefa_aprovou (recurso_tarefa_aprovou),
  CONSTRAINT recurso_tarefa_tarefa FOREIGN KEY (recurso_tarefa_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_tarefa_recurso FOREIGN KEY (recurso_tarefa_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_tarefa_evento FOREIGN KEY (recurso_tarefa_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_tarefa_aprovou FOREIGN KEY (recurso_tarefa_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recurso_tarefa_custo;

CREATE TABLE recurso_tarefa_custo (
  recurso_tarefa_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  recurso_tarefa_custo_recurso_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  recurso_tarefa_custo_nome VARCHAR(255) DEFAULT NULL,
  recurso_tarefa_custo_codigo VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_custo_fonte VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_custo_regiao VARCHAR(255) DEFAULT NULL,
	recurso_tarefa_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	recurso_tarefa_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	recurso_tarefa_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000,
	recurso_tarefa_custo_data_moeda DATE DEFAULT NULL, 	
  recurso_tarefa_custo_data DATETIME DEFAULT NULL,
  recurso_tarefa_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  recurso_tarefa_custo_valor DECIMAL(20,5) UNSIGNED DEFAULT 0,
  recurso_tarefa_custo_percentagem TINYINT(4) DEFAULT 0,
  recurso_tarefa_custo_descricao TEXT,
  recurso_tarefa_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_nd VARCHAR(11) DEFAULT NULL,
  recurso_tarefa_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  recurso_tarefa_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  recurso_tarefa_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  recurso_tarefa_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	recurso_tarefa_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  recurso_tarefa_custo_data_limite DATE DEFAULT NULL,
  PRIMARY KEY (recurso_tarefa_custo_id),
  KEY recurso_tarefa_custo_recurso_tarefa (recurso_tarefa_custo_recurso_tarefa),
  KEY recurso_tarefa_custo_usuario (recurso_tarefa_custo_usuario),
  KEY recurso_tarefa_custo_ordem (recurso_tarefa_custo_ordem),
  KEY recurso_tarefa_custo_data_inicio (recurso_tarefa_custo_data),
  KEY recurso_tarefa_custo_nome (recurso_tarefa_custo_nome),
  KEY recurso_tarefa_custo_moeda (recurso_tarefa_custo_moeda),
  CONSTRAINT recurso_tarefa_custo_recurso_tarefa FOREIGN KEY (recurso_tarefa_custo_recurso_tarefa) REFERENCES recurso_tarefa (recurso_tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_tarefa_custo_usuario FOREIGN KEY (recurso_tarefa_custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT recurso_tarefa_custo_moeda FOREIGN KEY (recurso_tarefa_custo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
	
