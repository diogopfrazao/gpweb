<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;
$sql->adTabela('perfil');
$sql->adCampo('perfil.*');
$perfis=$sql->lista();
$sql->limpar();


$perfil_id=getParam($_REQUEST, 'perfil_id', 0);

$botoesTitulo = new CBlocoTitulo('Perfis de Acesso', 'cadeado.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
$blocos = array();
$blocos['m=sistema'] = 'Sistema';



echo '<form name="env" id="env" method="post" action="?m=sistema&u=perfis">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="u" value="perfis" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_perfil_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="perfil_id" value="'.$perfil_id.'" />';


echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std2"><tr><th>&nbsp;</th>';
echo '<th style="width:270px;">'.dica('Nome do Perfil', 'Nome do perfil de acesso.').'Nome do Perfil'.dicaF().'</th>';
echo '<th>'.dica('Descrição', 'Descrição do perfil de acesso.').'Descrição'.dicaF().'</th><th>&nbsp;</th></tr>';

foreach ($perfis as $linha) {
	if (($perfil_id == $linha['perfil_id'] || $linha['perfil_id'] == 0) && $podeEditar) {
	
		
		echo '<tr><td>&nbsp;</td>';
		echo '<td valign="middle"><input type="text" style="width:270px;" name="perfil_nome" value="'.$linha['perfil_nome'].'" class="texto" /></td>';
		echo '<td valign="middle"><table cellpadding=0 cellspacing=0><tr><td><input type="text" style="width:550px;" name="perfil_descricao" class="texto" value="'.$linha['perfil_descricao'].'"></td>';
		echo '<td><a href="javascript: void(0);" onclick="adicionar('.$linha['perfil_id'].');">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição.').'</a></td><td><a href="javascript: void(0);" onclick="javascript:url_passar(0, \'m=sistema&u=perfis\');">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição.').'</a></td></tr></table></td></tr>';
		} 
	else {
		echo '<tr><td width="50" valign="top">';
		if ($podeEditar) echo dica('Editar Nome', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o nome e descrição deste perfil de acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=perfis&perfil_id='.$linha['perfil_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF().dica('Editar Perfil de Acesso', 'Clique neste ícone '.imagem('icones/cadeado.gif').' para editar os níveis de acesso deste perfil.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=perfis&a=ver_perfil&perfil_id='.$linha['perfil_id'].'\');" >'.imagem('icones/cadeado.gif').'</a>'.dicaF();
		if ($podeExcluir) echo dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este perfil de acesso.').'<a href=\'javascript:excluir('.$linha['perfil_id'].')\'>'.imagem('icones/remover.png').'</a>'.dicaF();
		echo '</td><td valign="top">'.$linha['perfil_nome'].'</td><td valign="top">'.$linha['perfil_descricao'].'</td><td valign="top" width="16">&nbsp;</td></tr>';
		}
	}	

if (!$perfil_id) echo '<tr>
<td>&nbsp;</td>
<td valign="middle"><input type="text" style="width:250px;" name="perfil_nome" value="" class="texto" /></td>
<td valign="middle"><table cellpadding=0 cellspacing=0><tr>
<td><input type="text" style="width:550px;" name="perfil_descricao" class="texto" value=""></td>
<td><a href="javascript: void(0);" onclick="adicionar(null);">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o perfil de acesso.').'</a></td></tr></table></td></tr>';
	
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>
<script type="text/javascript">
	
function adicionar(id){
	f = document.env;
	f.perfil_id.value = id;
	if (f.perfil_nome.value!='' && f.perfil_descricao.value!='') f.submit(); 
	else alert('Preencha tanto o nome quanto a descrição do perfil');
	}	
	
function limpar(){
	f = document.env;
	f.perfil_nome.value='';
	f.perfil_descricao.value='';
	}		
	
	
function excluir(id) {
	if (confirm('Tem certeza que deseja excluir?')) {
		f = document.env;
		f.del.value = 1;
		f.perfil_id.value = id;
		f.submit();
	}
}
</script>
