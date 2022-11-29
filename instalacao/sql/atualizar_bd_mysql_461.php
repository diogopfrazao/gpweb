<?php
global $config;

$sql = new BDConsulta;
$sql->adTabela('campo_customizado');
$sql->adCampo('campo_customizado_id, campo_customizado_nome, campo_customizado_modulo, campo_customizado_descricao');
$sql->adOnde("campo_customizado_tipo_html IN ('select', 'selecionar', 'textinput', 'textarea', 'checkbox', 'data', 'valor', 'href', 'formula')");
$lista_customizado = $sql->lista();
$sql->limpar();


foreach($lista_customizado AS $linha){

    //pesquisa se já existe
    $sql->adTabela('campo_formulario');
    $sql->adCampo('campo_formulario_id');
    $sql->adOnde('campo_formulario_tipo=\''.$linha['campo_customizado_modulo'].'_ex\'');
    $sql->adOnde('campo_formulario_campo=\''.$linha['campo_customizado_nome'].'_ex\'');
    $existe = $sql->resultado();
    $sql->limpar();

    if (!$existe){
        $sql->adTabela('campo_formulario');
        $sql->adInserir('campo_formulario_campo', $linha['campo_customizado_nome'].'_ex');
        $sql->adInserir('campo_formulario_tipo', $linha['campo_customizado_modulo'].'_ex');
        $sql->adInserir('campo_formulario_descricao', $linha['campo_customizado_descricao']);
        $sql->adInserir('campo_formulario_customizado', $linha['campo_customizado_id']);
        $sql->exec();
        $sql->limpar();

    }
}
?>
