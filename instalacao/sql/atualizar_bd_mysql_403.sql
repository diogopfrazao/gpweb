SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.5.04';
UPDATE versao SET ultima_atualizacao_bd='2017-04-20';
UPDATE versao SET ultima_atualizacao_codigo='2017-04-20';
UPDATE versao SET versao_bd=403;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('contatos','contato_dept','Seção',1),
	('contatos','contato_nascimento','Nascimento',1),	
	('contatos','contato_funcao','Função',1),
	('contatos','contato_codigo','Código',1),
	('contatos','contato_tipo','Tipo',1),
	('contatos','contato_matricula','Matrícula',1),
	('contatos','contato_identidade','Identidade',1),
	('contatos','contato_cpf','CPF',1),
	('contatos','contato_cnpj','CNPJ',1),
	('contatos','contato_email','E-mail',1),
	('contatos','contato_email2','E-mail alternativo',0),
	('contatos','contato_url','Página web',0),
	('contatos','contato_tel','Telefone comercial',1),
	('contatos','contato_tel2','Telefone residencial',1),
	('contatos','contato_cel','Celular',1),
	('contatos','contato_endereco','Endereço',1),
	('contatos','contato_notas','Observação',1),
	('contatos','contato_skype','Skype',1),
	('contatos','contato_religiao','Religião',0),
	('contatos','contato_sangue','Sangue',0),
	('contatos','contato_vivo','Vivo',0),
	('contatos','contato_natural_cidade','Município de nascimento',0),
	('contatos','contato_grau_instrucao','Grau de instrução',0),
	('contatos','contato_formacao','Formação',0),
	('contatos','contato_profissao','Profissão',0),
	('contatos','contato_ocupacao','Ocupação',0),
	('contatos','contato_especialidade','Especialidade',0),
	('contatos','contato_hora_custo','Custo hora',1),
	('contatos','contato_foto','Foto',0);
