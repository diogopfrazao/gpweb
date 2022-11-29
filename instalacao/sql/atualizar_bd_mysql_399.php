<?php
global $config;

$sql = new BDConsulta;
$sql->setExcluir('recurso_tarefa');
$sql->adOnde('recurso_tarefa_tarefa IS NULL');
$sql->adOnde('recurso_tarefa_evento IS NULL');
$sql->exec();
$sql->limpar();

?>