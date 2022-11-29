<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	require_once BASE_DIR.'/incluir/funcoes_principais.php';
	require_once BASE_DIR.'/incluir/db_adodb.php';
	require_once BASE_DIR.'/classes/BDConsulta.class.php';
	require_once BASE_DIR.'/classes/ui.class.php';
    require_once BASE_DIR.'/classes/aplic.class.php';

    $Aplic = new CAplic();

    require_once BASE_DIR.'/classes/data.class.php';
    require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';

    set_time_limit(0);
    ignore_user_abort(true);

   /* salvarCache();*/

    $sql = new \BDConsulta();
    $sql->adTabela('projetos');
    $sql->adCampo('projeto_id');
    $sql->adOnde('projeto_portfolio = 0 AND projeto_template = 0');
    $projetos = $sql->Lista();
    $sql->limpar();

    $cache = CTarefaCache::getInstance();
    foreach($projetos as $projeto){
        echo 'Recalculando projeto: '.$projeto['projeto_id'].'<br/>';
        $cache->recalcularProjeto($projeto['projeto_id']);
        }

    foreach($projetos as $projeto){
        $sql = new \BDConsulta();
        $sql->adTabela('baseline_projetos');
        $sql->adCampo('baseline_id');
        $sql->adOnde('projeto_portfolio = 0 AND projeto_template = 0');
        $sql->adOnde('projeto_id = '.$projeto['projeto_id']);
        $baselines = $sql->Lista();
        $sql->limpar();

        if($baselines) {
            foreach($baselines as $baseline){
                echo 'Recalculando baseline: '.$baseline['baseline_id'].'<br/>';
                $cache->recalcularBaseline( $projeto[ 'projeto_id' ], $baseline['baseline_id'] );
                }
            }
        }
	}



function salvarCache () {
    //executa enquanto houverem entradas na tabela temporaria
    $sql=new BDConsulta;
    while(true) {
        $sql->adTabela('tarefas_cache');
        $sql->adCampo('*');
        //$sql->adOnde('projeto_id='.(int)$projeto_id);
        $sql->setLimite(1);
        $cacheData = $sql->Linha();
        $sql->limpar();
        if(!$cacheData || !count($cacheData)) break;
        $cachejs = json_decode($cacheData['dados']);

        //grava os dados das tarefas
        foreach ( $cachejs as $chave=>$dados) {
            $sql->adTabela('tarefas');
            foreach ($dados as $field=>$value)
                if ($field != 'dep') $sql->adAtualizar($field, $value);
            $sql->adOnde('tarefa_id = '.(int)$chave);
            $sql->exec();
            $sql->limpar();
        }

        //remove a entrada processada
        $sql->setExcluir('tarefas_cache');
        $sql->adOnde('projeto_id='.(int)$cacheData['projeto_id'].' AND time=\''.$cacheData['time'].'\'');
        $sql->exec();
        $sql->limpar();
    }
}
?>