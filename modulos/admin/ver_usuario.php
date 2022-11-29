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


global $adPcT, $cia_id, $dept_ids, $secao, $m, $a, $u;


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = ($Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : null);

$podeEditar=$Aplic->checarModulo('usuarios', 'editar');
$podeAdicionar=$Aplic->checarModulo('usuarios', 'adicionar');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('tab', getParam($_REQUEST, 'tab', 0), $m, $a, $u);
$tab = $Aplic->getEstado('tab', 0, $m, $a, $u);

//feito para resolver bug no menu geral
if (!$usuario_id) $usuario_id = isset($_REQUEST['filtro_id_responsavel']) ? getParam($_REQUEST, 'filtro_id_responsavel', 0) : 0;

if (!(($usuario_id==$Aplic->usuario_id) || $Aplic->usuario_super_admin || $Aplic->usuario_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado'); 


if (!$dialogo) $Aplic->salvarPosicao();

if (isset($_REQUEST['mostrar_form'])) {
	$adicionar_pct=getParam($_REQUEST, 'adProjComTarefas', 0);
	$Aplic->setEstado('adicionarProjComTarefas', $adicionar_pct);
	} 
else 	$Aplic->setEstado('adicionarProjComTarefas', false);
$adPcT = $Aplic->getEstado('adicionarProjComTarefas') ? $Aplic->getEstado('adicionarProjComTarefas') : 0;
$cia_id = $Aplic->getEstado('Usrcia_id') !== null ? $Aplic->getEstado('Usrcia_id') : $Aplic->usuario_cia;
$cia_prefixo = 'cia_';
if (isset($_REQUEST['secao'])) {
	$Aplic->setEstado('UsrProjIdxDept', getParam($_REQUEST, 'secao', null));
	unset($cia_id);
	}
$secao = $Aplic->getEstado('UsrProjIdxDept') !== null ? $Aplic->getEstado('UsrProjIdxDept') : $cia_prefixo.$Aplic->usuario_cia;
if (!(strpos($secao, $cia_prefixo) === false)) {
	$cia_id = substr($secao, strlen($cia_prefixo));
	$Aplic->setEstado('Usrcia_id', $cia_id);
	unset($secao);
	}

$sql = new BDConsulta;
$sql->adTabela('usuarios');
$sql->esqUnir('contatos', 'contatos', 'usuario_contato = contatos.contato_id');
$sql->adCampo('usuarios.*');
$sql->adCampo('contatos.*');
$sql->adOnde('usuarios.usuario_id = '.(int)$usuario_id);
$usuario = $sql->Linha();
$sql->limpar();


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'contato\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();




if (!$usuario) {
	$botoesTitulo = new CBlocoTitulo('ID d'.$config['genero_usuario'].' '.$config['usuario'].' inv�lida', 'usuario.png', $m, "$m.$a");
	$botoesTitulo->adicionaBotao('m=admin', 'lista de '.$config['usuarios'],'','Lista de '.ucfirst($config['usuarios']),'Visualizar a lista de '.$config['usuarios'].' do Sistema.');
	$botoesTitulo->mostrar();
	} 
else {
	$paises = getPais('Paises');
	
	if (!$dialogo){	
		$botoesTitulo = new CBlocoTitulo('Detalhes do '.ucfirst($config['usuario']), 'usuario.png', $m, $m.'.'.$a);
		$botoesTitulo->mostrar();
		echo estiloTopoCaixa();
		echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
		echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
		require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
		$km = new CoolMenu("km");
		$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
		$km->styleFolder="default";
		$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
		if ($Aplic->usuario_admin || $Aplic->usuario_super_admin) $km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['usuarios']),'Clique neste bot�o para visualizar a lista de '.$config['usuarios'].'.').'Lista de '.ucfirst($config['usuarios']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=index&u=\");");
		$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
		if ($podeAdicionar && ($Aplic->usuario_admin || $Aplic->usuario_super_admin)) $km->Add("acao","acao_adicionar",dica('Adicionar '.ucfirst($config['usuario']), 'Adicionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' no Sistema.').'Adicionar '.ucfirst($config['usuario']).dicaF(), "javascript: void(0);'  onclick='url_passar(0, \"m=admin&a=editar_usuario\");");
		if ($podeEditar || $usuario_id == $Aplic->usuario_id ||  $Aplic->usuario_admin || $Aplic->usuario_super_admin) {	
			$km->Add("acao","acao_editar",dica('Editar Cadastro','Editar as informa��es cadastradas d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Editar Cadastro'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=editar_usuario&usuario_id=".$usuario_id."\");");
			$km->Add("acao","acao_preferencia",dica('Editar Prefer�ncias','Editar as prefer�ncias com'.$config['genero_usuario'].' '.$config['usuario'].'.').'Editar Prefer�ncias'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=editarpref&usuario_id=".$usuario_id."\");");
			if (!(isset($config['restrito']) && $config['restrito'])) {
				$km->Add("acao","acao_senhar",dica('Mudar Senha', 'Mudar a senha de entrada no Sistema.').'Mudar Senha'.dicaF(), "javascript: void(0);' onclick='popMudarSenha();return false");
				$km->Add("acao","acao_pergunta",dica('Mudar Pergunta/Resposta', 'Mudar a pergunta/resposta para o caso de esquecer a senha.').'Mudar Pergunta/Resposta'.dicaF(), "javascript: void(0);' onclick='popMudarPergunta();return false");
				
				$km->Add("acao","acao_assinatura",dica('Imagem da Assinatura', 'Clique neste link para incluir ou alterar a imagem da assinatura nos documentos expedidos.').'Imagem da assinatura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=assinatura&usuario_id=".$usuario_id."\");");
				}
			
			if ($Aplic->profissional) $km->Add("acao","acao_cha",dica('Editar Conhecimentos, Habilidades e Atitudes do '.ucfirst($config['usuario']), 'Adicionar conhecimentos, habilidades e atitudes d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Editar Conhecimentos, Habilidades e Atitudes'.dicaF(), "javascript: void(0);'  onclick='url_passar(0, \"m=admin&a=usuario_cha_pro&usuario_id=".$usuario_id."\");");
			}
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Para imprimir o cadastro d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Imprimir Cadastro'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&usuario_id=".$usuario_id."\");");	
		echo $km->Render();
		echo '</td></tr></table>';
		}

	
	echo '<table cellpadding=0 cellspacing=1 '.(!$dialogo ? 'width="100%" class="std"' : 'width=750').' >';
	
	if ($dialogo) echo '<tr><td colspan=2 style="font-weight: bold; font-size: 150%">Detalhes do '.ucfirst($config['usuario']).'<br>&nbsp;</td></tr>';
	
		if ($usuario['usuario_grupo_dept']){
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Conta de Grupo', 'Este '.$config['usuario'].' � uma conta de grupo.').'Conta de grupo:'.dicaF().'</td><td class="realce" width="100%">Sim</td></tr>';
		
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome', 'Nome do grupo.').'Nome:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['contato_nomeguerra'].'</td></tr>';
		if ($usuario['contato_nomecompleto']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome Completo', 'Nome completo do grupo.').'Nome completo:'.dicaF().'</td><td class="realce" >'.$usuario['contato_nomecompleto'].'</td></tr>';
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Login', 'Login do grupo.').'Login:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['usuario_login'].'</td></tr>';
		
		if ($usuario['contato_cia'])	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' do grupo.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce"  style="white-space: nowrap">'.link_cia($usuario['contato_cia']).'</td></tr>';
		if ($usuario['contato_dept']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' do grupo dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td class="realce"  style="white-space: nowrap">'.link_dept($usuario['contato_dept']).'</td></tr>';
		if ($exibir['contato_funcao'] && $usuario['contato_funcao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cargo/Fun��o', 'O Cargo/Fun��o do grupo dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Fun��o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$usuario['contato_funcao'].'</td></tr>';
		
		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_usuario');
		$sql->adOnde('usuario_grupo_pai = '.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_usuario >0');
		$usuarios_selecionados = $sql->carregarColuna();
		$sql->limpar();
		
		
		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_dept');
		$sql->adOnde('usuario_grupo_pai = '.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_dept >0');
		$depts_selecionados = $sql->carregarColuna();
		$sql->limpar();
		
		$saida_quem='';
		if (count($usuarios_selecionados)) {
				$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_quem.= '<tr><td>'.link_usuario($usuarios_selecionados[0], '','','esquerda');
				$qnt_usuarios_selecionados=count($usuarios_selecionados);
				if ($qnt_usuarios_selecionados > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_usuarios_selecionados; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i], '','','esquerda').'<br>';		
						$saida_quem.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'usuarios_selecionados\');">(+'.($qnt_usuarios_selecionados - 1).')</a>'.dicaF(). '<span style="display: none" id="usuarios_selecionados"><br>'.$lista.'</span>';
						}
				$saida_quem.= '</td></tr></table>';
				} 
		if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['usuarios']).' Envolvid'.$config['genero_usuario'].'s', 'Quais '.strtolower($config['usuarios']).' est�o envolvid'.$config['genero_usuario'].'s.').ucfirst($config['usuarios']).' envolvid'.$config['genero_usuario'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';
		
		
		$saida_depts='';
		if (count($depts_selecionados)) {
				$saida_depts.= '<table cellpadding=0 cellspacing=0 width=100%>';
				$saida_depts.= '<tr><td>'.link_dept($depts_selecionados[0]);
				$qnt_lista_depts=count($depts_selecionados);
				if ($qnt_lista_depts > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts_selecionados[$i]).'<br>';		
						$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
						}
				$saida_depts.= '</td></tr></table>';
				} 
		else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';			
		if ($saida_depts) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

		
		}
	else {
		echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['usuario']), 'Nome do '.ucfirst($config['usuario']).'.').'<b>'.ucfirst($config['usuario']).':</b>'.dicaF().'</td><td class="realce" width="100%">'.($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']).'</td></tr>';
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Login', 'Login do '.ucfirst($config['usuario']).' no '.$config['gpweb'].'.').'Login:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['usuario_login'].'</td></tr>';
		if ($usuario['contato_nomecompleto']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome Completo', 'Nome completo d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Nome completo:'.dicaF().'</td><td class="realce" >'.$usuario['contato_nomecompleto'].'</td></tr>';
		if ($usuario['contato_cia'])	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' d'.$config['genero_usuario'].' '.$config['usuario'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce"  style="white-space: nowrap">'.link_cia($usuario['contato_cia']).'</td></tr>';
		if ($usuario['contato_dept']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' d'.$config['genero_usuario'].' '.$config['usuario'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td class="realce"  style="white-space: nowrap">'.link_dept($usuario['contato_dept']).'</td></tr>';
		if ($exibir['contato_funcao'] && $usuario['contato_funcao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cargo/Fun��o', 'O Cargo/Fun��o d'.$config['genero_usuario'].' '.$config['usuario'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Fun��o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$usuario['contato_funcao'].'</td></tr>';
		if ($config['militar'] < 10 && $usuario['contato_arma']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Arma/Quadro/Sv:'.dicaF().'</td><td class="realce" >'.$usuario['contato_arma'].'</td></tr>';
		if ($exibir['contato_tipo'] && $usuario['contato_tipo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo', 'O tipo d'.$config['genero_usuario'].' '.$config['usuario'].'.<br><br>N�o tem relev�ncia para o '.$config['gpweb'].' mas pode facilitar na cataloga��o d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'Tipo:'.dicaF().'</td><td class="realce" >'.$usuario['contato_tipo'].'</td></tr>';
		if ($exibir['contato_identidade'] && $usuario['contato_identidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Identidade', 'A identidade d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Identidade:'.dicaF().'</td><td class="realce" >'.$usuario['contato_identidade'].'</td></tr>';
		if ($exibir['contato_cpf'] && $usuario['contato_cpf']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('CPF', 'O CPF d'.$config['genero_usuario'].' '.$config['usuario'].'.').'CPF:'.dicaF().'</td><td class="realce" >'.$usuario['contato_cpf'].'</td></tr>';
		if ($exibir['contato_matricula'] && $usuario['contato_matricula']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Matr�cula', 'A matr�cula d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Matr�cula:'.dicaF().'</td><td class="realce" >'.$usuario['contato_matricula'].'</td></tr>';
		if ($exibir['contato_tel'] && $usuario['contato_tel']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Telefone Comercial', 'O telefone comercial d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Telefone Comercial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$usuario['contato_tel'].'</td></tr>';
		if ($exibir['contato_tel2'] && $usuario['contato_tel2']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Telefone Residencial', 'O telefone residencial d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Telefone Residencial:'.dicaF().'</td><td class="realce" >'.$usuario['contato_tel2'].'</td></tr>';
		if ($exibir['contato_cel'] && $usuario['contato_cel']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Celular', 'O celular d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Celular:'.dicaF().'</td><td class="realce" >'.$usuario['contato_cel'].'</td></tr>';
		if ($exibir['contato_endereco'] && ($usuario['contato_endereco1'] ||$usuario['contato_endereco2']) ) echo '<tr valign="top"><td align="right" style="white-space: nowrap">'.dica('Endere�o', 'O endere�o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Endere�o:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['contato_endereco1'].($usuario['contato_endereco1'] ? '<br />' :'').$usuario['contato_endereco2'].($usuario['contato_endereco2'] ? '<br />' :'').$usuario['contato_cidade'].' '.$usuario['contato_estado'].' '.$usuario['contato_cep'].($usuario['contato_cidade'] || $usuario['contato_estado'] || $usuario['contato_cep'] ? '<br />' :'').(isset($paises[$usuario['contato_pais']]) ? $paises[$usuario['contato_pais']] : $usuario['contato_pais']).'</td></tr>';
		if ($exibir['contato_nascimento'] && $usuario['contato_nascimento'] && $usuario['contato_nascimento']!='0000-00-00') echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nascimento', 'O nascimento d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Nascimento:'.dicaF().'</td><td class="realce"  style="white-space: nowrap">'.retorna_data($usuario['contato_nascimento'], false).'</td></tr>';		
		if ($exibir['contato_skype'] && $usuario['contato_skype']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Skype', 'A conta Skype d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Skype:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="skype:'.$usuario['contato_skype'].'?call">'.$usuario['contato_skype'].'</a></td></tr>';
		if ($exibir['contato_email'] && $usuario['contato_email'])  echo '<tr><td align="right" style="white-space: nowrap">'.dica('E-mail', 'O e-mail d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'E-mail:'.dicaF().'</td><td class="realce" style="white-space: nowrap">'.link_email($usuario['contato_email'],$usuario['contato_id']).'</td></tr>';
		if ($exibir['contato_email2'] && $usuario['contato_email2']) echo '<tr><td align="right" style="white-space: nowrap">'. dica('E-mail Alternativo', 'O e-mail alternativo d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'E-mail alternativo:'.dicaF().'</td><td class="realce" style="white-space: nowrap">'.link_email($usuario['contato_email2'],$usuario['contato_id']).'</td></tr>';
		if ($usuario['usuario_rodape']) echo '<tr><td align="right">Rodap�:</td><td class="realce" style="text-align: justify;">'.str_replace(chr(10),'<br />', $usuario['usuario_rodape']).'</td></tr>';
		if ($exibir['contato_hora_custo'] && $usuario['contato_hora_custo'] > 0 && $Aplic->checarModulo('usuarios', 'acesso', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Custo hora:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($usuario['contato_hora_custo'], 2, ',', '.').'</td></tr>';
		if ($exibir['contato_religiao'] && $usuario['contato_religiao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Religi�o', 'A religi�o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Religi�o:'.dicaF().'</td><td class="realce" style="text-align: left;">'.getSisValorCampo('Religiao',$usuario['contato_religiao']).'</td></tr>';
		if ($exibir['contato_sangue'] && $usuario['contato_sangue']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Sangue', 'O tipo sangu�nio d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Sangue:'.dicaF().'</td><td class="realce" style="text-align: left;">'.getSisValorCampo('Sangue', $usuario['contato_sangue']).'</td></tr>';
		if ($exibir['contato_vivo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viv'.$config['genero_usuario'], 'Se '.$config['genero_usuario'].' '.$config['usuario'].' se encontra viv'.$config['genero_usuario'].'.').'Viv'.$config['genero_usuario'].':'.dicaF().'</td><td class="realce" style="text-align: left;">'.($usuario['contato_vivo'] ? 'Sim' : 'N�o').'</td></tr>';
		if ($exibir['contato_natural_cidade'] && $usuario['contato_natural_cidade']) {
			$sql->adTabela('contatos');
			$sql->esqUnir('estado', 'estado', 'contato_estado=estado_sigla');
			$sql->esqUnir('municipios', 'municipios', 'contato_natural_cidade = municipio_id');
			$sql->adCampo('estado_nome, municipio_nome');
			$sql->adOnde('contato_id='.(int)$usuario['contato_id']);
			$endereco=$sql->Linha();
			$sql->limpar();
			echo '<tr><td align="right" style="white-space: nowrap">'.dica('Natural', 'O cidade de nascimento d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Natural:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$endereco['municipio_nome'].' - '.$endereco['estado_nome'].'</td></tr>';
			}
		if ($exibir['contato_grau_instrucao'] && $usuario['contato_grau_instrucao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Grau de Instru��o', 'O Grau de instru��o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Grau de instru��o:'.dicaF().'</td><td class="realce" style="text-align: left;">'.getSisValorCampo('Escolaridade',$usuario['contato_grau_instrucao']).'</td></tr>';
		if ($exibir['contato_formacao'] && $usuario['contato_formacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Forma��o', 'A forma��o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Forma��o:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$usuario['contato_formacao'].'</td></tr>';
		if ($exibir['contato_profissao'] && $usuario['contato_profissao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Profiss�o', 'A profiss�o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Profiss�o:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$usuario['contato_profissao'].'</td></tr>';
		if ($exibir['contato_ocupacao'] && $usuario['contato_ocupacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ocupa��o', 'A ocupa��o d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Ocupa��o:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$usuario['contato_ocupacao'].'</td></tr>';
		if ($exibir['contato_especialidade'] && $usuario['contato_especialidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Especialidade', 'A especialidade d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Especialidade:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$usuario['contato_especialidade'].'</td></tr>';
			
		
		

		if ($usuario['usuario_assinatura_nome']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Assinatura', 'A assinatura d'.$config['genero_usuario'].' '.$config['usuario'].' para os documentos internos.').'Assinatura:'.dicaF().'</td><td class="realce"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos//'.$usuario['usuario_assinatura_local'].$usuario['usuario_assinatura_nome'].'" /></td></tr>';
		
		if ($usuario['usuario_observador']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Observador'.($config['genero_usuario']=='a' ? 'a' : ''), 'Se '.$config['genero_usuario'].' '.$config['usuario'].' tem perfil limitado a apenas observador dos pincipais objetos do sistema..').'Observador'.($config['genero_usuario']=='a' ? 'a' : '').':'.dicaF().'</td><td class="realce" style="text-align: left;">'.($usuario['usuario_observador'] ? 'Sim' : 'N�o').'</td></tr>';
		
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ativ'.$config['genero_usuario'], 'Se '.$config['genero_usuario'].' '.$config['usuario'].' est� ativ'.$config['genero_usuario'].'.').'Ativ'.$config['genero_usuario'].':'.dicaF().'</td><td class="realce" style="text-align: left;">'.($usuario['usuario_ativo'] ? 'Sim' : 'N�o').'</td></tr>';
		
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('usuario', $usuario_id, 'ver');
		if ($campos_customizados->count()) {
				echo '<tr><td colspan="2">';
				$campos_customizados->imprimirHTML();
				echo '</td></tr>';
				}	
		
		if ($Aplic->profissional) include_once BASE_DIR.'/modulos/admin/ver_usuario_pro.php';
	
		}
	echo '</table>';
	if (!$dialogo) echo estiloFundoCaixa();	

	if (!$dialogo){	
		$caixaTab = new CTabBox('m=admin&a=ver_usuario&usuario_id='.$usuario_id, '', $tab);
		if ($Aplic->checarModulo('admin', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_registro_usuarios', 'Acesso',null,null,'Registro de Acesso','Visualizar as datas e horas de entrada e sa�da do Sistema para este '.$config['usuario'].'.');
		$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_nivel_acesso_usuario', 'Perfil Acesso',null,null,'Perfil de Acesso','Visualizar o perfil de acesso deste '.$config['usuario'].'.<br><br>Cada perfil de acesso permite acessar diferentes funcionalidades do Sistema.');
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_grupos_msg', 'Grupos Msg.',null,null,'Grupos de '.ucfirst($config['mensagem']),'Visualizar os grupos de destinat�rios de '.$config['mensagens'].' que este '.$config['usuario'].' � participante, ou que tem autoriza��o para visualizar.');	
		
		
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' em que este '.$config['usuario'].' � o respons�vel.');
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_gantt', 'Gantt',null,null,'Gantt','Visualizar os per�odos em que '.$config['genero_usuario'].' '.$config['usuario'].' esteja designado para '.$config['tarefas'].', em forma de gr�fico Gantt.');
			if ($config['checar_comprometimento']) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/sobrecarga', 'Comprometimento',null,null,'Comprometimento','Visualizar o grau de comprometimento d'.$config['genero_usuario'].' '.$config['usuario'].' n'.$config['genero_tarefa'].'s '.$config['tarefas'].'.');
			}
		if ($Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/tarefas', ucfirst($config['tarefas']),null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' que este '.$config['usuario'].' � respons�vel ou foi designado.');
		
		
		
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionado com este '.$config['usuario'].'.');
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.compromissos', 'Compromissos',null,null,'Compromissos','Visualizar os compromissos em que esteja envolvido.');	
	
			
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso')) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores que este '.$config['usuario'].' seja respons�vel ou foi designado.');	
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_praticas', ucfirst($config['praticas']),null,null,ucfirst($config['praticas']),'Visualizar '.$config['genero_pratica'].'s '.$config['praticas'].' que este '.$config['usuario'].' seja respons�vel ou foi designado.');	
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_planos_acao', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
			}
	
		
		
			
		$caixaTab->mostrar('','','','',true);
		}
	if (!$dialogo) echo estiloFundoCaixa();
	else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';	
	
	}

?>
<script type="text/javascript">

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
	
function popMudarSenha() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Mudar Senha', 400,300,'m=publico&a=checarsenha&dialogo=1&usuario_id=<?php echo $usuario_id ?>', null, window);
	else window.open( './index.php?m=publico&a=checarsenha&dialogo=1&usuario_id=<?php echo $usuario_id ?>', 'Mudar Senha', 'top=250,left=250,width=350, height=220, scrollbars=no');
	}
	
function popMudarPergunta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Mudar Pergunta/Resposta', 600,300,'m=publico&a=mudarpergunta&dialogo=1&usuario_id=<?php echo $usuario["usuario_id"]; ?>', null, window);
	else window.open( './index.php?m=publico&a=mudarpergunta&dialogo=1&usuario_id=<?php echo $usuario["usuario_id"]; ?>', 'Mudar Pergunta/Resposta', 'top=250,left=250,width=350, height=220, scrollbars=no');
	}	
</script>
