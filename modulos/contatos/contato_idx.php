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
	
global $m, $a, $u, $xtotal_paginas, $xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, $config, $estilo_interface, $lista_cias, $exibir, $ordem, $ordenar, $tab, $seta, $ver_custo,  $Aplic, $linhas;


mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['contato']), ucfirst($config['contatos']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellspacing=0 cellpadding=0 class="tbl1">';


echo '<tr><th></th>';

if ($lista_cias) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_cia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_cia' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' d'.$config['genero_contato'].' '.$config['contato'].'.').ucfirst($config['organizacao']).''.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_nomeguerra&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_nomeguerra' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Nome d'.$config['genero_contato'].' '.$config['contato'].'.').'Nome'.dicaF().'</a></th>';
if ($exibir['contato_dept']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_dept&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_dept' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).''.dicaF().'</a></th>';
if ($exibir['contato_funcao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_funcao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_funcao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cargo/Fun��o', 'O Cargo/Fun��o d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Fun��o'.dicaF().'</a></th>';
if ($config['militar'] < 10) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_arma&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_arma' ? imagem('icones/'.$seta[$ordem]) : '').dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv d'.$config['genero_contato'].' '.$config['contato'].'.').'Arma/Quadro/Sv'.dicaF().'</a></th>';
if ($exibir['contato_tipo']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_tipo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_tipo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo', 'O tipo d'.$config['genero_contato'].' '.$config['contato'].'.').'Tipo'.dicaF().'</a></th>';
if ($exibir['contato_codigo']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('C�digo', 'C�digo d'.$config['genero_contato'].' '.$config['contato'].'.').'C�digo'.dicaF().'</a></th>';
if ($exibir['contato_identidade']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_identidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_identidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Identidade', 'A identidade d'.$config['genero_contato'].' '.$config['contato'].'.').'Ident.'.dicaF().'</a></th>';
if ($exibir['contato_cpf']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_cpf&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_cpf' ? imagem('icones/'.$seta[$ordem]) : '').dica('CPF', 'O CPF d'.$config['genero_contato'].' '.$config['contato'].'.').'CPF'.dicaF().'</a></th>';
if ($exibir['contato_cnpj']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_cnpj&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_cnpj' ? imagem('icones/'.$seta[$ordem]) : '').dica('CNPJ', 'O CNPJ d'.$config['genero_contato'].' '.$config['contato'].'.').'CNPJ'.dicaF().'</a></th>';
if ($exibir['contato_endereco']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_endereco1&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_endereco1' ? imagem('icones/'.$seta[$ordem]) : '').dica('Endere�o', 'O endere�o d'.$config['genero_contato'].' '.$config['contato'].'.').'Endere�o'.dicaF().'</a></th>';
if ($exibir['contato_tel']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_tel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_tel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Telefone Comercial', 'O telefone comercial d'.$config['genero_contato'].' '.$config['contato'].'.').'Tel. Com.'.dicaF().'</a></th>';
if ($exibir['contato_tel2']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_tel2&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_tel2' ? imagem('icones/'.$seta[$ordem]) : '').dica('Telefone Residencial', 'O telefone residencial d'.$config['genero_contato'].' '.$config['contato'].'.').'Tel. Res.'.dicaF().'</a></th>';
if ($exibir['contato_cel']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_cel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_cel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Celular', 'O celular d'.$config['genero_contato'].' '.$config['contato'].'.').'Cel.'.dicaF().'</a></th>';
if ($exibir['contato_email']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_email&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_email' ? imagem('icones/'.$seta[$ordem]) : '').dica('E-mail', 'O e-mail d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail'.dicaF().'</a></th>';
if ($exibir['contato_email2']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_email2&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_email2' ? imagem('icones/'.$seta[$ordem]) : '').dica('E-mail Alternativo', 'O e-mail alternativo d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail alt.'.dicaF().'</a></th>';
if ($exibir['contato_skype']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_skype&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_skype' ? imagem('icones/'.$seta[$ordem]) : '').dica('Skype', 'A conta Skype d'.$config['genero_contato'].' '.$config['contato'].'.').'Skype'.dicaF().'</a></th>';
if ($ver_custo && $exibir['contato_hora_custo']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_hora_custo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_hora_custo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_contato'].' '.$config['contato'].'.').'Custo hora'.dicaF().'</a></th>';
if ($exibir['contato_nascimento']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_nascimento&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_nascimento' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nascimento', 'O nascimento d'.$config['genero_contato'].' '.$config['contato'].'.').'Nascimento'.dicaF().'</a></th>';
if ($exibir['contato_religiao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_religiao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_religiao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Religi�o', 'A religi�o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Religi�o'.dicaF().'</a></th>';
if ($exibir['contato_sangue']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_sangue&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_sangue' ? imagem('icones/'.$seta[$ordem]) : '').dica('Sangue', 'O tipo sangu�nio d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Sangue'.dicaF().'</a></th>';
if ($exibir['contato_vivo']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_vivo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_vivo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Viv'.$config['genero_usuario'], 'Se '.$config['genero_usuario'].' '.$config['usuario'].' se encontra viv'.$config['genero_usuario'].'.').'Viv'.$config['genero_usuario'].'.'.dicaF().'</a></th>';
if ($exibir['contato_natural_cidade'] ) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_natural_cidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_natural_cidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Natural', 'O cidade de nascimento d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Natural'.dicaF().'</a></th>';
if ($exibir['contato_grau_instrucao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_grau_instrucao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_grau_instrucao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Grau de Instrucao', 'O Grau de instru��o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Instru��o'.dicaF().'</a></th>';
if ($exibir['contato_formacao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_formacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_formacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Forma��o', 'A forma��o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Forma��o'.dicaF().'</a></th>';
if ($exibir['contato_profissao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_profissao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_profissao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Profiss�o', 'A profiss�o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Profiss�o'.dicaF().'</a></th>';
if ($exibir['contato_ocupacao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_ocupacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_ocupacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ocupa��o', 'A ocupa��o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Ocupa��o'.dicaF().'</a></th>';
if ($exibir['contato_especialidade']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_especialidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_especialidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Especialidade', 'A especialidade d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Especialidade'.dicaF().'</a></th>';
if ($exibir['contato_notas']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=contato_notas&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='contato_notas' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observa��o', 'Observa��o sobre '.$config['genero_contato'].' '.$config['contato'].'.').'Obs.'.dicaF().'</a></th>';
if ($exibir['contato_foto']) echo '<th>'.dica('Foto', 'Foto d'.$config['genero_contato'].' '.$config['contato'].'.').'Foto'.dicaF().'</th>';
echo '</tr>';



foreach ($linhas as $linha) {
	echo '<tr>';
	echo '<td width=48>';
	
	if (($linha['contato_dono']==$Aplic->usuario_id) || $Aplic->usuario_super_admin || ($linha['usuario_id']==$Aplic->usuario_id)) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=editar&contato_id='.$linha['contato_id'].'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar este contato').'</a>';
	
	if ($linha['usuario_id']) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&usuario_id='.$linha['usuario_id'].'\');">'.imagem('icones/usuario_mini.png', 'Detalhes d'.$config['genero_usuario'].' '.ucfirst($config['usuario']), 'Este contato tamb�m � '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].', clique neste �cone '.imagem('icones/usuario_mini.png').' para ver seus detalhes.').'</a>';
	echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=vcard_exporta&sem_cabecalho=true&contato_id='.$linha['contato_id'].'\');" >'.imagem('icones/cartao.png', 'Exportar Dados', 'Clique neste �cone '.imagem('icones/cartao.png').' para exportar o vCard deste contato.').'</a>';
	
	if ($linha['contato_pedido_atualizacao'] && (!$linha['contato_ultima_atualizacao'] || $linha['contato_ultima_atualizacao'] == 0) && $linha['contato_chave_atualizacao']) {
		$ultimo_pedido = new CData($linha['contato_pedido_atualizacao']);
		echo imagem('icones/informacao.gif', 'Aguardando Atualiza��o', 'Aguardando pela atualiza��o d'.$config['genero_contato'].' '.$config['contato'].'.<br><br>(Pedido em: '.$ultimo_pedido->format('%d/%m/%Y %H:%M').')');
		}
	elseif ($linha['contato_pedido_atualizacao'] && (!$linha['contato_ultima_atualizacao'] || $linha['contato_ultima_atualizacao'] == 0) && !$linha['contato_chave_atualizacao']) {
		$ultimo_pedido = new CData($linha['contato_pedido_atualizacao']);
		echo imagem('icones/log-error.gif','N�o Atualizou', 'Espera muito longa pela atualiza��o d'.$config['genero_contato'].' '.$config['contato'].'!(Pedir  '.$ultimo_pedido->format('%d/%m/%Y %H:%M').')');
		}
	elseif ($linha['contato_ultima_atualizacao'] && !$linha['contato_chave_atualizacao']) {
		$ultimo_pedido = new CData($linha['contato_ultima_atualizacao']);
		echo imagem('icones/ok.gif','Atualiza��o d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Atualizou em: '.$ultimo_pedido->format('%d/%m/%Y'));
		}

	echo '</td>';
	
	echo '<td>'.link_contato($linha['contato_id']).'</a></td>';
	if ($lista_cias) echo '<td>'.link_cia($linha['contato_cia']).'</a></td>';
	if ($exibir['contato_dept']) echo '<td>'.link_dept($linha['contato_dept']).'</td>';
	if ($exibir['contato_funcao']) echo '<td>'.$linha['contato_funcao'].'</td>';
	if ($config['militar'] < 10) echo '<td>'.$linha['contato_arma'].'</td>';
	if ($exibir['contato_tipo']) echo '<td>'.$linha['contato_tipo'].'</td>';
	if ($exibir['contato_codigo']) echo '<td>'.$linha['contato_codigo'].'</td>';
	if ($exibir['contato_identidade']) echo '<td>'.$linha['contato_identidade'].'</td>';
	if ($exibir['contato_cpf']) echo '<td>'.$linha['contato_cpf'].'</td>';
	if ($exibir['contato_cnpj']) echo '<td>'.$linha['contato_cnpj'].'</td>';
	if ($exibir['contato_endereco']) echo '<td>'.$linha['contato_endereco1'].($linha['contato_endereco1'] && $linha['contato_endereco2'] ? '<br />' :'').$linha['contato_endereco2'].($linha['municipio_nome'] && ($linha['contato_endereco2'] || $linha['contato_endereco1']) ? '<br />' :'').$linha['municipio_nome'].($linha['contato_estado'] ? '-'.$linha['contato_estado'] : '').($linha['contato_cep'] ? '<br />'.$linha['contato_cep'] : '').'</td>';
	if ($exibir['contato_tel']) echo '<td>'.$linha['contato_tel'].'</td>';
	if ($exibir['contato_tel2']) echo '<td>'.$linha['contato_tel2'].'</td>';
	if ($exibir['contato_cel']) echo '<td>'.$linha['contato_cel'].'</td>';
	if ($exibir['contato_email'] ) echo '<td>'.link_email($linha['contato_email'], $linha['contato_id']).'</td>';
	if ($exibir['contato_email2']) echo '<td>'.link_email($linha['contato_email2'], $linha['contato_id']).'</td>';
	if ($exibir['contato_skype']) echo '<td><a href="skype:'.$linha['contato_skype'].'?call">'.$linha['contato_skype'].'</a></td>';
	if ($ver_custo && $exibir['contato_hora_custo']) echo  '<td>'.($linha['contato_hora_custo'] ? number_format($linha['contato_hora_custo'], 2, ',', '.') : '').'</td>';
	if ($exibir['contato_nascimento']) echo '<td>'.retorna_data($linha['contato_nascimento'], false).'</td>';	
	if ($exibir['contato_religiao']) echo '<td>'.(isset($religiao[$linha['contato_religiao']]) ? $religiao[$linha['contato_religiao']] : '').'</td>';
	if ($exibir['contato_sangue']) echo '<td>'.(isset($sangue[$linha['contato_sangue']]) ? $sangue[$linha['contato_sangue']] : '').'</td>';
	if ($exibir['contato_vivo']) echo '<td>'.($linha['contato_vivo'] ? 'Sim' : 'N�o').'</td>';
	if ($exibir['contato_natural_cidade']) echo '<td>'.$linha['cidade_natal'].($linha['contato_natural_estado'] ? ' - ' : '').$linha['contato_natural_estado'].'</td>';
	if ($exibir['contato_grau_instrucao']) echo '<td>'.(isset($escolaridade[$linha['contato_grau_instrucao']]) ? $escolaridade[$linha['contato_grau_instrucao']] : '').'</td>';
	if ($exibir['contato_formacao']) echo '<td>'.$linha['contato_formacao'].'</td>';
	if ($exibir['contato_profissao']) echo '<td>'.$linha['contato_profissao'].'</td>';
	if ($exibir['contato_ocupacao']) echo '<td>'.$linha['contato_ocupacao'].'</td>';
	if ($exibir['contato_especialidade']) echo '<td>'.$linha['contato_especialidade'].'</td>';
	if ($exibir['contato_notas']) echo '<td>'.$linha['contato_notas'].'</td>';
	if ($exibir['contato_foto']) echo '<td valign=top>'.($linha['contato_foto'] ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/contatos/'.$linha['contato_foto'].'" width=100 height=133 />' : '').'</td>';
	echo '</tr>';
	}
if (!count($linhas))	echo '<tr><td colspan=30>'.($config['genero_contato']=='o' ? 'Nenhum' : 'Nenhuma').' '.$config['genero_contato'].' '.$config['contato'].' encontrad'.$config['genero_contato'].'</td></tr>';
	
echo '</table>';

?>