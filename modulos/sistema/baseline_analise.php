<?php 
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');

global $bd;

$tabelas = array();

$tabelas['eb_arquivo'] = 'baseline_eb_arquivo';
$tabelas['eb_encerramento'] = 'baseline_eb_encerramento';
$tabelas['eb_situacao'] = 'baseline_eb_situacao';
$tabelas['eb_aceite'] = 'baseline_eb_aceite';
$tabelas['eb_mudanca_controle'] = 'baseline_eb_mudanca_controle';
$tabelas['eb_mudanca'] = 'baseline_eb_mudanca';
$tabelas['eb_mudanca_item'] = 'baseline_eb_mudanca_item';
$tabelas['eb_qualidade'] = 'baseline_eb_qualidade';
$tabelas['eb_qualidade_item'] = 'baseline_eb_qualidade_item';
$tabelas['eb_risco_item'] = 'baseline_eb_risco_item';
$tabelas['eb_risco'] = 'baseline_eb_risco';
$tabelas['eb_interessado'] = 'baseline_eb_interessado';
$tabelas['eb_comunicacao'] = 'baseline_eb_comunicacao';
$tabelas['eb_humano'] = 'baseline_eb_humano';
$tabelas['eb_humano_matriz'] = 'baseline_eb_humano_matriz';
$tabelas['eb_plano_item'] = 'baseline_eb_plano_item';
$tabelas['eb_plano'] = 'baseline_eb_plano';
$tabelas['eb_escopo'] = 'baseline_eb_escopo';
$tabelas['eb_iniciacao'] = 'baseline_eb_iniciacao';
$tabelas['eb_iniciacao_envolvido'] = 'baseline_eb_iniciacao_envolvido';
$tabelas['eb_implantacao'] = 'baseline_eb_implantacao';
$tabelas['eb_viabilidade'] = 'baseline_eb_viabilidade';
$tabelas['eb_viabilidade_envolvido'] = 'baseline_eb_viabilidade_envolvido';
$tabelas['eb_encerramento_campo'] = 'baseline_eb_encerramento_campo';
$tabelas['eb_situacao_campo'] = 'baseline_eb_situacao_campo';
$tabelas['eb_mudanca_controle_campo'] = 'baseline_eb_mudanca_controle_campo';
$tabelas['eb_mudanca_controle_item'] = 'baseline_eb_mudanca_controle_item';
$tabelas['eb_mudanca_controle_escopo'] = 'baseline_eb_mudanca_controle_escopo';
$tabelas['eb_mudanca_controle_custo'] = 'baseline_eb_mudanca_controle_custo';
$tabelas['eb_mudanca_campo'] = 'baseline_eb_mudanca_campo';
$tabelas['eb_interessado_campo'] = 'baseline_eb_interessado_campo';
$tabelas['eb_comunicacao_campo'] = 'baseline_eb_comunicacao_campo';
$tabelas['eb_plano_item_depts'] = 'baseline_eb_plano_item_depts';
$tabelas['eb_plano_item_custos'] = 'baseline_eb_plano_item_custos';
$tabelas['eb_plano_item_designados'] = 'baseline_eb_plano_item_designados';
$tabelas['eb_plano_item_gastos'] = 'baseline_eb_plano_item_gastos';
$tabelas['eb_plano_item_h_custos'] = 'baseline_eb_plano_item_h_custos';
$tabelas['eb_plano_item_h_gastos'] = 'baseline_eb_plano_item_h_gastos';
$tabelas['eb_escopo_campo'] = 'baseline_eb_escopo_campo';
$tabelas['eb_iniciacao_campo'] = 'baseline_eb_iniciacao_campo';
$tabelas['eb_implantacao_campo'] = 'baseline_eb_implantacao_campo';
$tabelas['eb_viabilidade_campo'] = 'baseline_eb_viabilidade_campo';
$tabelas['evento_gestao'] = 'baseline_evento_gestao';
$tabelas['eventos'] = 'baseline_eventos';
$tabelas['folha_ponto'] = 'baseline_folha_ponto';
$tabelas['folha_ponto_arquivo'] = 'baseline_folha_ponto_arquivo';
$tabelas['folha_ponto_gasto'] = 'baseline_folha_ponto_gasto';
$tabelas['municipio_lista'] = 'baseline_municipio_lista';
$tabelas['pagamento'] = 'baseline_pagamento';
$tabelas['projeto_area'] = 'baseline_projeto_area';
$tabelas['projeto_cia'] = 'baseline_projeto_cia';
$tabelas['projeto_contatos'] = 'baseline_projeto_contatos';
$tabelas['projeto_depts'] = 'baseline_projeto_depts';
$tabelas['projeto_gestao'] = 'baseline_projeto_gestao';
$tabelas['projeto_integrantes'] = 'baseline_projeto_integrantes';
$tabelas['projeto_ponto'] = 'baseline_projeto_ponto';
$tabelas['projeto_portfolio'] = 'baseline_projeto_portfolio';
$tabelas['priorizacao'] = 'baseline_priorizacao';
$tabelas['projeto_stakeholder'] = 'baseline_projeto_stakeholder';
$tabelas['projetos'] = 'baseline_projetos';
$tabelas['recurso_ponto'] = 'baseline_recurso_ponto';
$tabelas['recurso_ponto_arquivo'] = 'baseline_recurso_ponto_arquivo';
$tabelas['recurso_ponto_gasto'] = 'baseline_recurso_ponto_gasto';
$tabelas['recurso_tarefa'] = 'baseline_recurso_tarefa';
$tabelas['tarefa_contatos'] = 'baseline_tarefa_contatos';
$tabelas['tarefa_custos'] = 'baseline_tarefa_custos';
$tabelas['tarefa_dependencias'] = 'baseline_tarefa_dependencias';
$tabelas['tarefa_depts'] = 'baseline_tarefa_depts';
$tabelas['tarefa_designado_periodos'] = 'baseline_tarefa_designado_periodos';
$tabelas['tarefa_designados'] = 'baseline_tarefa_designados';
$tabelas['tarefa_entrega'] = 'baseline_tarefa_entrega';
$tabelas['tarefa_gastos'] = 'baseline_tarefa_gastos';
$tabelas['log'] = 'baseline_log';
$tabelas['log_arquivo'] = 'baseline_log_arquivo';
$tabelas['tarefas'] = 'baseline_tarefas';
$tabelas['tarefa_cia'] = 'baseline_tarefa_cia';
$tabelas['projeto_atividade'] = 'baseline_projeto_atividade';
$tabelas['projeto_regiao'] = 'baseline_projeto_regiao';

