SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.14';
UPDATE versao SET ultima_atualizacao_bd='2017-12-07';
UPDATE versao SET ultima_atualizacao_codigo='2017-12-07';
UPDATE versao SET versao_bd=460;


RENAME TABLE campos_customizados_estrutura TO campo_customizado;
ALTER TABLE campo_customizado DROP KEY cfs_campo_ordem;
ALTER TABLE campo_customizado DROP KEY campo_modulo;
ALTER TABLE campo_customizado DROP KEY campo_pagina;







ALTER TABLE campo_customizado CHANGE campo_modulo campo_customizado_modulo VARCHAR(30) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_pagina campo_customizado_pagina VARCHAR(30) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_tipo_html campo_customizado_tipo_html VARCHAR(20) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_tipo_dado campo_customizado_tipo_dado VARCHAR(20) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_ordem campo_customizado_ordem INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_nome campo_customizado_nome VARCHAR(100) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_tags_extras campo_customizado_tags_extras VARCHAR(250) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_descricao campo_customizado_descricao VARCHAR(250) DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_formula campo_customizado_formula TEXT;
ALTER TABLE campo_customizado CHANGE campo_tab campo_customizado_tab INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_customizado CHANGE campo_publicado campo_customizado_publicado TINYINT(1) DEFAULT 0;


ALTER TABLE campo_customizado ADD COLUMN campo_customizado_descendente TINYINT(1) DEFAULT 0;
ALTER TABLE campo_customizado ADD COLUMN campo_customizado_por_chave TINYINT(1) DEFAULT 0;



RENAME TABLE campos_customizados_valores TO campo_customizado_valor;
CALL PROC_DROP_FOREIGN_KEY('campo_customizado_valor','valor_campo_id');
ALTER TABLE campo_customizado_valor DROP KEY valor_campo_id;
ALTER TABLE campo_customizado_valor DROP KEY valor_objeto_id;
ALTER TABLE campo_customizado_valor CHANGE valor_id campo_customizado_valor_id INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE campo_customizado_valor CHANGE valor_objeto_id campo_customizado_valor_objeto INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_customizado_valor CHANGE valor_campo_id campo_customizado_valor_campo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_customizado_valor CHANGE valor_caractere campo_customizado_valor_caractere TEXT;
ALTER TABLE campo_customizado_valor CHANGE valor_inteiro campo_customizado_valor_inteiro INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_customizado_valor ADD KEY campo_customizado_valor_campo (campo_customizado_valor_campo);


ALTER TABLE campo_customizado_lista DROP FOREIGN KEY campo_customizado_lista_campo;

ALTER TABLE campo_formulario DROP KEY campo_formulario_customizado_id;
ALTER TABLE campo_formulario DROP FOREIGN KEY campo_formulario_customizado;



ALTER TABLE campo_customizado CHANGE campo_id campo_customizado_id INTEGER(100) UNSIGNED NOT NULL;

ALTER TABLE campo_customizado_valor ADD CONSTRAINT campo_customizado_valor_campo FOREIGN KEY (campo_customizado_valor_campo) REFERENCES campo_customizado (campo_customizado_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE campo_customizado_lista ADD CONSTRAINT campo_customizado_lista_campo FOREIGN KEY (campo_customizado_lista_campo) REFERENCES campo_customizado (campo_customizado_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE campo_formulario CHANGE campo_formulario_customizado_id campo_formulario_customizado INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_formulario ADD KEY campo_formulario_customizado (campo_formulario_customizado);
ALTER TABLE campo_formulario ADD CONSTRAINT campo_formulario_customizado FOREIGN KEY (campo_formulario_customizado) REFERENCES campo_customizado (campo_customizado_id) ON DELETE CASCADE ON UPDATE CASCADE;
