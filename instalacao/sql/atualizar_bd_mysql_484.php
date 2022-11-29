<?php
$sql = new BDConsulta;
//buscar perfis de observador
$sql->adTabela('perfil_acesso');	
$sql->adCampo('DISTINCT perfil_acesso_perfil');
$sql->adOnde('perfil_acesso_modulo=\'nao_admin\'');
$sql->adOnde('perfil_acesso_acesso=1');
$sql->adOnde('perfil_acesso_editar=0');
$sql->adOnde('perfil_acesso_adicionar=0');
$sql->adOnde('perfil_acesso_excluir=0');
$sql->adOnde('perfil_acesso_aprovar=0');
$sql->adOnde('perfil_acesso_negar=0');
$perfis1 = $sql->carregarColuna();
$sql->limpar();

$perfis=array();
//checar se perfil é só uma linha
foreach ($perfis1 as $perfil_id){
	$sql->adTabela('perfil_acesso');	
	$sql->adCampo('COUNT(perfil_acesso_id)');
	$sql->adOnde('perfil_acesso_perfil='.(int)$perfil_id);
	$qnt = $sql->resultado();
	$sql->limpar();
	if ($qnt==1) $perfis[]=$perfil_id;
	}


if (count($perfis)){
	$sql->adTabela('usuarios');
	$sql->esqUnir('perfil_usuario', 'perfil_usuario', 'perfil_usuario_usuario=usuario_id');
	$sql->adAtualizar('usuario_observador', 1);
	$sql->adOnde('perfil_usuario_perfil IN ('.implode(',', $perfis).')');
	$sql->exec();
	$sql->limpar();	
	}
?>