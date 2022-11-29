SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.19';
UPDATE versao SET ultima_atualizacao_bd='2018-09-21';
UPDATE versao SET ultima_atualizacao_codigo='2018-09-21';
UPDATE versao SET versao_bd=509;

ALTER TABLE ata_config ADD COLUMN ata_config_tipo_numeracao INTEGER(10) DEFAULT 0;

CALL PROC_DROP_FOREIGN_KEY('ata','ata_projeto');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_tarefa');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_pratica');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_meta');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_perspectiva');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_tema');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_objetivo');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_fator');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_indicador');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_estrategia');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_calendario');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_acao');
CALL PROC_DROP_FOREIGN_KEY('ata','ata_canvas');

CALL PROC_DROP_KEY('ata', 'ata_projeto');
CALL PROC_DROP_KEY('ata', 'ata_tarefa');
CALL PROC_DROP_KEY('ata', 'ata_pratica');
CALL PROC_DROP_KEY('ata', 'ata_meta');
CALL PROC_DROP_KEY('ata', 'ata_perspectiva');
CALL PROC_DROP_KEY('ata', 'ata_tema');
CALL PROC_DROP_KEY('ata', 'ata_objetivo');
CALL PROC_DROP_KEY('ata', 'ata_fator');
CALL PROC_DROP_KEY('ata', 'ata_indicador');
CALL PROC_DROP_KEY('ata', 'ata_estrategia');
CALL PROC_DROP_KEY('ata', 'ata_calendario');
CALL PROC_DROP_KEY('ata', 'ata_monitoramento');
CALL PROC_DROP_KEY('ata', 'ata_acao');
CALL PROC_DROP_KEY('ata', 'ata_canvas');