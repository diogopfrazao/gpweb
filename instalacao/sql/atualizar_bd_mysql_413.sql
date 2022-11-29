SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.06';
UPDATE versao SET ultima_atualizacao_bd='2017-05-31';
UPDATE versao SET ultima_atualizacao_codigo='2017-05-31';
UPDATE versao SET versao_bd=413;


ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_moeda INTEGER(100) UNSIGNED DEFAULT 1; 
ALTER TABLE projeto_viabilidade ADD KEY projeto_viabilidade_moeda (projeto_viabilidade_moeda);
ALTER TABLE projeto_viabilidade ADD CONSTRAINT projeto_viabilidade_moeda FOREIGN KEY (projeto_viabilidade_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE;  

ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_moeda INTEGER(100) UNSIGNED DEFAULT 1; 
ALTER TABLE projeto_abertura ADD KEY projeto_abertura_moeda (projeto_abertura_moeda);
ALTER TABLE projeto_abertura ADD CONSTRAINT projeto_abertura_moeda FOREIGN KEY (projeto_abertura_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE;  

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
	('viabilidade', 'moeda', 'Moeda estrangeira', 1),
	('abertura', 'moeda', 'Moeda estrangeira', 1);
	
INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES	
	('projetos','viabilidade_custo','Custo do estudo de viabilidade', null, null),
	('projetos','abertura_custo','Custo do termo de abertura', null, null),
	('praticas','plano_acao_item','Item de plano de ação', null, null);
	
UPDATE perfil_submodulo SET perfil_submodulo_descricao='Projetos' WHERE perfil_submodulo_submodulo='projetos_lista' AND perfil_submodulo_modulo='projetos';
UPDATE perfil_submodulo SET perfil_submodulo_descricao='Tarefas' WHERE perfil_submodulo_submodulo='projetos_tarefas' AND perfil_submodulo_modulo='projetos';

ALTER TABLE eventos ADD COLUMN evento_ativo INTEGER(1) DEFAULT 1;
ALTER TABLE baseline_eventos ADD COLUMN evento_ativo INTEGER(1) DEFAULT 1;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projeto_viabilidades','projeto_viabilidade_relacionado','Relacionado',1),
	('projeto_aberturas','projeto_abertura_relacionado','Relacionado',1),
	('trs','tr_percentagem','Percentagem',1),
	('trs','tr_status','Status',1),
	('trs','tr_acao_orcamentaria','Ação Orçamentária',0);
	
ALTER TABLE tr ADD COLUMN tr_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0;	
ALTER TABLE tr ADD COLUMN tr_status INTEGER(10) DEFAULT 0;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES
	('StatusTRCor','c5eff4','0',NULL),
	('StatusTRCor','e4ee8b','1',NULL),
	('StatusTRCor','a0cc9d','2',NULL),
	('StatusTRCor','da908c','3',NULL),
	('StatusTRCor','e3c383','4',NULL),
	('StatusTRCor','f297f1','5',NULL),
	('StatusTR','Não Iniciada','0',NULL),
	('StatusTR','Pendente','1',NULL),
	('StatusTR','Concluída','2',NULL),
	('StatusTR','Não Realizada','3',NULL),
	('StatusTR','Em Andamento','4',NULL),
	('StatusTR','Em Atraso','5',NULL);
	
DROP TABLE IF EXISTS projeto_custo;

CREATE TABLE projeto_custo (
  projeto_custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_custo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_nome VARCHAR(255) DEFAULT NULL,
  projeto_custo_codigo VARCHAR(255) DEFAULT NULL,
  projeto_custo_fonte VARCHAR(255) DEFAULT NULL,
  projeto_custo_regiao VARCHAR(255) DEFAULT NULL,
  projeto_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  projeto_custo_data DATETIME DEFAULT NULL,
  projeto_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  projeto_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  projeto_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	projeto_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	projeto_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	projeto_custo_data_moeda DATE DEFAULT NULL,
  projeto_custo_percentagem TINYINT(4) DEFAULT 0,
  projeto_custo_descricao TEXT,
  projeto_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_nd VARCHAR(11) DEFAULT NULL,
  projeto_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  projeto_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  projeto_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  projeto_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	projeto_custo_data_limite DATE DEFAULT NULL,
	projeto_custo_pi VARCHAR(100) DEFAULT NULL,
	projeto_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_custo_aprovado TINYINT(1) DEFAULT NULL,
	projeto_custo_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (projeto_custo_id),
  KEY projeto_custo_projeto (projeto_custo_projeto),
  KEY projeto_custo_usuario_inicio (projeto_custo_usuario),
  KEY projeto_custo_ordem (projeto_custo_ordem),
  KEY projeto_custo_data_inicio (projeto_custo_data),
  KEY projeto_custo_nome (projeto_custo_nome),
  KEY projeto_custo_aprovou (projeto_custo_aprovou),
  KEY projeto_custo_moeda (projeto_custo_moeda),
  CONSTRAINT projeto_custo_usuario FOREIGN KEY (projeto_custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_custo_projeto FOREIGN KEY (projeto_custo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_custo_aprovou FOREIGN KEY (projeto_custo_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_custo_moeda FOREIGN KEY (projeto_custo_moeda) REFERENCES moeda (moeda_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_projeto_custo;

CREATE TABLE baseline_projeto_custo (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_custo_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_custo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_nome VARCHAR(255) DEFAULT NULL,
  projeto_custo_codigo VARCHAR(255) DEFAULT NULL,
  projeto_custo_fonte VARCHAR(255) DEFAULT NULL,
  projeto_custo_regiao VARCHAR(255) DEFAULT NULL,
  projeto_custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  projeto_custo_data DATETIME DEFAULT NULL,
  projeto_custo_quantidade DECIMAL(20,5) UNSIGNED DEFAULT 0,
  projeto_custo_custo DECIMAL(20,5) UNSIGNED DEFAULT 0,
  projeto_custo_bdi DECIMAL(20,5) UNSIGNED DEFAULT 0,
	projeto_custo_moeda INTEGER(100) UNSIGNED DEFAULT 1, 
	projeto_custo_cotacao DECIMAL(6,5) UNSIGNED DEFAULT 1.00000, 
	projeto_custo_data_moeda DATE DEFAULT NULL,
  projeto_custo_percentagem TINYINT(4) DEFAULT 0,
  projeto_custo_descricao TEXT,
  projeto_custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_custo_nd VARCHAR(11) DEFAULT NULL,
  projeto_custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  projeto_custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  projeto_custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  projeto_custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	projeto_custo_data_limite DATE DEFAULT NULL,
	projeto_custo_pi VARCHAR(100) DEFAULT NULL,
	projeto_custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_custo_aprovado TINYINT(1) DEFAULT NULL,
	projeto_custo_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_custo_id),
  CONSTRAINT baseline_projeto_custo FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
	