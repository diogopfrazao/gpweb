<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$contato_id=getParam($_REQUEST, 'contato_id', null);
$cia_id=getParam($_REQUEST, 'cia_id', null);
$dept_id=getParam($_REQUEST, 'dept_id', null);
$usuario_id=usuario_id($contato_id);
$cia_nome=getParam($_REQUEST, 'cia_nome', null);
$dept_nome=getParam($_REQUEST, 'dept_nome', null);


$privado = ((($Aplic->getEstado('filtro_id_responsavel') ? $Aplic->getEstado('filtro_id_responsavel') : 0) == $Aplic->usuario_id) && !$usuario_id);

$obj = new CContato();
$obj->load($contato_id);

$podeEditar = ($Aplic->checarModulo('contatos', 'editar') && (($usuario_id==$Aplic->usuario_id) || ($obj->contato_privado && $obj->contato_dono == $Aplic->usuario_id) || !$obj->contato_privado));

if (!$contato_id  && !$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($contato_id && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$ehUsuario=$obj->ehUsuario();

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'contato\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



$botoesTitulo = new CBlocoTitulo(($contato_id > 0 ? 'Editar ' : 'Adicionar ').ucfirst($config['contato']), 'contatos.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


//$paises = getPais('Paises');
$posto=array();
if ($config['militar'] < 10) $posto+= getSisValor('Posto'.$config['militar']);
else $posto+= getSisValor('PronomeTratamento');

$arma=array(0 => '');
$arma+= getSisValor('Arma'.$config['militar']);

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="contatos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_contato_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="contato_atualizacao_exclusiva" value="'.uniqid('').'" />';
echo '<input type="hidden" name="contato_id" id="contato_id" value="'.$contato_id.'" />';
echo '<input type="hidden" name="contato_posto_valor" value="'.($obj->contato_posto_valor ? (int)$obj->contato_posto_valor : 0).'" />';
echo '<input type="hidden" name="contato_dono" value="'.($obj->contato_dono ? $obj->contato_dono : $Aplic->usuario_id).'" />';
echo '<input type="hidden" name="contato_foto" value="'.$obj->contato_foto.'" />';
echo '<input type="hidden" name="excluir_foto" id="excluir_foto" value="" />';
echo '<input type="hidden" name="existe_identidade" id="existe_identidade" value="" />';
echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';


echo '<tr><td align="right" style="white-space: nowrap">'.dica(($config['militar'] < 10 ? 'Posto/Grad' : 'Pronome de Tratamento'), 'Selecione '.($config['militar'] < 10 ? 'o posto/gradua��o' : 'o pronome de tratamento').' d'.$config['genero_contato'].' '.$config['contato'].'.').($config['militar'] < 10 ? 'Posto/Grad:' : 'Pronome de tratamento:').dicaF().'</td><td>'.selecionaVetor($posto, 'contato_posto', 'class="texto" size=1', $obj->contato_posto, true).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.($config['militar'] < 10 ? dica('Nome de Guerra', 'Escreva o nome de guerra d'.$config['genero_contato'].' '.$config['contato'].'.').'Nome de guerra:'.dicaF() : dica('Nome', 'Escreva o nome d'.$config['genero_contato'].' '.$config['contato'].'.').'Nome:'.dicaF()).'</td><td><input type="text" class="texto" style="width:300px;" name="contato_nomeguerra" value="'.$obj->contato_nomeguerra.'" maxlength="30" /></td></tr>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome Completo', 'Nome completo d'.$config['genero_contato'].' '.$config['contato'].'.').'Nome completo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_nomecompleto" value="'.$obj->contato_nomecompleto.'" style="width:300px;" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']), 'Escolha '.($config['genero_organizacao']=='a' ? 'a' : 'ao').' qual '.$config['organizacao'].' pertence '.$config['genero_contato'].' '.$config['contato'].'.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'contato_cia', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"').'</div></td></tr></table></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']), 'Escolha pressionando o �cone � direita a qual '.$config['genero_dept'].' '.$config['dept'].' d'.$config['genero_contato'].' '.$config['contato'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td><input type="hidden" name="contato_dept" id="contato_dept" value="'.($contato_id ? $obj->contato_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($contato_id ? $obj->contato_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

if ($exibir['contato_funcao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cargo/Fun��o', 'O cargo/fun��o d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Fun��o:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" class="texto" name="contato_funcao" value="'.$obj->contato_funcao.'" maxlength="100" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_funcao" value="'.$obj->contato_funcao.'" />';

if ($config['militar']<10) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv d'.$config['genero_contato'].' '.$config['contato'].'.').'Arma/Quadro/Sv:'.dicaF().'</td><td>'.selecionaVetor($arma, 'contato_arma', 'class="texto" style="width:300px;" size=1', $obj->contato_arma, true).'</td></tr>';
if ($exibir['contato_tipo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo', 'O tipo d'.$config['genero_contato'].' '.$config['contato'].'.').'Tipo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_tipo" value="'.$obj->contato_tipo.'" maxlength="50" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_tipo" value="'.$obj->contato_tipo.'" />';

if ($exibir['contato_codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('C�digo', 'O c�digo d'.$config['genero_contato'].' '.$config['contato'].'.').'C�digo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_codigo" value="'.(isset($obj->contato_codigo) ? $obj->contato_codigo : '').'" style="width:300px;" maxlength="255" /></td></tr>';
else echo '<input type="hidden" name="contato_codigo" value="'.$obj->contato_codigo.'" />';

if ($exibir['contato_matricula']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Matr�cula', 'A matr�cula d'.$config['genero_contato'].' '.$config['contato'].'.').'Matr�cula:'.dicaF().'</td><td><input type="text" class="texto" name="contato_matricula" value="'.(isset($obj->contato_matricula) ? $obj->contato_matricula : '').'" style="width:300px;" maxlength="255" /></td></tr>';
else echo '<input type="hidden" name="contato_matricula" value="'.$obj->contato_matricula.'" />';

if ($exibir['contato_identidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Identidade', 'A identidade d'.$config['genero_contato'].' '.$config['contato'].'.').($config['id_usuario_identidade'] ? '* ' : '').'Identidade:'.dicaF().'</td><td><input type="text" class="texto" name="contato_identidade" id="contato_identidade" value="'.$obj->contato_identidade.'" maxlength="25" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_identidade" value="'.$obj->contato_identidade.'" />';

if ($exibir['contato_cpf']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('CPF', 'O CPF d'.$config['genero_contato'].' '.$config['contato'].'.').'CPF:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cpf" value="'.$obj->contato_cpf.'" maxlength="14" style="width:300px;" onchange="verificarCPF()" /></td></tr>';
else echo '<input type="hidden" name="contato_cpf" value="'.$obj->contato_cpf.'" />';

if ($exibir['contato_cnpj']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('CNPJ', 'O CNPJ d'.$config['genero_contato'].' '.$config['contato'].'.').'CNPJ:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cnpj" value="'.$obj->contato_cnpj.'" maxlength="18" style="width:300px;" onchange="verificarCNPJ()" /></td></tr>';
else echo '<input type="hidden" name="contato_cnpj" value="'.$obj->contato_cnpj.'" />';

if ($exibir['contato_endereco']) {
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Endere�o', 'O ender�o d'.$config['genero_contato'].' '.$config['contato'].'.').'Endere�o:'.dicaF().'</td><td><input type="text" class="texto" name="contato_endereco1" value="'.$obj->contato_endereco1.'" maxlength="60" style="width:300px;" /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Complemento do Endere�o', 'O complemento do ender�o d'.$config['genero_contato'].' '.$config['contato'].'.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="contato_endereco2" value="'.$obj->contato_endereco2.'" maxlength="60" style="width:300px;" /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Estado', 'O Estado d'.$config['genero_contato'].' '.$config['contato'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'contato_estado', 'class="texto" size=1 style="width:300px;" onchange="mudar_cidades(\'contato_estado\', \'contato_cidade\', \'combo_cidade\');"', $obj->contato_estado).'</tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Munic�pio', 'O munic�pio d'.$config['genero_contato'].' '.$config['contato'].'.').'Munic�pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($obj->contato_estado, 'contato_cidade', 'class="texto" style="width:300px;"', '', $obj->contato_cidade, true, false).'</div></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('CEP', 'O CEP d'.$config['genero_contato'].' '.$config['contato'].'.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cep" value="'.$obj->contato_cep.'" maxlength="11" style="width:300px;" /></td></tr>';
	//echo '<tr><td align="right" style="white-space: nowrap">'.dica('Pa�s', 'O pa�s d'.$config['genero_contato'].' '.$config['contato'].'.').'Pa�s:'.dicaF().'</td><td>'.selecionaVetor($paises, 'contato_pais', 'size="1" style="width:300px;" class="texto"',($obj->contato_pais ? $obj->contato_pais : 'BR')).'</td></tr>';
	echo '<input type="hidden" name="contato_pais" value="'.($contato_id ? $obj->contato_pais : 'BR').'" />';
	}
else {
	echo '<input type="hidden" name="contato_endereco1" value="'.$obj->contato_endereco1.'" />';
	echo '<input type="hidden" name="contato_endereco2" value="'.$obj->contato_endereco2.'" />';
	echo '<input type="hidden" name="contato_estado" value="'.$obj->contato_estado.'" />';
	echo '<input type="hidden" name="contato_cidade" value="'.$obj->contato_cidade.'" />';
	echo '<input type="hidden" name="contato_cep" value="'.$obj->contato_cep.'" />';
	echo '<input type="hidden" name="contato_pais" value="'.($contato_id ? $obj->contato_pais : 'BR').'" />';
	}

if ($exibir['contato_tel']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Telefone Comercial', 'O telefone comercial d'.$config['genero_contato'].' '.$config['contato'].'.').'Telefone Comercial:'.dicaF().'</td><td><input type="text" class="texto" name="contato_tel" value="'.$obj->contato_tel.'" maxlength="30" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_tel" value="'.$obj->contato_tel.'" />';

if ($exibir['contato_tel2']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Telefone Residencial', 'O telefone residencial d'.$config['genero_contato'].' '.$config['contato'].'.').'Telefone Residencial:'.dicaF().'</td><td><input type="text" class="texto" name="contato_tel2" value="'.$obj->contato_tel2.'" maxlength="30" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_tel2" value="'.$obj->contato_tel2.'" />';

if ($exibir['contato_cel']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Celular', 'O celular d'.$config['genero_contato'].' '.$config['contato'].'.').'Celular:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cel" value="'.$obj->contato_cel.'" maxlength="30" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_cel" value="'.$obj->contato_cel.'" />';

if ($exibir['contato_email']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('E-mail', 'O e-mail d'.$config['genero_contato'].' '.$config['contato'].'.<br><br>Embora n�o tenha impacto no funcionamento do Sistema, exceto nas situa��es em que o mesmo envia mensagens para contatos, facilita a organiza��o dos contatos quando se trabalha com diversas '.$config['organizacao'].'.').'E-mail:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" class="texto" name="contato_email" value="'.$obj->contato_email.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_email" value="'.$obj->contato_email.'" />';

if ($exibir['contato_email2']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('E-mail Alternativo', 'O e-mail alternativo d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail alternativo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_email2" value="'.$obj->contato_email2.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_email2" value="'.$obj->contato_email2.'" />';

if ($exibir['contato_url']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('P�gina Web', 'Escreva a p�gina Web d'.$config['genero_contato'].' '.$config['contato'].'.').'P�gina Web:'.dicaF().'</td><td><input type="text" class="texto" name="contato_url" value="'.$obj->contato_url.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_url" value="'.$obj->contato_url.'" />';

if ($exibir['contato_skype']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Skype', 'O Skype d'.$config['genero_contato'].' '.$config['contato'].'.').'Skype:'.dicaF().'</td><td><input type="text" class="texto" name="contato_skype" value="'.$obj->contato_skype.'" maxlength="100" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_skype" value="'.$obj->contato_skype.'" />';

$data_inicio = intval($obj->contato_nascimento) ? new CData($obj->contato_nascimento) :  new CData(date("Y-m-d"));
if ($exibir['contato_nascimento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nascimento', 'Digite ou escolha no calend�rio a data de nascimento.').'Nascimento:'.dicaF().'</td><td style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="contato_nascimento" id="contato_nascimento" value="'.($data_inicio ? $data_inicio->format("%Y-%m-%d") : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'contato_nascimento\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data de In�cio', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de in�cio.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" />'.dicaF().'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="contato_nascimento" value="'.$obj->contato_nascimento.'" />';






if ($exibir['contato_religiao']) {
	$religiao=array(null=>'')+getSisValor('Religiao');
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Religi�o', 'A religiao d'.$config['genero_contato'].' '.$config['contato'].'.').'Religi�o:'.dicaF().'</td><td style="text-align: left;">'.selecionaVetor($religiao, 'contato_religiao', 'class="texto" style="width:300px;" size=1', $obj->contato_religiao).'</td></tr>';
	}
else echo '<input type="hidden" name="contato_religiao" value="'.$obj->contato_religiao.'" />';

if ($exibir['contato_sangue']) {
	$sangue=array(null=>'')+getSisValor('Sangue');
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Sangue', 'O tipo sangu�nio d'.$config['genero_contato'].' '.$config['contato'].'.').'Sangue:'.dicaF().'</td><td style="text-align: left;">'.selecionaVetor($sangue, 'contato_sangue', 'class="texto" style="width:300px;" size=1', $obj->contato_sangue).'</td></tr>';
	}
else echo '<input type="hidden" name="contato_sangue" value="'.$obj->contato_sangue.'" />';


if ($exibir['contato_natural_cidade']) {
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Estado de Nascimento', 'Escolha na caixa de sele��o � direita o estado de nascimento d'.$config['genero_contato'].' '.$config['contato'].'.').'Estado de nascimento:'.dicaF().'</td><td>'.selecionaVetor($estado, 'contato_natural_estado', 'class="texto" size=1 style="width:300px;" onchange="mudar_cidades(\'contato_natural_estado\', \'contato_natural_cidade\', \'combo_cidade_natural\');"', $obj->contato_natural_estado).'</tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Munic�pio de Nascimebto', 'Selecione o munic�pio de nascimento d'.$config['genero_contato'].' '.$config['contato'].'.').'Munic�pio de nascimento:'.dicaF().'</td><td><div id="combo_cidade_natural">'.selecionar_cidades_para_ajax($obj->contato_natural_estado, 'contato_natural_cidade', 'class="texto" style="width:300px;"', '', $obj->contato_natural_cidade, true, false).'</div></td></tr>';
	}
else {
	echo '<input type="hidden" name="contato_natural_estado" value="'.$obj->contato_natural_estado.'" />';
	echo '<input type="hidden" name="contato_natural_cidade" value="'.$obj->contato_natural_cidade.'" />';
	}
	
if ($exibir['contato_grau_instrucao']) {
	$escolaridade=array(null=>'')+getSisValor('Escolaridade');
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Grau de Instrucao', 'O Grau de instru��o d'.$config['genero_contato'].' '.$config['contato'].'.').'Grau de instru��o:'.dicaF().'</td><td style="text-align: left;">'.selecionaVetor($escolaridade, 'contato_grau_instrucao', 'class="texto" style="width:300px;" size=1', $obj->contato_grau_instrucao).'</td></tr>';
	}
else echo '<input type="hidden" name="contato_grau_instrucao" value="'.$obj->contato_grau_instrucao.'" />';

if ($exibir['contato_formacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Forma��o', 'A forma��o d'.$config['genero_contato'].' '.$config['contato'].'.').'Forma��o:'.dicaF().'</td><td style="text-align: left;"><input type="text" class="texto" name="contato_formacao" value="'.$obj->contato_formacao.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_formacao" value="'.$obj->contato_formacao.'" />';

if ($exibir['contato_profissao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Profiss�o', 'A profiss�o d'.$config['genero_contato'].' '.$config['contato'].'.').'Profiss�o:'.dicaF().'</td><td style="text-align: left;"><input type="text" class="texto" name="contato_profissao" value="'.$obj->contato_profissao.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_profissao" value="'.$obj->contato_profissao.'" />';

if ($exibir['contato_ocupacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ocupa��o', 'A ocupa��o d'.$config['genero_contato'].' '.$config['contato'].'.').'Ocupa��o:'.dicaF().'</td><td style="text-align: left;"><input type="text" class="texto" name="contato_ocupacao" value="'.$obj->contato_ocupacao.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_ocupacao" value="'.$obj->contato_ocupacao.'" />';

if ($exibir['contato_especialidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Especialidade', 'A especialidade d'.$config['genero_contato'].' '.$config['contato'].'.').'Especialidade:'.dicaF().'</td><td style="text-align: left;"><input type="text" class="texto" name="contato_especialidade" value="'.$obj->contato_especialidade.'" maxlength="255" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_especialidade" value="'.$obj->contato_especialidade.'" />';

if ($exibir['contato_hora_custo'] && $Aplic->checarModulo('usuarios', 'editar', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_contato'].' '.$config['contato'].'.').'Custo hora:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="contato_hora_custo" value="'.number_format($obj->contato_hora_custo, 2, ',', '.').'" maxlength="100" style="width:300px;" /></td></tr>';
else echo '<input type="hidden" name="contato_hora_custo" value="'.number_format($obj->contato_hora_custo, 2, ',', '.').'" />';

if ($exibir['contato_notas']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Observa��o', 'Escreva informa��es extras sobre '.$config['genero_contato'].' '.$config['contato'].'.').'Observa��o: '.dicaF().'</td><td><textarea class="texto" name="contato_notas" data-gpweb-cmp="ckeditor" rows="4" cols="40">'.$obj->contato_notas.'</textarea></td></td></tr>';
else echo '<input type="hidden" name="contato_notas" value="'.$obj->contato_notas.'" />';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $obj->contato_id, 'editar');
$campos_customizados->imprimirHTML();

if ($exibir['contato_vivo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viv'.$config['genero_contato'], 'Se '.$config['genero_contato'].' '.$config['contato'].' se encontra viv'.$config['genero_contato'].'.').'Viv'.$config['genero_contato'].':'.dicaF().'</td><td style="text-align: left;"><input type="checkbox" value="1" name="contato_vivo" id="contato_vivo" '.($obj->contato_vivo || !$contato_id ? 'checked="checked"' : '').' /></td></tr>';
else echo '<input type="hidden" name="contato_vivo" value="'.($obj->contato_vivo || !$contato_id ? 1 : 0).'" />';

if (!$ehUsuario) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ativ'.$config['genero_contato'], 'Se '.$config['genero_contato'].' '.$config['contato'].' est� ativ'.$config['genero_contato'].'.').'Ativ'.$config['genero_contato'].':'.dicaF().'</td><td style="text-align: left;"><input type="checkbox" value="1" name="contato_ativo" id="contato_ativo" '.($obj->contato_ativo || !$contato_id ? 'checked="checked"' : '').' /></td></tr>';
else {
	$sql->adTabela('usuarios');
	$sql->adCampo('usuario_ativo');
	$sql->adOnde('usuario_id='.(int)$ehUsuario);
	$usuario_ativo= $sql->resultado();
	$sql->limpar();
	echo '<input type="hidden" name="contato_ativo" value="'.$usuario_ativo.'" />';
	}	
	
if ($usuario_id!=$Aplic->usuario_id){
	if ($Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align="right" width="100" style="white-space: nowrap">'.dica('Solicitar Atualiza��o', 'Marque esta caixa caso deseje que seje enviado um e-mail para '.$config['genero_contato'].' '.$config['contato'].' solicitando que o mesmo atualize seu cadastro.').'Solicitar atualiza��o:'.dicaF().'</td><td style="text-align: left;"><input type="checkbox" value="1" name="contato_atualizarSolicitado" '.($obj->contato_chave_atualizacao ? 'checked="checked"' : '').' onclick="verificarAtualizacao()"/></td></tr>';
	if (($Aplic->usuario_super_admin || $Aplic->usuario_admin) && $obj->contato_pedido_atualizacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('�ltima Solicita��o', 'Data da �ltima solicita��o para que '.$config['genero_contato'].' '.$config['contato'].' mesmo atualize seu cadastro.').'�ltima solicita��o:'.dicaF().'</td><td style="text-align: left;">'.retorna_data($obj->contato_pedido_atualizacao, false).'</td></tr>';
	if ($obj->contato_ultima_atualizacao) echo '<tr><td align="right" width="100" style="white-space: nowrap">'.dica('�ltima Atualiza��o', 'Data da �ltima atualiza��o do cadastro.').'�ltima atualiza��o:'.dicaF().'</td><td style="text-align: left;">'.retorna_data($obj->contato_ultima_atualizacao, false).'</td></tr>	';
	}
if (true) echo '<tr><td align="right" style="white-space: nowrap"><label for="contato_privado">'.dica('Contato Privado', 'Marque esta op��o caso deseje que somente voc� possa visualizar este contato.').'Contato privado:'.dicaF().'</label></td><td><input type="checkbox" value="1" name="contato_privado" id="contato_privado" '.($obj->contato_privado || $privado ? 'checked="checked"' : '').' /></td></tr>';


if($exibir['contato_foto']){
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Foto','Clique no bot�o de escolher arquivo para selecionar uma foto no formato 3X4 com tamanho m�ximo de 150KB.').'Foto:'.dicaF().'</td><td><input type="File" class="arquivo" name="logo" size="59" /></td></tr>';
	
	if ($obj->contato_foto) {
		echo '<tr><td></td><td align=left><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/contatos/'.$obj->contato_foto.'" width=100 height=133 /></td></tr>';
		echo '<tr><td></td><td align=left>'.botao('excluir','Excluir','Clique neste bot�o para excluir a foto atual.','','excluir_foto()').'</td></tr>';
		}
	
	
	}



	
			
echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviar()').'</td><td colspan="2" align="right">'.botao('voltar', 'Voltar', 'Retornar � tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';


?>

<script type="text/javascript">

function excluir_foto(){
	document.getElementById("excluir_foto").value=1;
	enviar();
	}

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "contato_nascimento",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("contato_nascimento").value = Calendario.printDate(date, "%Y-%m-%d");
	    }
		cal1.hide();
		}
	});


function setData( frm_nome, f_data,  f_data_real){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}

function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}

function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 39 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}


function mudar_cidades(campo_estado, campo, combo){
	xajax_selecionar_cidades_ajax(document.getElementById(campo_estado).value,campo,combo, 'class="texto" size=1 style="width:300px;"', document.getElementById(campo).value);
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('contato_dept').value+'&cia_id='+document.getElementById('contato_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('contato_dept').value+'&cia_id='+document.getElementById('contato_cia').value, '<?php echo ucfirst($config["departamento"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('contato_cia').value=cia_id;
	document.getElementById('contato_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


function mudar_om(){
	document.getElementById('contato_dept').value=null;
	document.getElementById('dept_nome').value='';
	xajax_selecionar_om_ajax(document.getElementById('contato_cia').value, 'contato_cia','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"','&nbsp;',1);
	}

function enviar() {
	var form = document.env;
	var nomeposto=Array("<?php echo implode("\",\"",$posto)?>");
	var valorposto=Array();

	xajax_existe_identidade_ajax(document.getElementById('contato_identidade').value, document.getElementById('contato_id').value);

	<?php foreach ($posto as $valor_posto=> $nome) echo ($nome ? 'valorposto["'.$nome.'"]='.(int)$valor_posto.'; ' : ''); ?>;

		if (form.contato_nascimento.value=='<?php echo date("Y-m-d")?>') {
    form.contato_nascimento.value=null;
		}


	
	

	if (form.contato_nomeguerra.value.length < 3) {
		alert( 'Por favor insira um nome v�lido.' );
		form.contato_nomeguerra.focus();
		}

	else if (form.existe_identidade.value==1) {
    alert('O n�mero de identidade j� existe cadastrado!');
    form.contato_identidade.focus();
		}

	<?php if ($usuario_id!=$Aplic->usuario_id){ ?>
	else if (form.contato_email.value.length < 3 && form.contato_atualizarSolicitado.checked) {
		alert('Necessita inserir um e-mail v�lido antes de utilizar a fun��o de avisar sobre atualiza��o.' );
		form.contato_email.focus();
		}
	<?php }?>

	<?php	if ($config['id_usuario_identidade']) { ?>
  	else if (form.contato_identidade.value.length < 8) {
	    alert("A identidade dever� ser preenchida corretamente");
	    form.contato_identidade.focus();
			}
	<?php } ?>


	else {
		form.contato_posto_valor.value=(valorposto[form.contato_posto.value] != 'undefined' ? valorposto[form.contato_posto.value] : null);

		form.contato_hora_custo.value=moeda2float(form.contato_hora_custo.value);

		form.submit();
		}
	}



function verificarAtualizacao() {
	var form = document.env;
	if (form.contato_email.value.length < 3 && form.contato_atualizarSolicitado.checked) {
		alert('Necessita inserir um e-mail v�lido antes de utilizar esta fun��o');
		form.contato_atualizarSolicitado.checked = false;
		form.contato_email.focus();
		}
	}

function barra(objeto){
	if (objeto.value.length == 2 || objeto.value.length ==5) objeto.value = objeto.value+"/";
	}



var NUM_DIGITOS_CPF = 11;
var NUM_DIGITOS_CNPJ = 14;
var NUM_DGT_CNPJ_BASE = 8;

String.prototype.lpad = function (pSize, pCharPad) {
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif > 0; dif--) str = ch + str;
	return (str);
	}
String.prototype.trim = function () {
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
	}

function unformatNumber(pNum) {
	return String(pNum).replace(/\D/g, "").replace(/^0+/, "");
	}

function formatCpfCnpj(pCpfCnpj, pUseSepar, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	if (pUseSepar == null) pUseSepar = true;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var numero = unformatNumber(pCpfCnpj);
	numero = numero.lpad(maxDigitos, '0');
	if (!pUseSepar) return numero;
	if (pIsCnpj) {
		reCnpj = /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/;
		numero = numero.replace(reCnpj, "$1.$2.$3/$4-$5")
		}
	else {
		reCpf = /(\d{3})(\d{3})(\d{3})(\d{2})$/;
		numero = numero.replace(reCpf, "$1.$2.$3-$4")
		}
	return numero
	}

function dvCpfCnpj(pEfetivo, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	var i, j, k, soma, dv;
	var cicloPeso = pIsCnpj ? NUM_DGT_CNPJ_BASE : NUM_DIGITOS_CPF;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var calculado = formatCpfCnpj(pEfetivo + "00", false, pIsCnpj);
	calculado = calculado.substring(0, maxDigitos - 2);
	var result = "";
	for (j = 1; j <= 2; j++) {
		k = 2;
		soma = 0;
		for (i = calculado.length - 1; i >= 0; i--) {
			soma += (calculado.charAt(i) - '0') * k;
			k = (k - 1) % cicloPeso + 2
			}
		dv = 11 - soma % 11;
		if (dv > 9) dv = 0;
		calculado += dv;
		result += dv
		}
	return result
	}

function isCpf(pCpf) {
	var numero = formatCpfCnpj(pCpf, false, false);
	if (numero.length > NUM_DIGITOS_CPF) return false;
	var base = numero.substring(0, numero.length - 2);
	var digitos = dvCpfCnpj(base, false);
	var algUnico, i;
	if (numero != "" + base + digitos) return false;
	algUnico = true;
	for (i = 1; algUnico && i < NUM_DIGITOS_CPF; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	return (!algUnico);
	}

function isCnpj(pCnpj) {
	var numero = formatCpfCnpj(pCnpj, false, true);
	if (numero.length > NUM_DIGITOS_CNPJ) return false;
	var base = numero.substring(0, NUM_DGT_CNPJ_BASE);
	var ordem = numero.substring(NUM_DGT_CNPJ_BASE, 12);
	var digitos = dvCpfCnpj(base + ordem, true);
	var algUnico;
	if (numero != "" + base + ordem + digitos) return false;
	algUnico = numero.charAt(0) != '0';
	for (i = 1; algUnico && i < NUM_DGT_CNPJ_BASE; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	if (algUnico) return false;
	if (ordem == "0000") return false;
	return (base == "00000000" || parseInt(ordem, 10) <= 300 || base.substring(0, 3) != "000");
	}

function isCpfCnpj(pCpfCnpj) {
	var numero = pCpfCnpj.replace(/\D/g, "");
	if (numero.length > NUM_DIGITOS_CPF) return isCnpj(pCpfCnpj);
	else return isCpf(pCpfCnpj);
	}


function verificarCPF(){
	var cpf=env.contato_cpf.value;
	if(!isCpf(cpf)){
		alert('CPF inv�lido!');
		env.contato_cpf.focus();
		}
	else
	env.contato_cpf.value=formatCpfCnpj(cpf, true, false);
	}

function verificarCNPJ(){
	var cnpj=env.contato_cnpj.value;
	if(!isCnpj(cnpj)){
		alert('CNPJ inv�lido!');
		env.contato_cnpj.focus();
		}
	else
	env.contato_cnpj.value=formatCpfCnpj(cnpj, true, true);
	}
</script>
