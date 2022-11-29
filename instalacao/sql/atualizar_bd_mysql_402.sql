SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.04';
UPDATE versao SET ultima_atualizacao_bd='2017-04-20';
UPDATE versao SET ultima_atualizacao_codigo='2017-04-20';
UPDATE versao SET versao_bd=402;

ALTER TABLE preferencia DROP COLUMN formatohora;
ALTER TABLE preferencia DROP COLUMN datacurta;


ALTER TABLE contatos ADD COLUMN contato_foto VARCHAR(255) DEFAULT NULL;

ALTER TABLE contatos DROP COLUMN contato_dddtel;
ALTER TABLE contatos DROP COLUMN contato_dddtel2;
ALTER TABLE contatos DROP COLUMN contato_dddfax;
ALTER TABLE contatos DROP COLUMN contato_fax;
ALTER TABLE contatos DROP COLUMN contato_dddcel;

UPDATE contatos SET contato_nascimento=NULL WHERE contato_nascimento='0000-00-00';

ALTER TABLE contatos ADD COLUMN contato_ativo INTEGER(1) DEFAULT 1;
ALTER TABLE contatos ADD COLUMN contato_religiao INTEGER(10) DEFAULT 0;
ALTER TABLE contatos ADD COLUMN contato_sangue varchar(3) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_vivo INTEGER(1) DEFAULT 1;
ALTER TABLE contatos ADD COLUMN contato_natural_cidade VARCHAR(7) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_natural_estado VARCHAR(2) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_natural_pais VARCHAR(30) DEFAULT 'BR';
ALTER TABLE contatos ADD COLUMN contato_grau_instrucao INTEGER(10) DEFAULT 0;
ALTER TABLE contatos ADD COLUMN contato_formacao VARCHAR(100) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_profissao VARCHAR(100) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_ocupacao VARCHAR(100) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_especialidade VARCHAR(100) DEFAULT NULL;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('contato','contato_nascimento','Nascimento',1),
	('contato','contato_funcao','Função',1),
	('contato','contato_codigo','Código',1),
	('contato','contato_tipo','Tipo',1),
	('contato','contato_matricula','Matrícula',1),
	('contato','contato_identidade','Identidade',1),
	('contato','contato_cpf','CPF',1),
	('contato','contato_cnpj','CNPJ',1),
	('contato','contato_email','E-mail',1),
	('contato','contato_email2','E-mail alternativo',1),
	('contato','contato_url','Página web',1),
	('contato','contato_tel','Telefone comercial',1),
	('contato','contato_tel2','Telefone residencial',1),
	('contato','contato_cel','Celular',1),
	('contato','contato_endereco','Endereço',1),
	('contato','contato_notas','Observação',1),
	('contato','contato_skype','Skype',1),
	('contato','contato_religiao','Religião',0),
	('contato','contato_sangue','Sangue',0),
	('contato','contato_vivo','Vivo',0),
	('contato','contato_natural_cidade','Município de nascimento',0),
	('contato','contato_grau_instrucao','Grau de instrução',0),
	('contato','contato_formacao','Formação',0),
	('contato','contato_profissao','Profissão',0),
	('contato','contato_ocupacao','Ocupação',0),
	('contato','contato_especialidade','Especialidade',0),
	('contato','contato_hora_custo','Custo hora',1),
	('contato','contato_foto','Foto',1);



INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('Religiao','Católica apostólica romana','1', NULL),
	('Religiao','Católica apostólica brasileira','2', NULL),
	('Religiao','Católica ortodoxa','3', NULL),
	('Religiao','Evangélica','4', NULL),
	('Religiao','Igreja evangélica luterana','5', NULL),
	('Religiao','Igreja evangélica presbiteriana','6', NULL),
	('Religiao','Igreja evangélica metodista','7', NULL),
	('Religiao','Igreja evangélica batista','8', NULL),
	('Religiao','Igreja evangélica congregacional','9', NULL),
	('Religiao','Igreja evangélica adventista','10', NULL),
	('Religiao','Evangélicas pentecostal','11', NULL),
	('Religiao','Igreja assembleia de deus','12', NULL),
	('Religiao','Igreja congregação cristã do brasil','13', NULL),
	('Religiao','Igreja o brasil para cristo','14', NULL),
	('Religiao','Igreja evangelho quadrangular','15', NULL),
	('Religiao','Igreja universal do reino de deus','16', NULL),
	('Religiao','Igreja casa da benção','17', NULL),
	('Religiao','Igreja deus é amor','18', NULL),
	('Religiao','Igreja maranata','19', NULL),
	('Religiao','Igreja nova vida','20', NULL),
	('Religiao','Evangélica renovada não determinada','21', NULL),
	('Religiao','Comunidade evangélica','22', NULL),
	('Religiao','Outras religiosidades cristãs','23', NULL),
	('Religiao','Igreja de jesus cristo dos santos dos últimos dias','24', NULL),
	('Religiao','Testemunhas de jeová','25', NULL),
	('Religiao','Espiritualista','26', NULL),
	('Religiao','Espírita','27', NULL),
	('Religiao','Espírita kardecista','28', NULL),
	('Religiao','Umbanda','29', NULL),
	('Religiao','Candomblé','30', NULL),
	('Religiao','Religiosidades afro-brasileira','31', NULL),
	('Religiao','Judaísmo','32', NULL),
	('Religiao','Hinduísmo','33', NULL),
	('Religiao','Budismo','34', NULL),
	('Religiao','Religião oriental','35', NULL),
	('Religiao','Igreja messiânica mundial','36', NULL),
	('Religiao','Islamismo','37', NULL),
	('Religiao','Tradições esotéricas','38', NULL),
	('Religiao','Tradições indígenas','39', NULL),
	('Religiao','Sem religião','40', NULL),
	('Religiao','Ateu','41', NULL),
	('Religiao','Agnóstico','42', NULL),
	('Religiao','Não sabe','43', NULL),
	('Religiao','Sem declaração','44', NULL),
	('Sangue','O+','1', NULL),
	('Sangue','A+','2', NULL),
	('Sangue','B+','3', NULL),
	('Sangue','AB+','4', NULL),
	('Sangue','O-','5', NULL),
	('Sangue','A-','6', NULL),
	('Sangue','B-','7', NULL),
	('Sangue','AB-','8', NULL),
	('Escolaridade','Analfabeto','1', NULL),
	('Escolaridade','Analfabeto funcional','2', NULL),
	('Escolaridade','Ensino fundamental incompleto','3', NULL),
	('Escolaridade','Ensino fundamental','4', NULL),
	('Escolaridade','Ensino médio incompleto','5', NULL),
	('Escolaridade','Ensino médio','6', NULL),
	('Escolaridade','Superior incompleto','7', NULL),
	('Escolaridade','Tecnologia','8', NULL),
	('Escolaridade','Licenciatura','9', NULL),
	('Escolaridade','Bacharelado','10', NULL),
	('Escolaridade','Pós-graduação','11', NULL),
	('Escolaridade','Mestrado ','12', NULL),
	('Escolaridade','Doutorado','13', NULL),
	('Escolaridade','Pós doutorado','14', NULL);
	
	








