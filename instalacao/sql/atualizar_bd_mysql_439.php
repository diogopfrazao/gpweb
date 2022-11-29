<?php
global $config, $bd;

//Garantir que todas as Cias tenham o campo cia_superior preenchido e haja apenas uma única com cia_id=cia_superior
$sql = new BDConsulta;
$sql->adTabela('cias');
$sql->adCampo('cia_id');
$sql->adOnde('(cia_id=cia_superior) OR cia_superior IS NULL');
$sql->adOrdem('cia_id ASC');
$cia_superior=$sql->Resultado();
$sql->limpar();

$sql->adTabela('cias');
$sql->adAtualizar('cia_superior', (int)$cia_superior);
$sql->adOnde('(cia_id=cia_superior) OR cia_superior IS NULL');
$sql->exec();
$sql->limpar();
?>