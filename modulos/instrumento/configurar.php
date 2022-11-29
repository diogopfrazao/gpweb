<?php 
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!$Aplic->checarModulo('sistema', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Configuração', 'instrumento.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&a=vermods', 'voltar','','Voltar','Voltar à tela de administração de módulos.');
$botoesTitulo->mostrar();

$sql = new BDConsulta();



if (getParam($_REQUEST, 'gravar', null)){
	$sql->adTabela('instrumento_config');
	$sql->adAtualizar('instrumento_config_exibe_funcao', getParam($_REQUEST, 'instrumento_config_exibe_funcao', null));
	$sql->adAtualizar('instrumento_config_exibe_tipo_parecer', getParam($_REQUEST, 'instrumento_config_exibe_tipo_parecer', null));
	$sql->adAtualizar('instrumento_config_exibe_linha2', getParam($_REQUEST, 'instrumento_config_exibe_linha2', null));
	$sql->adAtualizar('instrumento_config_linha2_legenda', getParam($_REQUEST, 'instrumento_config_linha2_legenda', null));
	$sql->adAtualizar('instrumento_config_exibe_linha3', getParam($_REQUEST, 'instrumento_config_exibe_linha3', null));
	$sql->adAtualizar('instrumento_config_linha3_legenda', getParam($_REQUEST, 'instrumento_config_linha3_legenda', null));
	$sql->adAtualizar('instrumento_config_exibe_linha4', getParam($_REQUEST, 'instrumento_config_exibe_linha4', null));
	$sql->adAtualizar('instrumento_config_linha4_legenda', getParam($_REQUEST, 'instrumento_config_linha4_legenda', null));
	$sql->adOnde('instrumento_config_id = 1');
	$sql->exec();
	$sql->limpar();
	}


$sql->adTabela('instrumento_config');
$sql->adCampo('instrumento_config.*');
$sql->adOnde('instrumento_config_id = 1');
$linha = $sql->linha();
$sql->limpar();

$opcao=array(0=>'Não', 1=>'Sim');

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="gravar" value="1" />';

echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0>';

echo '<tr><td align="right" width=150>'.dica('Exibir Função', 'O campo de função para o cadastro dos '.$config['usuarios'].' internos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].' estará visivel.').'Exibir Função:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'instrumento_config_exibe_funcao', 'style="width:50px;" class="texto"', $linha['instrumento_config_exibe_funcao']).'</td></tr>';
echo '<tr><td align="right">'.dica('Exibir Parecer', 'O campo de parecer para o cadastro dos '.$config['usuarios'].' internos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].' estará visivel.').'Exibir Parecer:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'instrumento_config_exibe_tipo_parecer', 'style="width:50px;" class="texto"', $linha['instrumento_config_exibe_tipo_parecer']).'</td></tr>';
echo '<tr><td align="right">'.dica('Exibir 2ª Linha', 'A 2ª linha de dado do cadastro dos usuários externos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].' estará visivel.').'Exibir 2ª Linha:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'instrumento_config_exibe_linha2', 'style="width:50px;" class="texto"', $linha['instrumento_config_exibe_linha2']).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Legenda da 2ª Linha', 'A legenda da 2ª linha de dado do cadastro dos usuários externos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Legenda da 2ª Linha:'.dicaF().'</td><td colspan="2"><input type="text" id="instrumento_config_linha2_legenda" name="instrumento_config_linha2_legenda" value="'.$linha['instrumento_config_linha2_legenda'].'" style="width:150px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('Exibir 3ª Linha', 'A 3ª linha de dado do cadastro dos usuários externos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].' estará visivel.').'Exibir 3ª Linha:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'instrumento_config_exibe_linha3', 'style="width:50px;" class="texto"', $linha['instrumento_config_exibe_linha3']).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Legenda da 3ª Linha', 'A legenda da 3ª linha de dado do cadastro dos usuários externos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Legenda da 3ª Linha:'.dicaF().'</td><td colspan="2"><input type="text" id="instrumento_config_linha3_legenda" name="instrumento_config_linha3_legenda" value="'.$linha['instrumento_config_linha3_legenda'].'" style="width:150px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('Exibir 4ª Linha', 'A 4ª linha de dado do cadastro dos usuários externos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].' estará visivel.').'Exibir 4ª Linha:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'instrumento_config_exibe_linha4', 'style="width:50px;" class="texto"', $linha['instrumento_config_exibe_linha4']).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Legenda da 4ª Linha', 'A legenda da 4ª linha de dado do cadastro dos usuários externos que assinam '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Legenda da 4ª Linha:'.dicaF().'</td><td colspan="2"><input type="text" id="instrumento_config_linha4_legenda" name="instrumento_config_linha4_legenda" value="'.$linha['instrumento_config_linha4_legenda'].'" style="width:150px;" class="texto" /></td></tr>';


echo '<input type="hidden" name="instrumento_campo_id" id="instrumento_campo_id" value="" />';

echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0>';

echo '<tr><td colspan=2><table cellspacing=1 cellpadding=0>';

echo '<input type="hidden" name="instrumento_campo_servico" id="instrumento_campo_servico" value="0" />';
echo '<tr><td align="right" style="white-space: nowrap" width=150>'.dica('Nome', 'Nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="instrumento_campo_nome" id="instrumento_campo_nome" value="" maxlength="255" style="width:300px;" /></td></tr>';
echo '</table></td>';
echo '<td id="adicionar_instrumento_campo" style="display:"><a href="javascript: void(0);" onclick="incluir_instrumento_campo();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir um custo avulso.').'</a></td>';
echo '<td id="confirmar_instrumento_campo" style="display:none"><a href="javascript: void(0);" onclick="limpar_instrumento_campo();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do custo avulso.').'</a><a href="javascript: void(0);" onclick="incluir_instrumento_campo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do custo.').'</a></td>';
echo '</tr></table></td></tr>';


