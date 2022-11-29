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


$ajax=(file_exists(BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'_ajax.php')? 1 : 0);
if($ajax) include BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'_ajax.php';


echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
echo '<html>';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style">';
echo '<meta name="google" value="notranslate">';
//echo '<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">';
echo '<meta http-equiv="Content-Type" content="text/html; charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'">';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
$imprimir=getParam($_REQUEST, 'imprimir', '');
echo '<link rel="stylesheet" type="text/css" href="./estilo/rondon/'.($imprimir ? 'imprimir_' : 'estilo_').$config['estilo_css'].'.css" media="all">';
echo '<style type="text/css" media="all">@import "./estilo/rondon/'.($imprimir  ? 'imprimir_': 'estilo_').$config['estilo_css'].'.css";</style>';
if(!$Aplic->profissional){
    echo '<link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico">';
    }

if($ajax && isset($xajax)) {
	//por o script ignorar variaveis M e A via post, necess�rio for�ar o envio
	$enderecoURI=BASE_URL.'/index.php?m='.$m.'&a='.$a.'&u='.$u.($u? '&u='.$u : '');
	$xajax->printJavascript(BASE_URL.'/lib/xajax');
	}

$Aplic->carregarCabecalhoJS();
//papel de parede
echo '</head><body onload="this.focus();" '.(isset($config['papel_parede']) && $config['papel_parede'] ? 'background="'.$config['papel_parede'].'"' : '').'>';

echo '<script>$jq = jQuery.noConflict();</script>';

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

if ($Aplic->chave_criada){
	@unlink($base_dir.'/arquivos/temp/'.$Aplic->chave_criada.'.key');
	@unlink($base_dir.'/arquivos/temp/'.$Aplic->chave_criada.'.crt');
	}

$msg_saud = date("H");

if($msg_saud >= 0 && $msg_saud < 6)$msg_ini_saud = 'Boa madrugada ';
else if($msg_saud >= 6 && $msg_saud < 12) $msg_ini_saud = 'Bom dia ';
else if($msg_saud >= 12 && $msg_saud < 18) $msg_ini_saud = 'Boa tarde ';
else $msg_ini_saud = 'Boa noite ';

$final_saudacao=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id ? imagem('icones/membros_p.png', 'Conta de Grupo', 'Ao menos uma conta de grupo est� ativada.'): '');
$q = new BDConsulta;

