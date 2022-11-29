<?php
global $config, $bd;


if(!file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$bd->Execute("DROP TABLE IF EXISTS beneficio;");

	$bd->Execute("CREATE TABLE beneficio (
  beneficio_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  beneficio_programa INTEGER(100) UNSIGNED DEFAULT NULL,
  beneficio_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  beneficio_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  beneficio_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  beneficio_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  beneficio_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  beneficio_nome VARCHAR(250) DEFAULT NULL,
  beneficio_data DATETIME DEFAULT NULL,
  beneficio_inicio DATETIME DEFAULT NULL,
	beneficio_fim DATETIME DEFAULT NULL,
	beneficio_duracao DECIMAL(20,5) UNSIGNED DEFAULT 0,
  beneficio_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  beneficio_cor VARCHAR(6) DEFAULT 'FFFFFF',
  beneficio_descricao TEXT,
  beneficio_ativo TINYINT(1) DEFAULT 1,
  beneficio_percentagem DECIMAL(20,5) UNSIGNED DEFAULT 0,
  beneficio_meta DECIMAL(20,5) UNSIGNED DEFAULT 0,
  beneficio_peso DECIMAL(20,5) UNSIGNED DEFAULT 0,
  PRIMARY KEY (beneficio_id),
  KEY beneficio_programa (beneficio_programa),
  KEY beneficio_cia (beneficio_cia),
  KEY beneficio_dept (beneficio_dept),
  KEY beneficio_superior (beneficio_superior),
  KEY beneficio_usuario (beneficio_usuario),
  KEY beneficio_indicador (beneficio_indicador),
  CONSTRAINT beneficio_programa FOREIGN KEY (beneficio_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT beneficio_cia FOREIGN KEY (beneficio_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT beneficio_superior FOREIGN KEY (beneficio_superior) REFERENCES beneficio (beneficio_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT beneficio_usuario FOREIGN KEY (beneficio_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT beneficio_indicador FOREIGN KEY (beneficio_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT beneficio_dept FOREIGN KEY (beneficio_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
	}
	
?>