$sql->adTabela('instrumento_campo');
$sql->adCampo('instrumento_campo.*');
$sql->adOrdem('instrumento_campo_ordem');
$linhas=$sql->Lista();
$sql->limpar();
$qnt=0;


echo '<tr><td></td><td><div id="combo_instrumento_campo"><table border=0 cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><th>'.dica('Nome', 'Nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Nome'.dicaF().'</th><th></th></tr>';

foreach ($linhas as $linha) {
	echo '<tr align="center">';
	echo '<td align="left" width="300"><a href="javascript:void(0);" onclick="javascript:mostrar_formulario('.$linha['instrumento_campo_id'].');">'.$linha['instrumento_campo_nome'].'</a></td>';
	echo '<td width="72" align="left">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'<a href="javascript:void(0);" onclick="javascript:editar_instrumento_campo('.$linha['instrumento_campo_id'].');">'.imagem('icones/editar.gif').'</a>'.dicaF();
	if ($linha['instrumento_campo_id']!=1) echo dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'<a href="javascript:void(0);" onclick="javascript:excluir_instrumento_campo('.$linha['instrumento_campo_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF();
	echo '</td>';	
	echo '</tr>';
	}		
echo '</table></div></td></tr>';


echo '<tr><td></td><td >Clique no nome de algum modelo d'.($config['genero_instrumento']=='a' ? 'a': 'e').' '.$config['instrumento'].' para ver os campos do mesmo</td></tr>';
echo '<tr><td colspan=2><div id="combo_formulario">';
echo '</div></td></tr>';







echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Clique neste botão para salvar as alterações.','','env.submit()').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();



echo '</form>';
?>


<script type="text/javascript">
	
	
function check(chave){
	xajax_texto(document.getElementById('instrumento_campo_id').value, chave, (document.getElementById(chave).checked ? 1 : 0));
	}	

function texto(chave){
	xajax_texto(document.getElementById('instrumento_campo_id').value, chave, document.getElementById(chave).value);
	}	

	
	
function mostrar_formulario(instrumento_campo_id){
	document.getElementById('instrumento_campo_id').value=instrumento_campo_id;
	xajax_mostrar_formulario(instrumento_campo_id);
	}	
	
	
	
	
function editar_instrumento_campo(instrumento_campo_id){
	xajax_editar_instrumento_campo(instrumento_campo_id);
	document.getElementById('adicionar_instrumento_campo').style.display="none";
	document.getElementById('confirmar_instrumento_campo').style.display="";
	}


function limpar_instrumento_campo(){
	document.getElementById('instrumento_campo_nome').value='';
	document.getElementById('instrumento_campo_id').value=null;
	document.getElementById('adicionar_instrumento_campo').style.display='';
	document.getElementById('confirmar_instrumento_campo').style.display='none';
	}

function incluir_instrumento_campo(){
	if (document.getElementById('instrumento_campo_nome').value){
		xajax_incluir_instrumento_campo(
			document.getElementById('instrumento_campo_id').value,
			document.getElementById('instrumento_campo_nome').value
			);
		__buildTooltip();
		limpar_instrumento_campo();
		}
	else {
		alert('Precisa ter um nome');
		document.getElementById('instrumento_campo_nome').focus();
		}		
	}

function excluir_instrumento_campo(instrumento_campo_id){
	if (confirm('Tem certeza que deseja excluir?')) {
		xajax_excluir_instrumento_campo(instrumento_campo_id);
		__buildTooltip();
		}
	}

function mudar_posicao_instrumento_campo(ordem, instrumento_campo_id, direcao){
	xajax_mudar_posicao_instrumento_campo(ordem, instrumento_campo_id, direcao);
	__buildTooltip();
	}
</script>	