foreach($tabelas as $tabela => $baseline){
    analisar_baseline($bd, $tabela, $baseline);
}

/**
 * @param ADOConnection $bd
 * @param string $tabela
 * @param string $baseline
 */
function analisar_baseline($bd, $tabela, $baseline){
    $tableColumns = $bd->MetaColumns($tabela);
    $baselineColumns = $bd->MetaColumns($baseline);

    $baselineColumnsProblems = array();

    if($tableColumns !== false && $baselineColumns !== false){
        foreach($tableColumns as $name => $column){
            if(!array_key_exists($name, $baselineColumns)){
                $baselineColumnsProblems[$name] = 'Não existe no baseline';
            }
            else{
                $c1 = $tableColumns[$name];
                $c2 = $baselineColumns[$name];

                if($c1->type != $c2->type){
                    $text = 'Tipos diferentes';

                    if($c1->max_lenght != $c2->max_lenght){
                        $text .= ' e comprimento diferente';
                    }

                    $baselineColumnsProblems[ $name] = $text;
                }
                else if($c1->max_lenght != $c2->max_lenght){
                    $baselineColumnsProblems[$name] = 'Comprimento diferente';
                }
            }
        }
    }

    if($tableColumns === false || $baselineColumns === false || !empty($baselineColumnsProblems)){
        echo "<div style='color: white; background-color: red; margin-bottom: 10px; padding: 4px;'>{$tabela} - ";
        if($tableColumns === false){
            echo "Tabela '{$tabela}' não existe";
            if($baselineColumns === false){
                echo " e baseline '{$baseline}' não existe";
            }
        }
        else if($baselineColumns === false){
            echo "Baseline '{$baseline}' não existe";
        }
        else if(!empty($baselineColumnsProblems)) {
            echo "Campos divergentes";

            echo '<div style="background-color: white; color: black; padding: 15px;">';
            foreach($baselineColumnsProblems as $nome => $texto){
                echo '<div>'.strtolower($nome) . ": {$texto}</div>";
            }
            echo '</div>';
        }

        echo '</div>';
    }

}
?>