if(!$Aplic->profissional){
  echo '<table width="100%" cellpadding=0 cellspacing=0 border=0>';

	$podeAcessar_email=$Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso');
	$podeAcessar_contatos=$Aplic->modulo_ativo('contatos') && $Aplic->checarModulo('contatos', 'acesso');
	$podeAcessar_parafazer=$Aplic->modulo_ativo('parafazer') && $Aplic->checarModulo('parafazer', 'acesso');
	$podeAcessar_foruns=$Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso');
	$podeAcessar_arquivos=$Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso');
	$podeAcessar_pesquisa=$Aplic->modulo_ativo('pesquisa') && $Aplic->checarModulo('pesquisa', 'acesso');
	$podeAcessar_recursos=$Aplic->modulo_ativo('recursos') && $Aplic->checarModulo('recursos', 'acesso');
	$podeAcessar_links=$Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso');
	$podeAcessar_cias=$Aplic->modulo_ativo('cias') && $Aplic->checarModulo('cias', 'acesso');
	$podeAcessar_depts=$Aplic->modulo_ativo('depts') && $Aplic->checarModulo('depts', 'acesso');
	$podeAcessar_projetos=$Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso');
	$podeAcessar_tarefas=$Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso');
	$podeAcessar_praticas=$Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso');
	$podeAcessar_calendario=$Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso');
	$podeAcessar_relatorios=$Aplic->modulo_ativo('relatorios') && $Aplic->checarModulo('relatorios', 'acesso');
	if (!$Aplic->celular && !$dialogo){
		echo '<tr><td><table width="100%" cellpadding=0 cellspacing=0 border=0><tr><th style="background: url(estilo/rondon/imagens/titulo_fundo.png);" align="left" ><a href="'.$config['endereco_site'].'" target="_blank">'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.').'<img src="estilo/rondon/imagens/organizacao/10/mensagens.png" border=0 class="letreiro" align="left" />'.dicaF().'</th>';
		echo '<th style="background: url(estilo/rondon/imagens/titulo_fundo.png);" align="left" width="100%">&nbsp;</th>';
		if ($config['militar']==11) echo '<th style="background: url(estilo/rondon/imagens/titulo_fundo.png);" align="left" ><a href="http://www.mbc.org.br/mbc/pgqp" target="_blank">'.dica('PGQP - Programa Ga�cho da Qualidade e Produtividade', 'Clique para entrar no site oficial do PGQP - Programa Ga�cho da Qualidade e Produtividade.').'<img src="estilo/rondon/imagens/organizacao/11/pgqp.png" border=0 class="letreiro" align="left" />'.dicaF().'</th>';
		else echo '<th style="font-size: xx-small; background: url(estilo/rondon/imagens/titulo_fundo.png); white-space: nowrap;" align="right" >v. '.$Aplic->getVersao().'</th>';
		echo '</table></td></tr>';
		}

	if (!$dialogo){
		$nav = $Aplic->getMenuModulos();
		echo '<tr class="nav"><td width="100%" style="white-space: nowrap;background-color: #e6e6e6">';
		require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
		$menu = new CoolMenu('kmnuPrincipal');
		$menu->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
		$menu->styleFolder="default";
		$menu->Add("root","menu","Menu", "javascript: void(0);");

		

		if ($podeAcessar_contatos) $menu->Add("menu","contatos",dica('Contatos','Selecione esta op��o para acessar os contatos cadastrados.').'Contatos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=index&filtro_id_responsavel=".$Aplic->usuario_id."\");", "estilo/rondon/imagens/icones/contatos_p.png");
		$q->adTabela('parafazer_usuarios');
		$q->adCampo('count(id)');
		$q->adOnde('usuario_id = '.$Aplic->usuario_id.' AND aceito=0');
		$parafazer_outros = $q->Resultado();
		$q->limpar();
		if ($podeAcessar_parafazer) {
			$menu->Add("menu","prafazer", dica('Lembretes','Selecione esta op��o para acessar sua lista particular de atividades a serem realizadas.').'Lembretes'.($parafazer_outros ? ' ('.$parafazer_outros.')': '').dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer","lembretes", dica('Lista de Lembretes','Selecione esta op��o para acessar sua lista particular de atividades a serem realizadas.').'Lista de Lembretes'.($parafazer_outros ? ' ('.$parafazer_outros.')': '').dicaF(), "javascript: void(0);' onclick='lista_todo(".$parafazer_outros.");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer","controle_prafazer", dica('Controle dos Convites de Lembretes','Selecione esta op��o para acessar o painel de aceita��o de lembretes recebidos.').'Controle dos Lembretes'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=parafazer&a=controle\");", "estilo/rondon/imagens/icones/todo_list_p.png");
			//$menu->Add("prafazer",'vazio5', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		if ($podeAcessar_foruns) $menu->Add("menu","foruns", dica('F�runs','Exibir a lista de f�runs').'F�runs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=index\");", "estilo/rondon/imagens/icones/forum_p.gif");
		
		
		if ($podeAcessar_arquivos) {
			$menu->Add("menu","arquivos", dica('Arquivos','Exibir os arquivos inclu�dos no sistema').'Arquivos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/arquivo_p.png");
			$menu->Add("arquivos","lista_arquivos", dica('Lista de Arquivos','Exibir a lista de arquivos inclu�dos no sistema').'Lista de Arquivos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=index\");", "estilo/rondon/imagens/icones/arquivo_p.png");
			$menu->Add("arquivos","lista_pastas", dica('Lista de Pastas','Exibir a lista de pastas inclu�das no sistema').'Lista de Pastas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=pasta_lista\");", "estilo/rondon/imagens/icones/pasta_p.png");
			
			}
		
		if ($podeAcessar_pesquisa) $menu->Add("menu","pesquisa", dica('Pesquisa Inteligente','Selecione para pesquisar por palavra chave dentro dos m�dulos do sistema').'Pesquisa Inteligente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pesquisa&a=index\");", "estilo/rondon/imagens/icones/busca_p.png");
		if ($podeAcessar_recursos) {
			$menu->Add("menu","instrumentos", dica('Instrumentos','Exibir os instrumentos (contrato, conv�nio, etc.) cadastrados no sistema').'Instrumentos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_lista\");", "estilo/rondon/imagens/icones/instrumento_p.png");
			$menu->Add("menu","recursos1", '<span style="width:120px;">'.dica('Recursos','Exibir os recursos cadastrados no sistema').'Recursos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=index\");", "estilo/rondon/imagens/icones/recursos_p.gif");
			}

		if ($podeAcessar_links) $menu->Add("menu","links1", dica('Links','Exibir os links cadastrados no sistema').'Links&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=index\");", "estilo/rondon/imagens/icones/links_p.gif");
		if ($podeAcessar_cias) $menu->Add("menu",ucfirst($config['organizacoes']), dica(ucfirst($config['organizacoes']),'Exibir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' cadastrados no sistema').ucfirst($config['organizacoes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=cias&a=index\");", "estilo/rondon/imagens/icones/organizacao_p.gif");
		if ($podeAcessar_depts) $menu->Add("menu",ucfirst($config['departamentos']), dica(ucfirst($config['departamentos']),'Exibir '.$config['genero_dept'].'s '.$config['departamentos'].' cadastrad'.$config['genero_dept'].'s no sistema').ucfirst($config['departamentos']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=depts&a=index\");", "estilo/rondon/imagens/icones/secoes_p.gif");

		if (config('registrar_mudancas') && $Aplic->checarModulo('historico', 'acesso') && $Aplic->modulo_ativo('historico')) $menu->Add("menu","historico",dica('Hist�rico de altera��es','Selecione esta op��o para acessar o hist�rico de altera��es efetuados nos diversos m�dulos do '.$config['gpweb'].'.').'Hist�rico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=historico&a=index\");", "estilo/rondon/imagens/icones/historico_p.png");


		//Modulos extras
		exibir_modulos_terceiros();

	
		if ($podeAcessar_projetos) {
			$editar_projeto=$Aplic->checarModulo('projetos', 'editar');
			$menu->Add("root","projetos1", dica(ucfirst($config['projetos']),'Exibir o menu de '.$config['projetos']).ucfirst($config['projetos']).dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/projeto_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos1","demandas", dica('Demandas','Exibir a lista de demandas que poder�o se transformar em '.$config['projetos'].'.').'Demandas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_lista\");", "estilo/rondon/imagens/icones/demanda_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos1","viabilidades", dica('Estudos de Viabilidade','Exibir a lista de estudos de viabilidade de poss�veis '.$config['projetos'].'.').'Estudos de viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_lista\");", "estilo/rondon/imagens/icones/viabilidade_p.gif");
			
			if ($config['anexo_mpog']) $menu->Add("projetos1","termos_abertura", dica('Termos de Abertura','Exibir a lista de termos de abertura de '.$config['projetos'].'.').'Termos de abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_lista\");", "estilo/rondon/imagens/icones/anexo_projeto_p.png");
			
			
			if ($config['anexo_mpog']) $menu->Add("projetos1","banco_projetos", dica('Banco de Poss�veis '.ucfirst($config['projetos']),'Exibir a lista de poss�veis '.$config['projetos'].' de serem criados, atrav�s do termo de abertura.').'Banco de poss�veis '.$config['projetos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=banco_projeto\");", "estilo/rondon/imagens/icones/banco_projeto_p.gif");
			$menu->Add("projetos1","lista_projetos", dica('Lista de '.ucfirst($config['projetos']),'Exibir a lista de '.$config['projetos']).'Lista de '.$config['projetos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=index\");", "estilo/rondon/imagens/icones/projeto_p.gif");
			if ($podeAcessar_tarefas) $menu->Add("projetos1","tarefas", dica(ucfirst($config['tarefas']),'Exibir a lista de '.$config['tarefas']).ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=index\");", "estilo/rondon/imagens/icones/tarefa_p.gif");
			if ($editar_projeto) $menu->Add("projetos1","wbs", dica('Estrutura Anal�tica de Projeto(EAP)','Exibir a estrutura anal�tica de projeto').'Estrutura Anal�tica de Projeto(EAP)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical&jquery=1\");", "estilo/rondon/imagens/icones/wbs_p.png");
			if ($editar_projeto) $menu->Add("projetos1","agil", dica('Gantt Interativo','Exibir interface de cria��o e edi��o de '.$config['projetos'],' utilizando gr�fico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo\");", "estilo/rondon/imagens/icones/projeto_facil_p.gif");
			$menu->Add("projetos1","visao_marco", dica('Vis�o Macro d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']),'Exibir relat�rio com a vis�o macro d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Vis�o Macro d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=arvore_ciclica\");", "estilo/rondon/imagens/icones/arvore_ciclica.gif");
			$menu->Add("projetos1","envio_recebimento", dica('Envio e Recebimento de '. ucfirst($config['projetos']),'Exibir '.$config['genero_projeto'].'s '.$config['projetos'].' recebidos e enviados.').'Envio e Recebimento de '. ucfirst($config['projetos']).'&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=receber_projeto\");", "estilo/rondon/imagens/icones/receber_projeto_p.png");
			if ($podeAcessar_relatorios) $menu->Add("projetos1","relatorios_projeto", dica('Relat�rios de '.$config['projetos'],'Exibir lista de relat�rios de '.$config['projetos']).'Relat�rios de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index\");", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("projetos1","licao1", dica('Li��es Aprendidas','Exibir lista de '.$config['licoes'].' n'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Li��es aprendidas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_lista\");", "estilo/rondon/imagens/icones/licoes_p.gif");
			//vazio s� para melhorar a diagrama��o
			//$menu->Add("projetos1",'vazio10', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		elseif ($podeAcessar_tarefas) $menu->Add("root","tarefas", dica(ucfirst($config['tarefas']),'Exibir a lista de '.$config['tarefas']).ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=index\");", "estilo/rondon/imagens/icones/tarefa_p.gif");

		$acesso_praticas=0;

		if ($podeAcessar_praticas) {
			$menu->Add("root","gestao", dica('Gest�o','Exibir o planejamento estrat�gico d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Gest�o'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/ferramentas_p.png");
			$menu->Add("gestao","plano_gestao1", dica('Planejamento Estrat�gico','Exibir o planejamento estrat�gico d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Planejamento Estrat�gico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gestao_lista&u=gestao\");", "estilo/rondon/imagens/icones/planogestao_p.png");
			$menu->Add("gestao","lista_perspectivas", dica(ucfirst($config['perspectivas']),'Exibir a lista de '.$config['perspectivas'].'.').ucfirst($config['perspectivas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_lista\");", "estilo/rondon/imagens/icones/perspectiva_p.png");
			$menu->Add("gestao","lista_temas", dica(ucfirst($config['temas']),'Exibir a lista de '.$config['temas'].'.').ucfirst($config['temas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_lista\");", "estilo/rondon/imagens/icones/tema_p.png");
			$menu->Add("gestao","lista_obj_estrategicos", dica(ucfirst($config['objetivos']),'Exibir a lista de '.$config['objetivos'].'.').ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_lista\");", "estilo/rondon/imagens/icones/obj_estrategicos_p.gif");
			$menu->Add("gestao","lista_fatores", dica(ucfirst($config['fatores']),'Exibir a lista de '.$config['fatores'].'.').ucfirst($config['fatores']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_lista\");", "estilo/rondon/imagens/icones/fator_p.gif");
			$menu->Add("gestao","lista_estrategias", dica('Iniciativas','Exibir a lista de iniciativas.').'Iniciativas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_lista\");", "estilo/rondon/imagens/icones/estrategia_p.gif");
			$menu->Add("gestao","praticas1", dica(ucfirst($config['praticas']),'Menu de '.$config['praticas']).ucfirst($config['praticas']).dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("praticas1","lista_praticas", dica('Lista de '.ucfirst($config['praticas']),'Exibir a lista de '.$config['praticas']).'Lista de '.$config['praticas'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista\");", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("praticas1","praticas_melhores", dica('Melhores '.ucfirst($config['praticas']),'Exibir a lista de melhores .'.$config['praticas']).'Melhores '.$config['praticas'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista_melhores\");", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("gestao","indicadores1", dica('Indicadores','Menu de indicadores').'Indicadores'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/indicador_p.gif");
			$menu->Add("indicadores1","lista_indicadores", dica('Indicadores','Exibir a lista de indicadores').'Lista de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_lista\");", "estilo/rondon/imagens/icones/indicador_p.gif");
			$menu->Add("indicadores1","lista_lacunas", dica('Lacuna de Indicadores','Exibir a lista de ausencias de indicadores que seriam necess�rios para que todos os indicadores relevantes, referentes a uma pauta de pontua��o, fossem apresentados no Balanced Score Card.').'Lacuna de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_lista\");", "estilo/rondon/imagens/icones/lacuna_p.png");
			$menu->Add("indicadores1","avaliacao1", dica('Avalia��es','Menu de avalia��es dos indicadores').'Avalia��es'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao1","lista_avalia��o", dica('Lista de Avalia��es','Exibir a lista de avalia��es dos indicadores').'Lista de avalia��es'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_lista\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao1","avaliar1", dica('Avaliar','Executar uma avalia��o previamente cadastrada.').'Avaliar'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_executar\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao1","avaliar2", dica('Avaliar em Dispositivos M�veis','Executar uma avalia��o previamente cadastrada, para dispositivos com tela pequena.').'Avaliar em dispositivos m�veis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_executar&dialogo=1&movel=1\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("gestao","lista_metas", dica('Metas','Exibir a lista de metas.').'Metas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_lista\");", "estilo/rondon/imagens/icones/meta_p.gif");
			$menu->Add("gestao","checklist1", dica('Checklist','Exibir a lista de checklist').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_lista\");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("gestao","plano_acao1", dica('Planos de A��o','Exibir a lista de planos de a��o').'Planos de a��o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_lista\");", "estilo/rondon/imagens/icones/plano_acao_p.gif");
			$menu->Add("gestao","relatorios_gestao", dica('Relat�rios de BSC','Exibir a lista de relat�rios de BSC').'Relat�rios de BSC'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("relatorios_gestao","lista_relatorios_gestao", dica('Lista de Relat�rios','Exibir a lista de relat�rios de gest�o').'Lista de relat�rios de gest�o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=relatorios\");", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("relatorios_gestao","arvore_gestao", dica('�rvore da Gest�o Estrat�gica','Exibir a �rvore da gest�o estrat�gica.').'�rvore da gest�o estrat�gica'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=arvore_gestao\");", "estilo/rondon/imagens/icones/arvore_p.gif");
			$menu->Add("relatorios_gestao","mapa_estrategicos", dica('Mapa Estrat�gicos','Exibir o mapa estrat�gico.').'Mapa estrat�gico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=mapa_estrategico\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","projetos_por_obj_estr", dica('Projetos por '.ucfirst($config['objetivos']),'Exibir o n�mero de projetos por '.$config['objetivos'].'.').ucfirst($config['projetos']).' por '.$config['objetivos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=projetos_por_obj_estrategicos\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","dept_por_obj_estr", dica(ucfirst($config['departamento']).' por '.$config['objetivos'],'Exibir '.$config['departamento'].' por '.$config['objetivos'].'.').ucfirst($config['dept']).' por '.$config['objetivos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=dept_por_obj_estrategicos\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","obj_vs_iniciativas", dica(ucfirst($config['objetivos']).' vs Iniciativas','Exibir a lista de '.$config['objetivos'].' relaciodados com as iniciativas.').ucfirst($config['objetivos']).' vs iniciativas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=objetivos_vs_iniciativas\");", "estilo/rondon/imagens/icones/obj_vs_iniciativas_p.gif");
			$menu->Add("gestao","ferramentas_gestao", dica('Ferramentas de Gest�o','Exibir as ferramentas de gest�o').'Ferramentas de Gest�o'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/ferramentas_gestao_p.png");
			$menu->Add("ferramentas_gestao","brainstorm1", dica('Brainstorm','Exibir Brainstorm').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_lista\");", "estilo/rondon/imagens/icones/brainstorm_p.gif");
			$menu->Add("ferramentas_gestao","causa_efeito1", dica('Diagrama de Causa-Efeito','Exibir diagramas de causa-efeito').'Diagrama de Causa-Efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_lista\");", "estilo/rondon/imagens/icones/causaefeito_p.png");
			$menu->Add("ferramentas_gestao","gut1", dica('Matriz GUT','Exibir a matriz de prioriza��o GUT (gravidade, urg�ncia e tend�ncia)').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_lista\");", "estilo/rondon/imagens/icones/gut_p.gif");
			$menu->Add("gestao","modelos1", dica('Pautas de <i>Balaced Score Card</i>','Visualizar os <i>Balaced Score Card</i> cadastrados nas diversas r�guas de pontua��o.').'Pautas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=modelos\");", "estilo/rondon/imagens/icones/modelos_p.png");
			//$menu->Add("ferramentas_gestao",'vazio15', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');

			$acesso_praticas=1;
			}

		if ($podeAcessar_calendario) {
			$menu->Add("root",'calendario1', dica('Calend�rio','Exibir o calend�rio com as datas de in�cio e t�rmino dos indicadores, eventos, etc.').'Calend�rio'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario1",'ano', dica('Ano','Exibir o calend�rio anual com as datas de in�cio e t�rmino dos indicadores, eventos, etc.').'Ano'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_ano\");", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario1",'mes', dica('M�s','Exibir o calend�rio mensal com as datas de in�cio e t�rmino dos indicadores, eventos, etc.').'M�s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=index\");", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario1",'semana', dica('Semana','Exibir o calend�rio semanal com as datas de in�cio e t�rmino dos indicadores, eventos, etc.').'Semana'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_semana\");", "estilo/rondon/imagens/icones/calendario_p.png");
			
			$menu->Add("calendario1",'compromissos', dica('Compromissos','Exibir sua agenda particular de compromissos.').'Compromissos'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/compromisso_p.png");
			
			$menu->Add("compromissos",'cano', dica('Ano','Exibir sua agenda particular de compromissos no formato anual.').'Ano'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_ano\");", "estilo/rondon/imagens/icones/compromisso_p.png");
			$menu->Add("compromissos",'cmes', dica('M�s','Exibir sua agenda particular de compromissos no formato mensal.').'M�s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_mes\");", "estilo/rondon/imagens/icones/compromisso_p.png");
			$menu->Add("compromissos",'csemana', dica('Semana','Exibir sua agenda particular de compromissos no formato semanal.').'Semana'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_semana\");", "estilo/rondon/imagens/icones/compromisso_p.png");
			
			
			

			//vazio s� para melhorar a diagrama��o
			//$menu->Add("calendario1",'vazio5', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}

		if ($podeAcessar_email) {
			$menu->Add("root",'mensagem1', dica('Comunica��o', 'Leia e escreva '.$config['mensagens'].' e documentos pelo sistema interno do '.$config['gpweb'].'.').'Comunica��o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=1\");", "estilo/rondon/imagens/icones/email.gif");
			$menu->Add("mensagem1",'entrada', dica('Caixa de Entrada', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' na caixa de entrada.').ucfirst($config['mensagens']).' na Caixa de Entrada'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=1&pagina=1\");", "estilo/rondon/imagens/icones/email_receber.gif");
			$menu->Add("mensagem1",'pendente', dica('Pendentes', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' colocad'.$config['genero_mensagem'].'s como pendentes.').ucfirst($config['mensagens']).' Pendentes'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=3&pagina=1\");", "estilo/rondon/imagens/icones/email_pendente.gif");
			$menu->Add("mensagem1",'arquivadas', dica('Arquivad'.$config['genero_mensagem'].'s', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' colocad'.$config['genero_mensagem'].'s na caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s.').ucfirst($config['mensagens']).' Arquivad'.$config['genero_mensagem'].'s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=4&pagina=1\");", "estilo/rondon/imagens/icones/email_arquivada.gif");
			$menu->Add("mensagem1",'enviadas', dica('Enviad'.$config['genero_mensagem'].'s', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' enviad'.$config['genero_mensagem'].'s.').ucfirst($config['mensagens']).' Enviad'.$config['genero_mensagem'].'s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=5&pagina=1\");", "estilo/rondon/imagens/icones/email_enviado.gif");
			$menu->Add("mensagem1",'nova_msg', dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Enviar um'.($config['genero_mensagem']=='a' ? 'a' : '').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Nov'.$config['genero_mensagem'].' '.ucfirst($config['msg']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=seleciona_usuarios&destino_cabecalho=envia_msg\");", "estilo/rondon/imagens/icones/email_novo.gif");
			if ($config['doc_interno']) $menu->Add("mensagem1",'doc_int', dica('Documentos Internos','Selecione esta op��o para acessar os documentos criados dentro do '.$config['gpweb'].'.').'Documentos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_pesquisar\");", "estilo/rondon/imagens/icones/msg10000.gif");
			//vazio s� para melhorar a diagrama��o
			}

		if ($podeAcessar_email) {
			$menu->Add("mensagem1","msg_tarefa",dica(ucfirst($config['mensagens']).' do Tipo Atividade','Selecione esta op��o para acessar o painel de controle d'.$config['genero_mensagem'].'s '.$config['mensagens'].' que s�o do tipo atividade.').ucfirst($config['mensagens']).' do tipo atividade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg_tarefa\");", "estilo/rondon/imagens/icones/task_p.png");
			$menu->Add("mensagem1","despacho",dica('Controle de Despachos','Selecione esta op��o para acessar os despacho recebidos e enviados ainda sem uma resposta.').'Despachos'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/despacho_p.gif");
			$menu->Add("despacho","despacho_msg",dica('Controle de Despachos de '.ucfirst($config['mensagens']),'Selecione esta op��o para acessar os despacho recebidos e enviados de '.$config['mensagens'].' ainda sem uma resposta.').'Despachos de '.$config['mensagens'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_despacho\");", "estilo/rondon/imagens/icones/despacho_p.gif");
			if ($config['doc_interno']) $menu->Add("despacho","despacho_modelo",dica('Controle de Despachos de Documentos','Selecione esta op��o para acessar os despacho recebidos e enviados de documentos ainda sem uma resposta.').'Despachos de documentos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_despacho_modelo\");", "estilo/rondon/imagens/icones/despacho_p.gif");
			//$menu->Add("despacho",'vazio0', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		
		$menu->Add("root",'atalhos1', dica('Atalhos','Atalhos de op��es comuns do sistema.').'Atalhos'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/atalho_p.gif");

		if ($Aplic->usuario_tem_lista_grupo) $menu->Add("atalhos1","grupo11",'<span style="width:120px;">'.dica('Conta de Grupo', 'Selecionar quais contas de grupo deseja deixar ativas, para verifica��o das informa��es. Pode-se selecionar quantas contas cadastradas deseja deixar ativas para os diversos filtros do sistema.').'Conta&nbsp;de&nbsp;Grupo&nbsp;do&nbsp;Sistema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='popContas();", "estilo/rondon/imagens/icones/usuarios.gif");
		$menu->Add("atalhos1","afazer1",'<span style="width:120px;">'.dica('A Fazer', 'Mostra objetos que lhe foram designadas e que ainda n�o estejam terminados.').'A&nbsp;Fazer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=parafazer\");", "estilo/rondon/imagens/icones/afazer_p.png");
		if ($podeAcessar_calendario) {
			$agora = new CData();
			$menu->Add("atalhos1","hoje1",'<span style="width:120px;">'.dica('Hoje', 'Mostra a agenda com os eventos para hoje.').'Hoje&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_dia&tab=0&data=".$agora->format('%Y%m%d')."\");", "estilo/rondon/imagens/icones/todo.gif");
	 		}
		$menu->Add("atalhos1","meus_dados",'<span style="width:120px;">'.dica('Meus Dados', 'Mostra as suas informa��es de cadastro e outras que sejam de seu interesse.').'Meus&nbsp;Dados&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=ver_usuario&filtro_id_responsavel=".$Aplic->usuario_id."\");", "estilo/rondon/imagens/icones/usuario_mini.png");

		if (function_exists('openssl_sign')) $menu->Add("atalhos1","chaves2",'<span style="width:120px;">'.dica('Chaves', 'Abre uma janela onde poder� carregar a chave privada na mem�ria, ou criar o par de chaves p�blicas e privadas').'Chaves&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=email&a=chaves\");", "estilo/rondon/imagens/icones/chave_p.gif");





		$menu->add("root",'sistema', dica('Sistema','Op��es do Sistema.').'Sistema'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/config-sistema_p.png");
		if($Aplic->usuario_admin || $Aplic->usuario_super_admin) $menu->Add("sistema","sistema1",'<span style="width:120px;">'.dica('Configura��o do Sistema','Selecione esta op��o para acessar diversas op��es de configura��o do sistema.').'Configura��o&nbsp;do&nbsp;Sistema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=index\");", "estilo/rondon/imagens/icones/config-sistema_p.png");
		if($Aplic->usuario_admin || $Aplic->usuario_super_admin) $menu->Add("sistema","admin",dica('Administra��o dos '.ucfirst($config['usuarios']),'Selecione esta op��o para acessar a administra��o d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'Administra��o d'.$config['genero_usuario'].'s '.$config['usuarios'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=index\");", "estilo/rondon/imagens/icones/membros_p.png");
		$menu->add("sistema","sobre",dica('Sobre o '.$config['gpweb'],'Selecione esta op��o para acessar informa��es sobre o '.$config['gpweb'].'.').'Sobre o '.$config['gpweb'].dicaF(), "javascript: void(0);' onclick='javascript:window.open(\"?m=ajuda&dialogo=1\", \"Sobre\", \"width=700, height=600, left=0, top=0, scrollbars=yes, resizable=yes\");", "estilo/rondon/imagens/icones/informacao.gif");
		$menu->add("sistema","ajuda",dica('Ajuda','Selecione esta op��o para acessar tutoriais e manuais do '.$config['gpweb'].'.').'Ajuda'.dicaF(), "javascript: void(0);' onclick='javascript:window.open(\"?m=ajuda&a=manual&dialogo=1\", \"Ajuda\", \"width=800, height=600, left=0, top=0, scrollbars=yes, resizable=yes\");", "estilo/rondon/imagens/icones/ajuda.gif");
		
		$menu->add("root",'sair1', dica('Sair','Sair do Sistema.').'Sair'.dicaF(), "./index.php?logout=-1", "estilo/rondon/imagens/icones/sair.png");


		//vazio s� para melhorar a diagrama��o
		//$menu->Add("menu",'vazio1', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		echo $menu->render();
		echo '<script type="text/javascript">var mnu = document.getElementById("kmnuPrincipal"); if(mnu){mnu.style.zIndex="6000";}</script>';
		echo '</td></tr>';
		if (!$Aplic->celular) echo '<tr><td colspan="20" valign="top" style="background: url(estilo/rondon/imagens/nav_sombra.jpg);" align="left"><img width="1" height="13" src="estilo/rondon/imagens/nav_sombra.jpg"/></td></tr>';
		//echo '</table></td></tr>';

		echo '<tr><td colspan=2><table cellspacing=0 cellpadding="3" border=0 width="100%">';
		echo '<tr><td style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;">'.$msg_ini_saud.($Aplic->usuario_id > 0 ? ($Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra) : $visitante).$final_saudacao.'</td>';
		if ($Aplic->usuario_id > 0) {
			echo '<td valign="right" align="left" width="200"><form name="frm_pesquisa" method="POST"><input type="hidden" name="m" value="pesquisa" />';
			if ($podeAcessar_pesquisa) echo dica('Pesquisa', 'Este campo pesquisa em toda base do '.$config['gpweb'].', baseado no texto digitado.</p>Caso necessite de fazer uma pesquisa mais refinada, utilize o item <b>Pesquisa</b> na barra de menu acima.',TRUE).imagem('icones/procurar.png').dicaF().'&nbsp;<input class="texto" size="20" type="text" name="palavraChave" value="Pesquisa geral..." onclick="document.frm_pesquisa.palavraChave.value=\'\'" onblur="document.frm_pesquisa.palavraChave.value=\'Pesquisa geral...\'" />';
			else echo '&nbsp;';
			echo '</form></td>';
			
				echo '</tr>';
			}
		echo '</table></td></tr>';
		}

	
		
    echo '</table>';
    }

if (!$dialogo) echo $Aplic->getMsg();

function exibir_modulos_terceiros(){
	global $Aplic, $q, $menu;
	$q->adTabela('modulos');
	$q->adCampo('mod_diretorio,mod_ui_nome, mod_texto_botao, mod_ui_icone, mod_menu');
	$q->adOnde('mod_tipo !=\'core\'');
	$q->adOnde('mod_ativo=1');
	$q->adOrdem('mod_ui_ordem, mod_ui_nome');
	$modulos = $q->Lista();
	$q->limpar();
	foreach ($modulos as $modulo) {
		if($Aplic->checarModulo($modulo['mod_diretorio'], 'acesso')){
			//verifica se tem script de menu
			if ($modulo['mod_menu']){
				$qnt=0;
				$lista=explode(';',$modulo['mod_menu']);
				foreach($lista as $item){
					$linha=explode(':',$item);
					if (!$qnt) $menu->Add("menu", (isset($linha[4]) ? $linha[4] : $modulo['mod_diretorio']), dica($linha[0],$linha[3]).$linha[0].dicaF(), ($linha[2] ? "javascript: void(0);' onclick='url_passar(0, \"".$linha[2]."\");" : "javascript: void(0);"), ($linha[1]? 'modulos/'.$modulo['mod_diretorio'].'/imagens/'.$linha[1] : ''));
					else $menu->Add((isset($linha[5]) ? $linha[5] : $modulo['mod_diretorio']), (isset($linha[4]) ? $linha[4] : $modulo['mod_diretorio'].'_'.$qnt), dica($linha[0],$linha[3]).$linha[0].dicaF(), ($linha[2] ? "javascript: void(0);' onclick='url_passar(0, \"".$linha[2]."\");" : "javascript: void(0);"), ($linha[1] ? 'modulos/'.$modulo['mod_diretorio'].'/imagens/'.$linha[1] : ''));
					$qnt++;
					}
				}
			else $menu->Add("menu", $modulo['mod_diretorio'], dica($modulo['mod_ui_nome'],$modulo['mod_texto_botao']).$modulo['mod_ui_nome'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=".$modulo['mod_diretorio']."&a=index\");", 'modulos/'.$modulo['mod_diretorio'].'/imagens/'.$modulo['mod_ui_icone']);
			}
		}
	}

?>


<div id="fade" class="cobertura_negra"></div>
