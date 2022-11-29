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

global $m, $a, $u, $obj, $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $projeto_tipos, $tab, $podeEditar, $ordemDir, $ordenarPor, $responsavel, $supervisor, $autoridade, $cliente, $nao_apenas_superiores,$lista_cias, $projeto_expandido, $favorito_id, $dept_id, $lista_depts, $pesquisar_texto, $projeto_tipo,  $projetostatus, $Aplic, $cia_id, $projStatus, $projetos_status, $projeto_setor, $projeto_segmento, $projeto_intervencao, $projeto_tipo_intervencao, $estado_sigla, $municipio_id, $dialogo, $filtro_area, $filtro_criterio, $filtro_opcao, $filtro_prioridade, $filtro_perspectiva, $filtro_tema, $filtro_objetivo, $filtro_fator, $filtro_estrategia, $filtro_meta, $filtro_canvas, $campos_extras, $xpg_totalregistros_projetos, $xpg_totalregistros_recebidos, $usar_periodo, $reg_data_inicio, $reg_data_fim,
	$projeto_id,
	$gestao_projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;

$vetor_selecionado=explode(',', $selecionado);
$selecionado=array();
foreach($vetor_selecionado as $valor) $selecionado[$valor]=$valor;

$from_lista = true;
if (!($m=='projetos' && $a=='index')) {
    $from_lista = false;
	$gestao_projeto_id=$projeto_id;
	if ($m=='projetos' && $a=='ver') {
		if (!$obj->projeto_portfolio) $projeto_id=null;
		else $gestao_projeto_id=null;
		}
	else $projeto_id=null;
	}





$seta=array('asc'=>'seta-cima.gif', 'desc'=>'seta-baixo.gif');
//ordena��o dos projetos

$pagina=getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = ($dialogo ? 900000 : $config['qnt_projetos']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);
$mostrar_todos_projetos = false;
if (!is_array($projStatus)) $projStatus = getSisValor('StatusProjeto');
$vetorStatus=array(''=>'')+$projStatus;
$mover=array();
$mover[]='';

for ($i=1;$i<=12;$i++) $mover['m'.$i]='+'.($i < 10 ? '0':'').$i.' mes'.($i>1 ? 'es' : '');
$mover['m24']='+24 meses';
$mover['m36']='+36 meses';
$mover['m48']='+48 meses';
for ($i=1;$i<=5;$i++) $mover['s'.$i]='+'.($i < 10 ? '0':'').$i.' semana'.($i>1 ? 's' : '');
for ($i=1;$i<=30;$i++) $mover['d'.$i]='+'.($i < 10 ? '0':'').$i.' dia'.($i>1 ? 's' : '');
for ($i=-1;$i>=-12;$i--) $mover['m'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' mes'.($i<-1 ? 'es' : '');
$mover['m-24']='-24 meses';
$mover['m-36']='-36 meses';
$mover['m-48']='-48 meses';
for ($i=-1;$i>=-5;$i--) $mover['s'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' semana'.($i<-1 ? 's' : '');
for ($i=-1;$i>=-30;$i--) $mover['d'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' dia'.($i<-1 ? 's' : '');


$sql = new BDConsulta;

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projetos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'projetos\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}

if ($exibir['projeto_fase']) $projetoFase = getSisValor('projetoFase');


require_once (BASE_DIR.'/modulos/projetos/projetos.class.php');

//para quando nao venho de lista de projetos
if ($nao_apenas_superiores==null) $nao_apenas_superiores=1;

$filtrosBuilder = new FiltrosProjetoBuilder();

if(!$from_lista){
    //ordena��o � carregado pelo index, mas quando vem de detalhes de um relacionado
    // n�o passa pelo index dos projetos

    if (isset($_REQUEST['ordemPor'])) $Aplic->setEstado('ProjIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
    $ordenarPor = $Aplic->getEstado('ProjIdxOrdemPor') ? $Aplic->getEstado('ProjIdxOrdemPor') : 'projeto_nome';

    if (isset($_REQUEST['ordemDir'])) $Aplic->setEstado('ordemDir', getParam($_REQUEST, 'ordemDir', ''));
    $ordemDir = $Aplic->getEstado('ordemDir') ? $Aplic->getEstado('ordemDir') : 'desc';
    if ($ordemDir == 'asc') {$ordemDir = 'desc';}
    else $ordemDir = 'asc';
    }
else{
    //filtros a serem aplicados somente quando chamado pelo index
    $filtrosBuilder->setUsuarioId($responsavel)
        ->setEnvolvimento($envolvimento)
        ->setSupervisor($supervisor)
        ->setAutoridade($autoridade)
        ->setCliente($cliente)
        ->setCiaId($cia_id)
        ->setListaCias($lista_cias)
        ->setUsar_periodo($usar_periodo)
        ->setReg_data_inicio($reg_data_inicio)
        ->setReg_data_fim($reg_data_fim)
        ->setProjetoTipo($projeto_tipo)
        ->setProjetoSetor($projeto_setor)
        ->setProjetoSegmento($projeto_segmento)
        ->setProjetoIntervencao($projeto_intervencao)
        ->setProjetoTipoIntervencao($projeto_tipo_intervencao)
        ->setEstadoSigla($estado_sigla)
        ->setMunicipioId($municipio_id)
        ->setPesquisarTexto($pesquisar_texto)
        ->setMostrarProjRespPertenceDept(false)
        ->setDeptId($lista_depts ? $lista_depts : $dept_id)
        ->setFavoritoId($favorito_id)
        ->setProjetoStatus($projetostatus)
        ->setProjetoExpandido($projeto_expandido)
        ->setNaoApenasSuperiores($nao_apenas_superiores)
        ->setDataInicio(null)
        ->setDataTermino(null)
        ->setFiltroArea($filtro_area)
        ->setFiltroCriterio($filtro_criterio)
        ->setFiltroOpcao($filtro_opcao)
        ->setFiltroPrioridade($filtro_prioridade)
        ->setFiltroPerspectiva($filtro_perspectiva)
        ->setFiltroTema($filtro_tema)
        ->setFiltroObjetivo($filtro_objetivo)
        ->setFiltroFator($filtro_fator)
        ->setFiltroEstrategia($filtro_estrategia)
        ->setFiltroMeta($filtro_meta)
        ->setFiltroCanvas($filtro_canvas)
        ->setFiltroExtra($campos_extras);
    }

//filtros a serem aplicados quando chamado pelo index ou detalhes do relacionado
$filtrosBuilder->setOrdenarPor($ordenarPor)
    ->setOrdemDir($ordemDir)
    ->setExibir($exibir)
    ->setPontoInicio(($pagina - 1) * $xpg_tamanhoPagina)
    ->setLimite($xpg_tamanhoPagina)
    ->setRecebido(false)
    ->setPortfolio(false)
    ->setTemplate(false)
    ->setPortfolioPai(isset($projeto_id) ? $projeto_id : null)
    ->setTarefaId( $tarefa_id )
    ->setProjetoId( $gestao_projeto_id )
    ->setPgPerspectivaId( $pg_perspectiva_id )
    ->setTemaId( $tema_id )
    ->setPgObjetivoEstrategicoId( $objetivo_id )
    ->setPgFatorCriticoId( $fator_id )
    ->setPgEstrategiaId( $pg_estrategia_id )
    ->setPgMetaId( $pg_meta_id )
    ->setPraticaId( $pratica_id )
    ->setPraticaIndicadorId( $pratica_indicador_id )
    ->setPlanoAcaoId( $plano_acao_id )
    ->setCanvasId( $canvas_id )
    ->setRiscoId( $risco_id )
    ->setRiscoRespostaId( $risco_resposta_id )
    ->setCalendarioId( $calendario_id )
    ->setMonitoramentoId( $monitoramento_id )
    ->setAtaId( $ata_id )
    ->setMswotId( $mswot_id )
    ->setSwotId( $swot_id )
    ->setOperativoId( $operativo_id )
    ->setInstrumentoId( $instrumento_id )
    ->setRecursoId( $recurso_id )
    ->setProblemaId( $problema_id )
    ->setDemandaId( $demanda_id )
    ->setProgramaId( $programa_id )
    ->setLicaoId( $licao_id )
    ->setEventoId( $evento_id )
    ->setLinkId( $link_id )
    ->setAvaliacaoId( $avaliacao_id )
    ->setTgnId( $tgn_id )
    ->setBrainstormId( $brainstorm_id )
    ->setGutId( $gut_id )
    ->setCausaEfeitoId( $causa_efeito_id )
    ->setArquivoId( $arquivo_id )
    ->setForumId( $forum_id )
    ->setChecklistId( $checklist_id )
    ->setAgendaId( $agenda_id )
    ->setAgrupamentoId( $agrupamento_id )
    ->setPatrocinadorId( $patrocinador_id )
    ->setTemplateId( $template_id )
    ->setPainelId( $painel_id )
    ->setPainelOdometroId( $painel_odometro_id )
    ->setPainelComposicaoId( $painel_composicao_id )
    ->setTrId( $tr_id )
    ->setMeId( $me_id )
    ->setPlano_acao_itemId( $plano_acao_item_id )
    ->setBeneficioId( $beneficio_id )
    ->setPainel_slideshowId( $painel_slideshow_id )
    ->setProjeto_viabilidadeId( $projeto_viabilidade_id )
    ->setProjeto_aberturaId( $projeto_abertura_id )
    ->setPgId( $pg_id )
    ->setPainelOdometroId( $painel_odometro_id )
    ->setPainelComposicaoId( $painel_composicao_id )
    ->setTrId( $tr_id )
    ->setMeId( $me_id )
    ->setPlano_acao_itemId( $plano_acao_item_id )
    ->setBeneficioId( $beneficio_id )
    ->setPainel_slideshowId( $painel_slideshow_id )
    ->setProjeto_viabilidadeId( $projeto_viabilidade_id )
    ->setProjeto_aberturaId( $projeto_abertura_id )
    ->setPgId( $pg_id )
    ->setSstiId( $ssti_id )
    ->setLaudoId( $laudo_id )
    ->setTreloId( $trelo_id )
    ->setTrelo_cartaoId( $trelo_cartao_id )
    ->setPdclId( $pdcl_id )
    ->setPdcl_itemId( $pdcl_item_id )
    ->setOsId( $os_id );
		
if (is_null($xpg_totalregistros_projetos)){
	$xpg_totalregistros_projetos = (int)projetos_quantidade($filtrosBuilder);
	}

$xpg_totalregistros = $xpg_totalregistros_projetos;



if($tab != 1 || !$from_lista) {
	$projetos=projetos_inicio_data($filtrosBuilder);
	}
else {
  $xpg_totalregistros = $xpg_totalregistros_recebidos;
  $filtrosBuilder->setRecebido(true);
	$projetos=projetos_inicio_data($filtrosBuilder);
	}


$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;




if ($xpg_total_paginas > 1){
	mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['projeto'], $config['projetos'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
	}

echo '<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>';
echo '<table id="tblProjetos" width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	
if ($exibir['cor']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cor&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Cor', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por cor.<br>Para facilitar a visualiza��o d'.$config['genero_projeto'].'s '.$config['projetos'].' � conveniente escolher cores distintas para cada um.').($ordenarPor=='projeto_cor' ? imagem('icones/'.$seta[$ordemDir]) : '').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nome dos mesmos.').($ordenarPor=='projeto_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['projeto']). dicaF().'</a></th>';
if ($exibir['fisico']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_percentagem&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('F�sico Executado', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo f�sico executado.').($ordenarPor=='projeto_percentagem' ? imagem('icones/'.$seta[$ordemDir]) : '').'%'.dicaF().'</a></th>';
if ($Aplic->profissional && $exibir['projeto_aprovado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.($tab ? '&tab='.$tab : '').'&ordemPor=projeto_aprovado&ordemDir='.($ordemDir ? '0' : '1').'\');">'.($ordenarPor=='projeto_aprovado' ? imagem('icones/'.$seta[$ordemDir]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=priorizacao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Prioriza��o', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela prioriza��o.').($ordenarPor=='priorizacao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Prioriza��o'.dicaF().'</a></th>';
if ($exibir['prioridade']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_prioridade&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Prioridade', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por prioridade.').($ordenarPor=='projeto_prioridade' ? imagem('icones/'.$seta[$ordemDir]) : '').'P'.dicaF().'</a></th>';
if ($exibir['cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cia_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_organizacao'].' '.$config['organizacao'].' respons�vel.').($ordenarPor=='projeto_cia_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if (isset($exibir['cias']) && $exibir['cias'] && !$selecao) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s n'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['organizacoes']).dicaF().'</th>';
if (isset($exibir['dept']) && $exibir['dept']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['departamento']).dicaF().'</th>';
if ($exibir['depts'] && !$selecao) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s n'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['inicio']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.'&u='.$u.'&ordemPor=projeto_data_inicio&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('In�cio Previsto', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de in�cio previsto.').($ordenarPor=='projeto_data_inicio' ? imagem('icones/'.$seta[$ordemDir]) : '').'In�cio Previsto'.dicaF().'</a></th>';
if ($exibir['termino']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.'&u='.$u.'&ordemPor=projeto_data_fim&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('T�rmino Previsto', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de t�rmino previsto.').($ordenarPor=='projeto_data_fim' ? imagem('icones/'.$seta[$ordemDir]) : '').'T�rmino Previsto'.dicaF().'</a></th>';
if ($exibir['provavel_inicio']) echo '<th style="white-space: nowrap">'.dica('In�cio', 'Data de in�cio baseada na data inicial d'.$config['genero_tarefa'].' primeir'.$config['genero_tarefa'].' '.$config['tarefa'].' do portf�lio.').'In�cio'.dicaF().'</th>';
if ($exibir['provavel_termino']) echo '<th style="white-space: nowrap">'.dica('T�rmino', 'Data de t�rmino baseada na data final d'.$config['genero_tarefa'].' �ltim'.$config['genero_tarefa'].' '.$config['tarefa'].' do portf�lio.').'T�rmino'.dicaF().'</th>';
if ($exibir['projeto_encerramento']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.'&u='.$u.'&ordemPor=projeto_encerramento&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Data de Encerramento', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de encerramento.').($ordenarPor=='projeto_encerramento' ? imagem('icones/'.$seta[$ordemDir]) : '').'Data Encerramento'.dicaF().'</a></th>';
if ($exibir['problema']) echo '<th style="white-space: nowrap">'.dica('Registros de Problemas', ucfirst($config['projetos']).' com registros de problemas.').'RP'.dicaF().'</th>';
if ($exibir['responsavel']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_responsavel_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.ucfirst($config['projetos']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_gerente'].'s '.$config['gerente'].'.').($ordenarPor==='projeto_responsavel_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['gerente']).dicaF().'</a></th>';
if ($exibir['supervisor']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_supervisor_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['supervisor']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_supervisor'].' '.$config['supervisor'].'.').($ordenarPor==='projeto_supervisor_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['supervisor']).dicaF().'</a></th>';
if ($exibir['autoridade']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_autoridade_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['autoridade']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_autoridade'].' '.$config['autoridade'].'.').($ordenarPor==='projeto_autoridade_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['autoridade']).dicaF().'</a></th>';
if ($exibir['cliente']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cliente_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['cliente']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_cliente'].' '.$config['cliente'].'.').($ordenarPor==='projeto_cliente_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['cliente']).dicaF().'</a></th>';
if ($exibir['custo']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_custo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Custo', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo custo d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor==='projeto_custo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Custo'.dicaF().'</a></th>';
if ($exibir['gasto']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_gasto&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Gasto', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo gasto d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor==='projeto_gasto' ? imagem('icones/'.$seta[$ordemDir]) : '').'Gasto'.dicaF().'</a></th>';
if ($exibir['recursos']) echo '<th style="white-space: nowrap">'.dica('Recursos Financeiro', 'Somat�rio dos recursos financeiros alocados n'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Recursos'.dicaF().'</th>';
if ($exibir['codigo']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_codigo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('C�digo', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos c�digos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_codigo' ? imagem('icones/'.$seta[$ordemDir]) : '').'C�digo'.dicaF().'</a></th>';
if ($exibir['ano']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_ano&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Ano', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos anos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_ano' ? imagem('icones/'.$seta[$ordemDir]) : '').'Ano'.dicaF().'</a></th>';
if ($exibir['setor']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_setor&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Setor', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos setores d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_setor' ? imagem('icones/'.$seta[$ordemDir]) : '').'Setor'.dicaF().'</a></th>';
if ($exibir['segmento']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_segmento&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Segmento', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos segmentos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_segmento' ? imagem('icones/'.$seta[$ordemDir]) : '').'Segmento'.dicaF().'</a></th>';
if ($exibir['intervencao']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_intervencao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Interven��o', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelas interven��es  n'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_intervencao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Interven��o'.dicaF().'</a></th>';
if ($exibir['tipo_intervencao']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_tipo_intervencao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Tipo de Interven��o', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos tipos de Interven��o d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_tipo_intervencao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Tipo de Interven��o'.dicaF().'</a></th>';

if ($exibir['projeto_programa_financeiro']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordemPor=projeto_programa_financeiro&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Programa', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos programas d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_programa_financeiro' ? imagem('icones/'.$seta[$ordemDir]) : '').'Programa'.dicaF().'</a></th>';
if ($exibir['projeto_convenio']) echo '<th style="white-space: nowrap">'.dica('Conv�nio', 'Conv�nios d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Conv�nio'.dicaF().'</th>';

if ($exibir['categoria']) {
	echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_tipo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Categoria', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelas categorias d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_tipo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Categoria'.dicaF().'</a></th>';
	if (!is_array($projeto_tipos)){
		$projeto_tipos=array();
		if(!$Aplic->profissional) $projeto_tipos[-1] = '';
		$projeto_tipos += getSisValor('TipoProjeto');
		}
	}
if ($exibir['url']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_url&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Link', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos links d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_url' ? imagem('icones/'.$seta[$ordemDir]) : '').'Link'.dicaF().'</a></th>';
if ($exibir['www']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_url_externa&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Endere�o Web', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos endere�os Web d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_url_externa' ? imagem('icones/'.$seta[$ordemDir]) : '').'Endere�o Web'.dicaF().'</a></th>';
if ($exibir['projeto_descricao']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_descricao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('O Que', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo O Que d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_descricao' ? imagem('icones/'.$seta[$ordemDir]) : '').'O Que'.dicaF().'</a></th>';
if ($exibir['projeto_objetivos']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_objetivos&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Por Que', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Por Que d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_objetivos' ? imagem('icones/'.$seta[$ordemDir]) : '').'Por Que'.dicaF().'</a></th>';
if ($exibir['projeto_observacao']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_observacao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Observa��es', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Observa��es d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_observacao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Observa��es'.dicaF().'</a></th>';
if ($exibir['projeto_como']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_como&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Como', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Como d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_como' ? imagem('icones/'.$seta[$ordemDir]) : '').'Como'.dicaF().'</a></th>';

if ($exibir['projeto_localizacao']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_localizacao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Onde', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Onde d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_localizacao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Onde'.dicaF().'</a></th>';
if ($exibir['endereco']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_endereco1&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Endere�o', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo endere�o d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_endereco1' ? imagem('icones/'.$seta[$ordemDir]) : '').'Endere�o'.dicaF().'</a></th>';
if ($exibir['projeto_cidade']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cidade&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Munic�pio', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo munic�pio d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_cidade' ? imagem('icones/'.$seta[$ordemDir]) : '').'Munic�pio'.dicaF().'</a></th>';



if ($exibir['projeto_beneficiario']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_beneficiario&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Benefici�rio', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Benefici�rio d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_beneficiario' ? imagem('icones/'.$seta[$ordemDir]) : '').'Benefici�rio'.dicaF().'</a></th>';
if ($exibir['projeto_justificativa']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_justificativa&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Justificativa', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Justificativa d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_justificativa' ? imagem('icones/'.$seta[$ordemDir]) : '').'Justificativa'.dicaF().'</a></th>';
if ($exibir['projeto_objetivo']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_objetivo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Objetivo', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Objetivo d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_objetivo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Objetivo'.dicaF().'</a></th>';
if ($exibir['projeto_objetivo_especifico']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_objetivo_especifico&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Objetivos Espec�ficos', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Objetivos Espec�ficos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_objetivo_especifico' ? imagem('icones/'.$seta[$ordemDir]) : '').'Objetivos Espec�ficos'.dicaF().'</a></th>';
if ($exibir['projeto_escopo']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_escopo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Declara��o de Escopo', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Declara��o de Escopo d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_escopo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Escopo'.dicaF().'</a></th>';
if ($exibir['projeto_nao_escopo']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_nao_escopo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('N�o Escopo', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo N�o Escopo d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_nao_escopo' ? imagem('icones/'.$seta[$ordemDir]) : '').'N�o Escopo'.dicaF().'</a></th>';
if ($exibir['projeto_premissas']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_premissas&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Premissas', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Premissas d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_premissas' ? imagem('icones/'.$seta[$ordemDir]) : '').'Premissas'.dicaF().'</a></th>';
if ($exibir['projeto_restricoes']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_restricoes&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Restri��es', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Restri��es d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_restricoes' ? imagem('icones/'.$seta[$ordemDir]) : '').'Restri��es'.dicaF().'</a></th>';
if ($exibir['projeto_orcamento']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_orcamento&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Custos Estimados', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Custos Estimados d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_orcamento' ? imagem('icones/'.$seta[$ordemDir]) : '').'Custos Estimados'.dicaF().'</a></th>';
if ($exibir['projeto_beneficio']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_beneficio&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Benef�cios', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Benef�cios d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_beneficio' ? imagem('icones/'.$seta[$ordemDir]) : '').'Benef�cios'.dicaF().'</a></th>';
if ($exibir['projeto_produto']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_produto&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Produtos', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Produtos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_produto' ? imagem('icones/'.$seta[$ordemDir]) : '').'Produtos'.dicaF().'</a></th>';
if ($exibir['projeto_requisito']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_requisito&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Requisitos', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo campo Requisitos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_requisito' ? imagem('icones/'.$seta[$ordemDir]) : '').'Requisitos'.dicaF().'</a></th>';
if ($exibir['integrantes'] && !$selecao) echo '<th style="white-space: nowrap">'.dica('Integrantes', 'Lista de integrantes d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Integrantes'.dicaF().'</th>';
if ($exibir['partes'] && !$selecao) echo '<th style="white-space: nowrap">'.dica('Partes Interessadas', 'Partes interessadas d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Partes Interessadas'.dicaF().'</th>';
if ($exibir['status']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_status&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Visualizar os Status d'.$config['genero_projeto'].'s '.$config['projetos'].'.').($ordenarPor=='projeto_status' ? imagem('icones/'.$seta[$ordemDir]) : '').'Status'.dicaF().'</a></th>';
if ($exibir['projeto_fase']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_fase&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Fases d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Visualizar as fases d'.$config['genero_projeto'].'s '.$config['projetos'].'.').($ordenarPor=='projeto_fase' ? imagem('icones/'.$seta[$ordemDir]) : '').'Fase'.dicaF().'</a></th>';

if ($Aplic->profissional && !$selecao){
	$obj = new CProjeto();
	if ($exibir['relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionad'.$config['genero_projeto'], '�reas que '.($config['genero_projeto']=='o' ? 'estes' : 'estas').' '.$config['projetos'].' est�o relacionad'.$config['genero_projeto'].'s.').'Relacionad'.$config['genero_projeto'].dicaF().'</th>';
	if ($exibir['fisico_previsto']) echo '<th style="white-space: nowrap">'.dica('F�sico Previsto', 'A execu��o f�sica prevista para a data atual.').'F�sico Previsto'.dicaF().'</th>';
	if ($exibir['fisico_velocidade']) echo '<th style="white-space: nowrap">'.dica('Vel.F�sico', 'Velocidade do cronograma f�sico.').'Vel.F�sico'.dicaF().'</th>';
	if ($exibir['emprego_obra'])  echo '<th style="white-space: nowrap">'.dica('Emprego Durante', 'Empregos gerados durante a execu��o.').'Emprego Durante'.dicaF().'</th>';
	if ($exibir['emprego_direto']) echo '<th style="white-space: nowrap">'.dica('Emprego Ap�s', 'Empregos diretos ap�s a conclus�o.').'Emprego Ap�s'.dicaF().'</th>';
	if ($exibir['emprego_indireto']) echo '<th style="white-space: nowrap">'.dica('Emprego Indireto', 'Empregos indiretos ap�s a conclus�o.').'Emprego Indireto'.dicaF().'</th>';
	if ($exibir['gasto_registro']) echo '<th style="white-space: nowrap">'.dica('Total Extra', 'Total de gastos extras.').'Total Extra'.dicaF().'</th>';
	if ($exibir['financeiro_previsto']) echo '<th style="white-space: nowrap">'.dica('Financeiro Atual', 'Cronograma financeiro previsto at� a data atual.').'Financeiro Atual'.dicaF().'</th>';
	if ($exibir['financeiro_velocidade']) echo '<th style="white-space: nowrap">'.dica('Vel. Financeiro', 'Velocidade do cronograma financeiro.').'Vel. Financeiro'.dicaF().'</th>';
	if ($exibir['recurso_previsto']) echo '<th style="white-space: nowrap">'.dica('Custo Recursos Atual', 'Custo de recursos alocados at� a data atual.').'Custo Recursos Atual'.dicaF().'</th>';
	if ($exibir['recurso_previsto_total']) echo '<th style="white-space: nowrap">'.dica('Custo Recursos Final', 'Custo de recursos alocados at� o final.').'Custo Recursos Final'.dicaF().'</th>';
	if ($exibir['mo_previsto']) echo '<th style="white-space: nowrap">'.dica('Custo M.O. Atual', 'Custo de m�o de obra prevista at� a data atual.').'Custo M.O. Atual'.dicaF().'</th>';
	if ($exibir['mo_previsto_total']) echo '<th style="white-space: nowrap">'.dica('Custo M.O. Final', 'Custo de m�o de obra prevista at� o final.').'Custo M.O. Final'.dicaF().'</th>';
	if ($exibir['total_estimado']) echo '<th style="white-space: nowrap">'.dica('Custo Total Atual', 'Custo de M.O., planilhas de custo e recursos at� a data atual.').'Custo Total Atual'.dicaF().'</th>';
	if ($exibir['total_estimado_total']) echo '<th style="white-space: nowrap">'.dica('Custo Total Final', 'Custo de M.O., planilhas de custo e recursos at� o final.').'Custo Total Final'.dicaF().'</th>';
	
	if (isset($exibir['ne_qnt']) && $exibir['ne_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Notas de Empenho', 'Quantidade de notas de empenho relacionadas.').'Qnt NE'.dicaF().'</th>';
	if (isset($exibir['ne_valor']) && $exibir['ne_valor']) echo '<th style="white-space: nowrap">'.dica('Valor das Notas de Empenho', 'Valor total das notas de empenho relacionadas.').'Valor NE'.dicaF().'</th>';
	if (isset($exibir['ne_estorno_qnt']) && $exibir['ne_estorno_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Estornos de Notas de Empenho', 'Quantidade de estornos notas de empenho relacionadas.').'Qnt Est. NE'.dicaF().'</th>';
	if (isset($exibir['ne_estorno_valor']) && $exibir['ne_estorno_valor']) echo '<th style="white-space: nowrap">'.dica('Valor dos Estornos das Notas de Empenho', 'Valor total dos estornos das notas de empenho relacionadas.').'Valor Est. NE'.dicaF().'</th>';

	if (isset($exibir['ns_qnt']) && $exibir['ns_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Notas de Liquida��o', 'Quantidade de notas de liquida��o relacionadas.').'Qnt NL'.dicaF().'</th>';
	if (isset($exibir['ns_valor']) && $exibir['ns_valor']) echo '<th style="white-space: nowrap">'.dica('Valor das Notas de Liquida��o', 'Valor total das notas de liquida��o relacionadas.').'Valor NL'.dicaF().'</th>';
	if (isset($exibir['ns_estorno_qnt']) && $exibir['ns_estorno_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Estornos de Notas de Liquida��o', 'Quantidade de estornos notas de liquida��o relacionadas.').'Qnt Est. NL'.dicaF().'</th>';
	if (isset($exibir['ns_estorno_valor']) && $exibir['ns_estorno_valor']) echo '<th style="white-space: nowrap">'.dica('Valor dos Estornos das Notas de Liquida��o', 'Valor total dos estornos das notas de liquida��o relacionadas.').'Valor Est. NL'.dicaF().'</th>';

	if (isset($exibir['ob_qnt']) && $exibir['ob_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Notas de Ordem Banc�ria', 'Quantidade de notas de ordem banc�ria relacionadas.').'Qnt OB'.dicaF().'</th>';
	if (isset($exibir['ob_valor']) && $exibir['ob_valor']) echo '<th style="white-space: nowrap">'.dica('Valor das Notas de Ordem Banc�ria', 'Valor total das notas de ordem banc�ria relacionadas.').'Valor OB'.dicaF().'</th>';
	if (isset($exibir['ob_estorno_qnt']) && $exibir['ob_estorno_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Estornos de Notas de Ordem Banc�ria', 'Quantidade de estornos notas de ordem banc�ria relacionadas.').'Qnt Est. OB'.dicaF().'</th>';
	if (isset($exibir['ob_estorno_valor']) && $exibir['ob_estorno_valor']) echo '<th style="white-space: nowrap">'.dica('Valor dos Estornos das Notas de Ordem Banc�ria', 'Valor total dos estornos das notas de ordem banc�ria relacionadas.').'Valor Est. OB'.dicaF().'</th>';
	
	if (isset($exibir['gcv_qnt']) && $exibir['gcv_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Guias de Cr�dito de Verba', 'Quantidade de guias de cr�dito de verba relacionadas.').'Qnt GCV'.dicaF().'</th>';
	if (isset($exibir['gcv_valor']) && $exibir['gcv_valor']) echo '<th style="white-space: nowrap">'.dica('Valor das Guias de Cr�dito de Verba', 'Valor total das guias de cr�dito de verba relacionadas.').'Valor GCV'.dicaF().'</th>';
	if (isset($exibir['gcv_estorno_qnt']) && $exibir['gcv_estorno_qnt']) echo '<th style="white-space: nowrap">'.dica('Quantidade de Estornos de Guias de Cr�dito de Verba', 'Quantidade de estornos guias de cr�dito de verba relacionadas.').'Qnt Est. GCV'.dicaF().'</th>';
	if (isset($exibir['gcv_estorno_valor']) && $exibir['gcv_estorno_valor']) echo '<th style="white-space: nowrap">'.dica('Valor dos Estornos das Guias de Cr�dito de Verba', 'Valor total dos estornos das guias de cr�dito de verba relacionadas.').'Valor Est. GCV'.dicaF().'</th>';

	
	
	
  $exibir_customizado = array();

  $sql->adTabela('campo_formulario');
  $sql->adCampo('campo_formulario_campo, campo_formulario_customizado');
  $sql->adOnde('campo_formulario_tipo = \'projetos_ex\'');

  $sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
  $sql->adOnde('campo_formulario_customizado IS NOT NULL AND campo_formulario_customizado != 0');
  $sql->adOnde('campo_formulario_ativo != 0');
  $exibir_customizado = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_customizado');
  $sql->limpar();

  $sql->adTabela('campo_formulario');
  $sql->adCampo('campo_formulario_campo, campo_formulario_customizado');
  $sql->adOnde('campo_formulario_tipo = \'projetos_ex\'');
  $sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
  $sql->adOnde('campo_formulario_customizado IS NOT NULL AND campo_formulario_customizado != 0');
  $sql->adOnde('campo_formulario_ativo != 0');
  $exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_customizado');
  $sql->limpar();

  $diff = array_diff_key($exibir_customizado, $exibir2);
  if($diff) $exibir_customizado = array_merge($exibir2, $diff);
  else $exibir_customizado = $exibir2;

  foreach($exibir_customizado as $cmp){
    $campo_id = (int) $cmp;
    if(isset($campos_extras[$campo_id])){
      $campo = $campos_extras[$campo_id];
      $desc = $campo['campo_customizado_descricao'];
      echo '<th style="white-space: nowrap">'.dica($desc, 'Campo customizado - '.$desc).$desc.dicaF().'</th>';
      }
    }
	}


if ($exibir['projeto_numero_tarefas']) echo '<th style="white-space: nowrap" width=32>'.dica(ucfirst($config['tarefas']), 'Quantidade de  '.$config['tarefas'].'.').'T'.dicaF().dica('Minhas '.ucfirst($config['tarefa']), 'Quantidade de  '.$config['tarefas'].' designadas para mim.').' M'.dicaF().'</th>';

if(!$from_lista) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordemPor=projeto_ativo&ordemDir='.($ordemDir ? '0' : '1').'\');">'.($ordenarPor==='projeto_ativo' ? imagem('icones/'.$seta[$ordemDir]) : '').dica('Ativo', 'Neste campo consta se '.$config['genero_projeto'].' '.$config['projeto'].' est� ativ'.$config['genero_projeto'].'.').'At.'.dicaF().'</a></th>';

if (!$dialogo) echo '<th style="white-space: nowrap" width=16>'.dica('Selecionar '.ucfirst($config['projetos']), 'Utilize as caixas abaixo para selecionar '.$config['genero_projeto'].'s '.$config['projetos'].' em que se deseja alterar o Status dos mesmos.').'S'.dicaF().'</th>';

echo '</tr>';


$nenhum = true;
foreach ($projetos as $linha){
	if (permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])){
		$nenhum = false;

        $codigoPermissaoProjeto = permiteEditar( $linha[ 'projeto_acesso' ], $linha[ 'projeto_id' ] );
        $editar = ( $podeEditar && $codigoPermissaoProjeto );
        $podeEditarTudo = permiteEditarTudoProjeto($podeEditar, $linha[ 'projeto_acesso' ], $codigoPermissaoProjeto);

		if ($edicao && !$editar && !$Aplic->usuario_ver_tudo) continue;
		
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		
		$estilo = ((substr($linha['projeto_fim_atualizado'], 0, 10) > substr($linha['projeto_data_fim'], 0, 10)) && !empty($linha['projeto_data_fim'])) ? 'style="color:red; font-weight:bold"' : '';
		$estilo2 = ((substr($linha['projeto_inicio_atualizado'], 0, 10) > substr($linha['projeto_data_inicio'], 0, 10)) && !empty($linha['projeto_data_inicio'])) ? 'style="color:red; font-weight:bold"' : '';
		echo '<tr id="projeto_'.$linha['projeto_id'].'" onmouseover="iluminar_tds(this, true, '.$linha['projeto_id'].')" onmouseout="iluminar_tds(this, false, '.$linha['projeto_id'].')" onclick="selecionar_projeto('.$linha['projeto_id'].')">';

		if (!$dialogo){
		    echo '<td style="white-space: nowrap" width=16>';
		    echo '<div style="display: inline-block; width: 16px; padding: 0 2px">'.($podeEditarTudo ? dica('Editar detalhes d'.$config['genero_projeto']. ' ' .ucfirst($config['projeto']), 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.$config['genero_projeto'].'s '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</div>';
		    if($Aplic->profissional) echo '<div style="display: inline-block; width: 16px; padding: 0 2px">'.($podeEditarTudo ? dica('Editar com gantt interativo', 'Clique neste �cone '.imagem('icones/gantt.png').' para editar '.$config['genero_projeto'].' '.$config['projeto'].' utilizando o gantt interativo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=wbs_completo&projeto_id='.$linha['projeto_id'].'&baseline_id=0\');">'.imagem('icones/gantt.png').'</a>'.dicaF() : '&nbsp;').'</div>';
        echo '</td>';
        }

		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['projeto_id'].'" value="'.$linha['projeto_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['projeto_id'].'" value="'.$linha['projeto_id'].'" '.(isset($selecionado[$linha['projeto_id']]) ? 'checked="checked"' : '').' />';


		if ($exibir['cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">&nbsp;&nbsp;</font></td>';
		
		
		if ($selecao) echo '<td id="projeto_nome_'.$linha['projeto_id'].'">'.$linha['projeto_nome'].'</td>';
		else {
			echo '<td>';
			$icone='';
			if ($projeto_expandido!=$linha['projeto_id']){
				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_superior='.$linha['projeto_id']);
				$sql->adOnde('projeto_id!='.$linha['projeto_id']);
				$subordinados=$sql->Resultado();
				$sql->limpar();
				$icone=($subordinados > 0 ? ($projeto_expandido ? imagem('icones/subnivel.gif') : '').'<a href="javascript:void(0);" onclick="env.projeto_expandido.value='.$linha['projeto_id'].'; env.submit();">'.imagem('icones/expandir.gif', 'Ver Subordinados', 'Clique neste �cone '.imagem('icones/expandir.gif').' para expandir os projetos subordinados a este').'</a>' : ( $projeto_expandido ? imagem('icones/subnivel.gif') : ''));
				}
			else{
				$sql->adTabela('projetos');
				$sql->adCampo('projeto_superior');
				$sql->adOnde('projeto_id='.$linha['projeto_id'].' AND projeto_superior!=projeto_id');
				$superior=$sql->Resultado();
				$sql->limpar();
				$icone='<a href="javascript:void(0);" onclick="env.projeto_expandido.value='.($superior ? $superior : 0).'; env.submit();">'.imagem('icones/colapsar.gif', 'Colapsar Subordinados', 'Clique neste �cone '.imagem('icones/colapsar.gif').' para colapsar os projetos subordinados a este').'</a>';
				}
			echo $icone.link_projeto($linha["projeto_id"],'','','','','',true);
			echo '</td>';
			}
			
		if ($exibir['fisico']) echo '<td width="45" align="right">'.number_format($linha['projeto_percentagem'], 2, ',', '.').'</td>';

		if ($Aplic->profissional && $exibir['projeto_aprovado']) echo '<td style="width: 30px; text-align: center">'.($linha['projeto_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'N�o' : 'N/A')).'</td>';
		if ($filtro_prioridade) echo '<td align="right">'.($linha['priorizacao']).'</td>';

		if ($exibir['prioridade']) echo '<td align="center">'.prioridade($linha['projeto_prioridade'], true).'</td>';
		if ($exibir['cia']) echo '<td>'.link_cia($linha['projeto_cia']).'</td>';

		if (isset($exibir['cias']) && $exibir['cias'] && !$selecao){
			$sql->adTabela('projeto_cia');
			$sql->adCampo('projeto_cia_cia');
			$sql->adOnde('projeto_cia_projeto = '.(int)$linha['projeto_id']);
			$cias = $sql->carregarColuna();
			$sql->limpar();
			$saida_cias='';
			if (isset($cias) && is_array($cias) && count($cias)) {
				$plural=(count($cias)>1 ? 's' : '');
				$saida_cias.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_cias.= '<tr><td style="border:0px;">'.link_cia($cias[0]);
				$qnt_cias=count($cias);
				if ($qnt_cias > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_cias; $j < $i_cmp; $j++) $lista.=link_cia($cias[$j]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_cias_'.$linha['projeto_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		if (isset($exibir['dept']) && $exibir['dept']) echo '<td>'.link_dept($linha['projeto_dept']).'</td>';	
		if ($exibir['depts'] && !$selecao){
			$sql->adTabela('projeto_depts');
			$sql->adCampo('departamento_id');
			$sql->adOnde('projeto_id = '.(int)$linha['projeto_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
			$saida_depts='';
			if (isset($depts) && is_array($depts) && count($depts)) {
				$plural=(count($depts)>1 ? 's' : '');
				$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_depts.= '<tr><td style="border:0px;">'.link_dept($depts[0]);
				$qnt_depts=count($depts);
				if ($qnt_depts > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_depts; $j < $i_cmp; $j++) $lista.=link_dept($depts[$j]).'<br>';
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_depts_'.$linha['projeto_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
		if ($exibir['inicio']) echo '<td width="80px" style="white-space: nowrap" align="center">'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '&nbsp;').'</td>';
		if ($exibir['termino']) echo '<td width="80px" style="white-space: nowrap" align="center">'.($data_fim ? $data_fim->format('%d/%m/%Y') : '&nbsp;').'</td>';
		if ($exibir['provavel_inicio']) echo '<td width="80px" style="white-space: nowrap" align="center">'.($linha['projeto_inicio_atualizado'] ? dica('In�cio Calculado', 'Clique para visualizar quais '.$config['tarefas'].' est�o alterando a data de in�cio prevista.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$linha['critica_inicio_tarefa'].'\');"><span '.$estilo2.'>'.retorna_data($linha['projeto_inicio_atualizado'], false).'</span></a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['provavel_termino']) echo '<td width="80px" style="white-space: nowrap" align="center">'.($linha['projeto_fim_atualizado'] ? dica('T�rmino Calculado', 'Clique para visualizar quais '.$config['tarefas'].' est�o alterando a data de t�rmino prevista.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$linha['critica_tarefa'].'\');"><span '.$estilo.'>'.retorna_data($linha['projeto_fim_atualizado'], false).'</span></a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['projeto_encerramento']) echo '<td width="80px" style="white-space: nowrap" align="center">'.retorna_data($linha['projeto_encerramento'], false).'</td>';
		if ($exibir['problema']) echo '<td align="center">'.($linha['log_corrigir'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=ver&tab=2&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/aviso.gif', 'Problema', 'Foi registrado ao menos um problema em uma d'.$config['genero_tarefa'].'s '.$config['tarefas'].'. Clique para ver os registros.').'</a>' : '&nbsp;').'</td>';
		if ($exibir['responsavel']) echo '<td>'.link_usuario($linha['projeto_responsavel'],'','','esquerda').'</td>';
		if ($exibir['supervisor']) echo '<td>'.link_usuario($linha['projeto_supervisor'],'','','esquerda').'</td>';
		if ($exibir['autoridade']) echo '<td>'.link_usuario($linha['projeto_autoridade'],'','','esquerda').'</td>';
		if ($exibir['cliente']) echo '<td>'.link_usuario($linha['projeto_cliente'],'','','esquerda').'</td>';

        $dentro = false;
		if ($exibir['custo'] || $exibir['gasto']){
			if ($config['popup_detalhado']){
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Custo</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['projeto_custo'], 2, ',', '.').'</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Gasto</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['projeto_gasto'], 2, ',', '.').'</td></tr>';
				if ((int)$linha['projeto_percentagem']!=100 && (float)$linha['projeto_percentagem']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Estimativa Final</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['projeto_gasto']*100/$linha['projeto_percentagem'], 2, ',', '.').'</td></tr>';
				$dentro .= '</table>';
				}
			}
		if ($exibir['custo'])	echo '<td align="right" style="white-space: nowrap">'.($config['popup_detalhado'] ? dica('Valores', $dentro).number_format($linha['projeto_custo'], 2, ',', '.').dicaF() : number_format($linha['projeto_custo'], 2, ',', '.')).'</td>';
		if ($exibir['gasto'])	echo '<td align="right" style="white-space: nowrap">'.($config['popup_detalhado'] ? dica('Valores', $dentro).number_format($linha['projeto_gasto'], 2, ',', '.').dicaF() : number_format($linha['projeto_gasto'], 2, ',', '.')).'</td>';
		if ($exibir['recursos']) echo '<td align="right" style="white-space: nowrap">'.($config['popup_detalhado'] ? dica('Recursos Financeiros Alocados', $dentro).number_format($linha['total_recursos'], 2, ',', '.').dicaF() : number_format($linha['total_recursos'], 2, ',', '.')).'</td>';
		if ($exibir['codigo']) echo '<td>'.($linha['projeto_codigo'] ? $linha['projeto_codigo'] : '&nbsp;').'</td>';
		if ($exibir['ano']) echo '<td>'.($linha['projeto_ano'] ? $linha['projeto_ano'] : '&nbsp;').'</td>';
		if ($exibir['setor']) echo '<td>'.($linha['projeto_setor'] ? getSisValorCampo('Setor',$linha['projeto_setor']) : '&nbsp;').'</td>';
		if ($exibir['segmento']) echo '<td>'.($linha['projeto_segmento'] ? getSisValorCampo('Segmento',$linha['projeto_segmento']) : '&nbsp;').'</td>';
		if ($exibir['intervencao']) echo '<td>'.($linha['projeto_intervencao'] ? getSisValorCampo('Intervencao',$linha['projeto_intervencao']) : '&nbsp;').'</td>';
		if ($exibir['tipo_intervencao']) echo '<td>'.($linha['projeto_tipo_intervencao'] ? getSisValorCampo('TipoIntervencao',$linha['projeto_tipo_intervencao']) : '&nbsp;').'</td>';
		
		if ($exibir['projeto_programa_financeiro']) echo '<td>'.($linha['projeto_programa_financeiro'] ? $linha['projeto_programa_financeiro'] : '&nbsp;').'</td>';
		if ($exibir['projeto_convenio']) {
			$sql->adTabela('projeto_convenio');
			$sql->esqUnir('sisvalores', 'sisvalores', 'sisvalor_valor_id=projeto_convenio_convenio');
			$sql->adOnde('projeto_convenio_projeto = '.(int)$linha['projeto_id']);
			$sql->adOnde('sisvalor_titulo = \'projeto_convenio\'');
			$sql->adCampo('sisvalor_valor');
			$sql->adOrdem('projeto_convenio_ordem');
			$convenios=$sql->carregarColuna();
			$sql->limpar();
			echo '<td>'.(count($convenios) ? implode('<br>', $convenios) : '').'</td>';
			}
		
		
		
		
		
		
		
		if ($exibir['categoria']) echo '<td>'.(isset($projeto_tipos[$linha['projeto_tipo']]) ? $projeto_tipos[$linha['projeto_tipo']] : '&nbsp;').'</td>';
		if ($exibir['url']) echo '<td>'.($linha['projeto_url'] ? $linha['projeto_url'] : '&nbsp;').'</td>';
		if ($exibir['www']) echo '<td>'.($linha['projeto_url_externa'] ? $linha['projeto_url_externa'] : '&nbsp;').'</td>';

		if ($exibir['projeto_descricao']) echo '<td>'.($linha['projeto_descricao'] ? $linha['projeto_descricao'] : '&nbsp;').'</td>';
		if ($exibir['projeto_objetivos']) echo '<td>'.($linha['projeto_objetivos'] ? $linha['projeto_objetivos'] : '&nbsp;').'</td>';
		if ($exibir['projeto_observacao']) echo '<td>'.($linha['projeto_observacao'] ? $linha['projeto_observacao'] : '&nbsp;').'</td>';
		if ($exibir['projeto_como']) echo '<td>'.($linha['projeto_como'] ? $linha['projeto_como'] : '&nbsp;').'</td>';
		if ($exibir['projeto_localizacao']) echo '<td>'.($linha['projeto_localizacao'] ? $linha['projeto_localizacao'] : '&nbsp;').'</td>';
		if ($exibir['endereco']) echo '<td>'.$linha['projeto_endereco1'].(($linha['projeto_endereco2']) ? '<br />'.$linha['projeto_endereco2'] : '') .($linha['projeto_cidade'] || $linha['projeto_estado'] ? '<br>' : '').$linha['municipio_nome'].($linha['projeto_estado'] ? ' - ' : '').$linha['projeto_estado'].(($linha['projeto_cep']) ? '<br />'.$linha['projeto_cep'] : '').'</td>';
		
		if ($exibir['projeto_cidade']) echo '<td align=center>'.($linha['municipio_nome'] ? $linha['municipio_nome'] : '&nbsp;').'</td>';
		
		
		if ($exibir['projeto_beneficiario']) echo '<td>'.($linha['projeto_beneficiario'] ? $linha['projeto_beneficiario'] : '&nbsp;').'</td>';
		if ($exibir['projeto_justificativa']) echo '<td>'.($linha['projeto_justificativa'] ? $linha['projeto_justificativa'] : '&nbsp;').'</td>';
		if ($exibir['projeto_objetivo']) echo '<td>'.($linha['projeto_objetivo'] ? $linha['projeto_objetivo'] : '&nbsp;').'</td>';
		if ($exibir['projeto_objetivo_especifico']) echo '<td>'.($linha['projeto_objetivo_especifico'] ? $linha['projeto_objetivo_especifico'] : '&nbsp;').'</td>';
		if ($exibir['projeto_escopo']) echo '<td>'.($linha['projeto_escopo'] ? $linha['projeto_escopo'] : '&nbsp;').'</td>';
		if ($exibir['projeto_nao_escopo']) echo '<td>'.($linha['projeto_nao_escopo'] ? $linha['projeto_nao_escopo'] : '&nbsp;').'</td>';
		if ($exibir['projeto_premissas']) echo '<td>'.($linha['projeto_premissas'] ? $linha['projeto_premissas'] : '&nbsp;').'</td>';
		if ($exibir['projeto_restricoes']) echo '<td>'.($linha['projeto_restricoes'] ? $linha['projeto_restricoes'] : '&nbsp;').'</td>';
		if ($exibir['projeto_orcamento']) echo '<td>'.($linha['projeto_orcamento'] ? $linha['projeto_orcamento'] : '&nbsp;').'</td>';
		if ($exibir['projeto_beneficio']) echo '<td>'.($linha['projeto_beneficio'] ? $linha['projeto_beneficio'] : '&nbsp;').'</td>';
		if ($exibir['projeto_produto']) echo '<td>'.($linha['projeto_produto'] ? $linha['projeto_produto'] : '&nbsp;').'</td>';
		if ($exibir['projeto_requisito']) echo '<td>'.($linha['projeto_requisito'] ? $linha['projeto_requisito'] : '&nbsp;').'</td>';
		


		if ($exibir['integrantes'] && !$selecao){
			$sql->adTabela('projeto_integrantes');
			$sql->adCampo('contato_id');
			$sql->adOnde('projeto_id = '.(int)$linha['projeto_id']);
			$sql->adOrdem('ordem');
			$integrantes = $sql->carregarColuna();
			$sql->limpar();
			$saida_integrantes='';
			if (isset($integrantes) && is_array($integrantes) && count($integrantes)) {
				$plural=(count($integrantes)>1 ? 's' : '');
				$saida_integrantes.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_integrantes.= '<tr><td style="border:0px;">'.link_contato($integrantes[0]);
				$qnt_integrantes=count($integrantes);
				if ($qnt_integrantes > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_integrantes; $j < $i_cmp; $j++) $lista.=link_contato($integrantes[$j]).'<br>';
					$saida_integrantes.= dica('Outros Integrantes', 'Clique para visualizar os demais integrantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_integrantes_'.$linha['projeto_id'].'\');">(+'.($qnt_integrantes - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_integrantes_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_integrantes.= '</td></tr></table>';
				$plural=(count($integrantes)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_integrantes ? $saida_integrantes : '&nbsp;').'</td>';
			}



		if ($exibir['partes'] && !$selecao){
			$sql->adTabela('projeto_contatos');
			$sql->adCampo('contato_id');
			$sql->adOnde('projeto_id = '.(int)$linha['projeto_id']);
			$sql->adOrdem('ordem');
			$partes = $sql->carregarColuna();
			$sql->limpar();
			$saida_partes='';
			if (isset($partes) && is_array($partes) && count($partes)) {
				$plural=(count($partes)>1 ? 's' : '');
				$saida_partes.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_partes.= '<tr><td style="border:0px;">'.link_contato($partes[0]);
				$qnt_partes=count($partes);
				if ($qnt_partes > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_partes; $j < $i_cmp; $j++) $lista.=link_contato($partes[$j]).'<br>';
					$saida_partes.= dica('Outras Partes Interessadas', 'Clique para visualizar as demais partes interessadas.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_partes_'.$linha['projeto_id'].'\');">(+'.($qnt_partes - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_partes_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_partes.= '</td></tr></table>';
				$plural=(count($partes)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_partes ? $saida_partes : '&nbsp;').'</td>';
			}
		if ($exibir['status']) echo '<td id="ignore_td_'.$linha['projeto_id'].'" style="white-space: nowrap; background: '.$linha['projeto_situacao'].'" align="center">'.(isset($vetorStatus[$linha['projeto_status']]) ? $vetorStatus[$linha['projeto_status']] : '&nbsp;').'</td>';
		if ($exibir['projeto_fase']) echo '<td id="ignore_td_'.$linha['projeto_id'].'" style="white-space: nowrap;" align="center">'.(isset($projetoFase[$linha['projeto_fase']]) ? $projetoFase[$linha['projeto_fase']] : '&nbsp;').'</td>';

		//campos da vers�o Pro

		if ($Aplic->profissional && !$selecao){

			$obj->projeto_id=(int)$linha['projeto_id'];
			if (isset($exibir['relacionado']) && $exibir['relacionado'])  {
				$sql->adTabela('projeto_gestao');
				$sql->adCampo('projeto_gestao.*');
				$sql->adOnde('projeto_gestao_projeto ='.(int)$linha['projeto_id']);
				$sql->adOrdem('projeto_gestao_ordem');
			  $gestao = $sql->Lista();
			  $sql->limpar();
			  $qnt_gestao=0;
				echo '<td align="left">';
				foreach($gestao as $gestao_data){
					if ($gestao_data['projeto_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_gestao_tarefa']);
					
					elseif ($gestao_data['projeto_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_gestao_semelhante']);
					
					elseif ($gestao_data['projeto_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']);
					elseif ($gestao_data['projeto_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']);
					elseif ($gestao_data['projeto_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']);
					elseif ($gestao_data['projeto_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']);
					elseif ($gestao_data['projeto_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']);
					elseif ($gestao_data['projeto_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']);
					elseif ($gestao_data['projeto_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']);
					elseif ($gestao_data['projeto_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']);
					elseif ($gestao_data['projeto_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']);
					elseif ($gestao_data['projeto_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']);
					elseif ($gestao_data['projeto_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']);
					elseif ($gestao_data['projeto_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']);
					elseif ($gestao_data['projeto_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_gestao_calendario']);
					elseif ($gestao_data['projeto_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']);
					elseif ($gestao_data['projeto_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']);
					elseif ($gestao_data['projeto_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_gestao_mswot']);
					elseif ($gestao_data['projeto_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']);
					elseif ($gestao_data['projeto_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']);
					elseif ($gestao_data['projeto_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']);
					elseif ($gestao_data['projeto_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']);
					elseif ($gestao_data['projeto_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['projeto_gestao_problema']);
					elseif ($gestao_data['projeto_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']);	
					elseif ($gestao_data['projeto_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']);
					elseif ($gestao_data['projeto_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']);
					elseif ($gestao_data['projeto_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']);
					elseif ($gestao_data['projeto_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']);
					elseif ($gestao_data['projeto_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']);
					elseif ($gestao_data['projeto_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']);
					elseif ($gestao_data['projeto_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_gestao_brainstorm']);
					elseif ($gestao_data['projeto_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_gestao_gut']);
					elseif ($gestao_data['projeto_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_gestao_causa_efeito']);
					elseif ($gestao_data['projeto_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']);
					elseif ($gestao_data['projeto_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']);
					elseif ($gestao_data['projeto_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']);
					elseif ($gestao_data['projeto_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_gestao_agenda']);
					elseif ($gestao_data['projeto_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']);
					elseif ($gestao_data['projeto_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']);
					elseif ($gestao_data['projeto_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['projeto_gestao_template']);
					elseif ($gestao_data['projeto_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['projeto_gestao_painel']);
					elseif ($gestao_data['projeto_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']);
					elseif ($gestao_data['projeto_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']);		
					elseif ($gestao_data['projeto_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']);	
					elseif ($gestao_data['projeto_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']);	
					elseif ($gestao_data['projeto_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_gestao_acao_item']);	
					elseif ($gestao_data['projeto_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_gestao_beneficio']);	
					elseif ($gestao_data['projeto_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_gestao_painel_slideshow']);	
					elseif ($gestao_data['projeto_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['projeto_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_gestao_projeto_abertura']);	
					elseif ($gestao_data['projeto_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_gestao_plano_gestao']);	
					elseif ($gestao_data['projeto_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_gestao_ssti']);	
					elseif ($gestao_data['projeto_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_gestao_laudo']);	
					elseif ($gestao_data['projeto_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_gestao_trelo']);	
					elseif ($gestao_data['projeto_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_gestao_trelo_cartao']);	
					elseif ($gestao_data['projeto_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_gestao_pdcl']);	
					elseif ($gestao_data['projeto_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_gestao_pdcl_item']);	
					elseif ($gestao_data['projeto_gestao_os']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/os_p.png').link_os($gestao_data['projeto_gestao_os']);	

					}
				echo '</td>';
				}


			if ($exibir['fisico_previsto'])  {
				echo '<td align="right">'.number_format($obj->fisico_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['fisico_velocidade'])  {
				echo '<td align="right">'.number_format($obj->fisico_velocidade(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['emprego_obra']) {
				echo '<td align="right">'.(int)$obj->getEmpregosObra().'</td>';
				}

			if ($exibir['emprego_direto'])  {
				echo '<td align="right">'.(int)$obj->getEmpregosDiretos().'</td>';
				}

			if ($exibir['emprego_indireto'])  {
				echo '<td align="right">'.(int)$obj->getEmpregosIndiretos().'</td>';
				}


			if ($exibir['gasto_registro'])  {
				echo '<td align="right">'.number_format($obj->gasto_registro(true), 2, ',', '.').'</td>';
				}

			if ($exibir['financeiro_previsto'])  {
				echo '<td align="right">'.number_format($obj->custo_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['financeiro_velocidade'])  {
				echo '<td align="right">'.number_format($obj->financeiro_velocidade(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['recurso_previsto'])  {
				echo '<td align="right">'.number_format($obj->recurso_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['recurso_previsto_total'])  {
				echo '<td align="right">'.number_format($obj->recurso_previsto(date('Y-m-d H:i:s'), false), 2, ',', '.').'</td>';
				}

			if ($exibir['mo_previsto'])  {
				echo '<td align="right">'.number_format($obj->mao_obra_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['mo_previsto_total'])  {
				echo '<td align="right">'.number_format($obj->mao_obra_previsto(date('Y-m-d H:i:s'), false), 2, ',', '.').'</td>';
				}

			if ($exibir['total_estimado'])  {
				echo '<td align="right">'.number_format(($obj->mao_obra_previsto(date('Y-m-d H:i:s'))+$obj->recurso_previsto(date('Y-m-d H:i:s'))+$obj->custo_previsto(date('Y-m-d H:i:s'))+$obj->gasto_registro(true)), 2, ',', '.').'</td>';
				}

			if ($exibir['total_estimado_total'])  {
				echo '<td align="right">'.number_format(($obj->mao_obra_previsto(date('Y-m-d H:i:s'), '', false)+$obj->recurso_previsto(date('Y-m-d H:i:s'), '', false)+$obj->custo_estimado(true)+$obj->gasto_registro(true)), 2, ',', '.').'</td>';
				}

			if (isset($exibir['ne_qnt']) && $exibir['ne_qnt']) {
				$sql->adTabela('financeiro_rel_ne','financeiro_rel_ne');
				$sql->adOnde('financeiro_rel_ne_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_rel_ne_ne)');
				$qnt_ne=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_ne ? $qnt_ne : '').'</td>';
				}
			if (isset($exibir['ne_valor']) && $exibir['ne_valor']) {
				$sql->adTabela('financeiro_rel_ne','financeiro_rel_ne');
				$sql->adOnde('financeiro_rel_ne_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_rel_ne_valor)');
				$soma_ne=$sql->resultado();
				$sql->limpar();			
				echo '<td align="right">'.($soma_ne > 0 ? number_format($soma_ne, 2, ',', '.') : '').'</td>';
				}
			if (isset($exibir['ne_estorno_qnt']) && $exibir['ne_estorno_qnt']) {
				$sql->adTabela('financeiro_estorno_rel_ne_fiplan','financeiro_estorno_rel_ne_fiplan');
				$sql->adOnde('financeiro_estorno_rel_ne_fiplan_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_estorno_rel_ne_fiplan_ne_estorno)');
				$qnt_ne_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_ne_estorno ? $qnt_ne_estorno : '').'</td>';
				}
			if (isset($exibir['ne_estorno_valor']) && $exibir['ne_estorno_valor']) {
				$sql->adTabela('financeiro_estorno_rel_ne_fiplan','financeiro_estorno_rel_ne_fiplan');
				$sql->adOnde('financeiro_estorno_rel_ne_fiplan_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_estorno_rel_ne_fiplan_valor)');
				$soma_ne_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($soma_ne_estorno ? $soma_ne_estorno : '').'</td>';
				}
			
			if (isset($exibir['ns_qnt']) && $exibir['ns_qnt']) {
				$sql->adTabela('financeiro_rel_ns','financeiro_rel_ns');
				$sql->adOnde('financeiro_rel_ns_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_rel_ns_ns)');
				$qnt_ns=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_ns ? $qnt_ns : '').'</td>';
				}
			if (isset($exibir['ns_valor']) && $exibir['ns_valor']) {
				$sql->adTabela('financeiro_rel_ns','financeiro_rel_ns');
				$sql->adOnde('financeiro_rel_ns_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_rel_ns_valor)');
				$soma_ns=$sql->resultado();
				$sql->limpar();			
				echo '<td align="right">'.($soma_ns > 0 ? number_format($soma_ns, 2, ',', '.') : '').'</td>';
				}
			if (isset($exibir['ns_estorno_qnt']) && $exibir['ns_estorno_qnt']) {
				$sql->adTabela('financeiro_estorno_rel_ns_fiplan','financeiro_estorno_rel_ns_fiplan');
				$sql->adOnde('financeiro_estorno_rel_ns_fiplan_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_estorno_rel_ns_fiplan_ns_estorno)');
				$qnt_ns_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_ns_estorno ? $qnt_ns_estorno : '').'</td>';
				}
			if (isset($exibir['ns_estorno_valor']) && $exibir['ns_estorno_valor']) {
				$sql->adTabela('financeiro_estorno_rel_ns_fiplan','financeiro_estorno_rel_ns_fiplan');
				$sql->adOnde('financeiro_estorno_rel_ns_fiplan_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_estorno_rel_ns_fiplan_valor)');
				$soma_ns_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($soma_ns_estorno ? $soma_ns_estorno : '').'</td>';
				}
			
			if (isset($exibir['ob_qnt']) && $exibir['ob_qnt']) {
				$sql->adTabela('financeiro_rel_ob','financeiro_rel_ob');
				$sql->adOnde('financeiro_rel_ob_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_rel_ob_ob)');
				$qnt_ob=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_ob ? $qnt_ob : '').'</td>';
				}
			if (isset($exibir['ob_valor']) && $exibir['ob_valor']) {
				$sql->adTabela('financeiro_rel_ob','financeiro_rel_ob');
				$sql->adOnde('financeiro_rel_ob_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_rel_ob_valor)');
				$soma_ob=$sql->resultado();
				$sql->limpar();			
				echo '<td align="right">'.($soma_ob > 0 ? number_format($soma_ob, 2, ',', '.') : '').'</td>';
				}
			if (isset($exibir['ob_estorno_qnt']) && $exibir['ob_estorno_qnt']) {
				$sql->adTabela('financeiro_estorno_rel_ob_fiplan','financeiro_estorno_rel_ob_fiplan');
				$sql->adOnde('financeiro_estorno_rel_ob_fiplan_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_estorno_rel_ob_fiplan_ob_estorno)');
				$qnt_ob_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_ob_estorno ? $qnt_ob_estorno : '').'</td>';
				}
			if (isset($exibir['ob_estorno_valor']) && $exibir['ob_estorno_valor']) {
				$sql->adTabela('financeiro_estorno_rel_ob_fiplan','financeiro_estorno_rel_ob_fiplan');
				$sql->adOnde('financeiro_estorno_rel_ob_fiplan_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_estorno_rel_ob_fiplan_valor)');
				$soma_ob_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($soma_ob_estorno ? $soma_ob_estorno : '').'</td>';
				}
			
			if (isset($exibir['gcv_qnt']) && $exibir['gcv_qnt']) {
				$sql->adTabela('financeiro_rel_gcv','financeiro_rel_gcv');
				$sql->esqUnir('financeiro_gcv','financeiro_gcv','financeiro_rel_gcv_gcv=financeiro_gcv_id');
				$sql->adOnde('NUMR_GCV_ESTORNO IS NULL');
				$sql->adOnde('financeiro_rel_gcv_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_rel_gcv_gcv)');
				$qnt_gcv=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_gcv ? $qnt_gcv : '').'</td>';
				}
			if (isset($exibir['gcv_valor']) && $exibir['gcv_valor']) {
				$sql->adTabela('financeiro_rel_gcv','financeiro_rel_gcv');
				$sql->esqUnir('financeiro_gcv','financeiro_gcv','financeiro_rel_gcv_gcv=financeiro_gcv_id');
				$sql->adOnde('NUMR_GCV_ESTORNO IS NULL');
				$sql->adOnde('financeiro_rel_gcv_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(financeiro_rel_gcv_valor)');
				$soma_gcv=$sql->resultado();
				$sql->limpar();			
				echo '<td align="right">'.($soma_gcv > 0 ? number_format($soma_gcv, 2, ',', '.') : '').'</td>';
				}
			if (isset($exibir['gcv_estorno_qnt']) && $exibir['gcv_estorno_qnt']) {
				$sql->adTabela('financeiro_rel_gcv','financeiro_rel_gcv');
				$sql->esqUnir('financeiro_gcv','financeiro_gcv','financeiro_rel_gcv_gcv=financeiro_gcv_id');
				$sql->adOnde('NUMR_GCV_ESTORNO IS NOT NULL');
				$sql->adOnde('financeiro_rel_gcv_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('COUNT(DISTINCT financeiro_rel_gcv_gcv)');
				$qnt_gcv_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($qnt_gcv_estorno ? $qnt_gcv_estorno : '').'</td>';
				}
			if (isset($exibir['gcv_estorno_valor']) && $exibir['gcv_estorno_valor']) {
				$sql->adTabela('financeiro_rel_gcv','financeiro_rel_gcv');
				$sql->esqUnir('financeiro_gcv','financeiro_gcv','financeiro_rel_gcv_gcv=financeiro_gcv_id');
				$sql->adOnde('NUMR_GCV_ESTORNO IS NOT NULL');
				$sql->adOnde('financeiro_rel_gcv_projeto = '.(int)$linha['projeto_id']);
				$sql->adCampo('SUM(VALR_GCV)');
				$soma_gcv_estorno=$sql->resultado();
				$sql->limpar();
				echo '<td align="center">'.($soma_gcv_estorno ? $soma_gcv_estorno : '').'</td>';
				}
			
			
			
			
			
		




      foreach($exibir_customizado as $cmp){
        $campo_id = (int) $cmp;
        if(isset($campos_extras[$campo_id])){
          $campo = $campos_extras[$campo_id];
          $sql->adTabela('campo_customizado_valor');
          $sql->adCampo('campo_customizado_valor_caractere, campo_customizado_valor_inteiro');
          $sql->adOnde('campo_customizado_valor_campo = '.$campo_id);
          $sql->adOnde('campo_customizado_valor_objeto = '.$obj->projeto_id);
          $valor = $sql->linha();
          $sql->limpar();
          if(!empty($valor)){
            switch($campo['campo_customizado_tipo_html']){
                case 'data':
                    $dataFormatada = '';
                    if( strlen($valor['campo_customizado_valor_caractere']) === 10 ) {
                        $dataFormatada = substr( $valor[ 'campo_customizado_valor_caractere' ], 8, 2 )
                                         . '/' . substr( $valor[ 'campo_customizado_valor_caractere' ], 5, 2 )
                                         . '/' . substr( $valor[ 'campo_customizado_valor_caractere' ], 0, 4 );
                    }
                    echo '<td style="text-align:center;">'.$dataFormatada.'</td>';
                    break;

                case 'valor':
                    $valorFormatado = '';
                    if( $valor[ 'campo_customizado_valor_caractere' ] ) {
                        $valorFormatado = number_format( $valor[ 'campo_customizado_valor_caractere' ], 2, ',', '.' );
                    }

                    echo '<td style="text-align:center;">' . $valorFormatado . '</td>';
                    break;

                case 'formula':

                    $campoFormula = new CampoCustomizadoFormula(
                        $campo['campo_customizado_id'],
                        $campo['campo_customizado_nome'],
                        $campo['campo_customizado_ordem'],
                        $campo['campo_customizado_descricao'],
                        $campo['campo_customizado_formula'],
                        $campo['campo_customizado_tags_extras'],
                        $campo['campo_customizado_publicado'],
                        $campo['campo_customizado_descendente'],
                        $campo['campo_customizado_por_chave'],
                        (int)$linha['projeto_id']
                    );

                    $valorFormatado = number_format( $campoFormula->calcularValor(), 2, ',', '.' );


                    echo '<td style="text-align:center;">'.$valorFormatado.'</td>';
                    break;

                case 'href':
                    echo '<td>'.$valor['campo_customizado_valor_caractere'].'</td>';
                    break;

              case 'textinput':
              case 'textarea':
                echo '<td>'.$valor['campo_customizado_valor_caractere'].'</td>';
                break;
              case 'select':
              case 'selecionar':
                $res = '';
                $cor = null;
                if(isset($campo['lista']) && isset($campo['lista'][$valor['campo_customizado_valor_caractere']])){
                  $res = $campo['lista'][$valor['campo_customizado_valor_caractere']]['campo_customizado_lista_valor'];
                  $cor = $campo['lista'][$valor['campo_customizado_valor_caractere']]['campo_customizado_lista_cor'];
                }

                echo '<td style="text-align:center;' . (!empty($cor) ? 'background-color:#'.$cor.';': '').'">'.$res.'</td>';
                break;
              case 'checkbox':
                $checado = (int)$valor['campo_customizado_valor_inteiro'];
                echo '<td style="text-align:center;">'.($checado ? 'X' : '').'</td>';
                break;
              default:
                echo '<td></td>';
              }
            }
          else{
            echo '<td></td>';
            }
          }
        }

			}


		if ($exibir['projeto_numero_tarefas']) echo '<td align="center" style="white-space: nowrap">'.$linha['total_tarefas'].($linha['minhas_tarefas'] ? ' ('.$linha['minhas_tarefas'].')' : '').($linha['total_tarefas'] ? '': '&nbsp;').'</td>';
        if (!$from_lista) echo '<td style="width: 30px; text-align: center">'.($linha['projeto_ativo'] ? 'Sim' : 'N�o').'</td>';
        if (!$dialogo) echo '<td align="center">'.($editar ? dica('Selecionar '.ucfirst($config['projeto']), 'Marque esta caixa caso deseje mudar o valores, status ou deslocar '.$config['genero_projeto'].' '.$config['projeto'].'.<ul><li>Ap�s ter terminado de marcar '.$config['genero_projeto'].'s '.$config['projetos'].' selecione a op��o nas caixas de op��o no canto inferior.</ul>').'<input type="checkbox" name="projeto_id[]" value="'.$linha['projeto_id'].'" onclick="selecionar_projeto( '.$linha['projeto_id'].')" onfocus="estah_marcado=true;" onblur="estah_marcado=false;" id="selecao_projeto_'.$linha['projeto_id'].'" />'.dicaF() : '&nbsp;').'</td>';
		echo '</tr>';
		}
	}


if ($nenhum){
	echo '<tr><td colspan="20"><p>'.($config['genero_projeto']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['projeto'].' encontrad'.$config['genero_projeto'].'.</p></td></tr></table></td></tr>';
	
	
	if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%></td><td>'.botao('nenhum', 'Nenhum', 'Clique neste bot�o para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste bot�o para fechar esta janela de sele��o','','javascript:fecharPopupExtJS()').'</td></tr></table></td></tr>';


	
	if (!$dialogo && $Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'recebe_cia')) {
		$sql->adTabela('projeto_observado');
		$sql->adCampo('count(projeto_id)');
		$sql->adOnde('aprovado = 0');
		$sql->adOnde('cia_para ='.(int)$Aplic->usuario_cia);
		$resultado=$sql->Resultado();
		$sql->limpar();
		if ($resultado) echo '<tr><td colspan=20><table width="100%" class="std2"><tr><td>'.botao('receber ('.$resultado.')', 'Receber '.$resultado.' '.ucfirst(($resultado>1 ? $config['projetos']: $config['projeto'])),'Clique neste bot�o receber '.$resultado.' '.($resultado>1 ? $config['projetos']: $config['projeto']).' de outra'.($resultado>1 ? 's '.$config['organizacoes']: ' '.$config['organizacao']).'.','','document.env.a.value=\'receber_projeto\'; document.env.tab.value=0; document.env.submit();').'</td></tr></table></td></tr>';
		}
	}
else {
		
		if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste bot�o para confirmar as op��es marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste bot�o para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste bot�o para fechar esta janela de sele��o','','javascript:fecharPopupExtJS()').'</td></tr></table></td></tr>';


		echo '</table></td></tr>';

		if (!$dialogo){
			echo '<tr><td colspan=20><table width="100%" class="std2" cellspacing=0 cellpadding=0>';
			echo '<tr><td align=right><table cellspacing=0 cellpadding=0><tr>';
			if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'envia_cia')) echo '<td>'.botao('enviar', 'Enviar Para Outr'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste bot�o para enviar '.$config['genero_projeto'].'s '.$config['projetos'].' acima selecionad'.$config['genero_projeto'].'s para outr'.$config['genero_organizacao'].' '.$config['organizacao'].'.','','enviar()').'</td>';

			if ($Aplic->profissional) echo '<td>'.botao('valores', 'Mudar Valores d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']), ucfirst($config['genero_projeto']).'s '.$config['projetos'].' selecionad'.$config['genero_projeto'].'s poder�o ter valores de campos como respons�vel, dura��o, �nicio, t�rmino, etc. modificados todos de uma �nica vez.','','valores_projetos();').'</td>';


			if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'recebe_cia')) {
				$sql->adTabela('projeto_observado');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('aprovado = 0');
				$sql->adOnde('cia_para ='.(int)$Aplic->usuario_cia);
				$resultado=$sql->Resultado();
				$sql->limpar();
				if ($resultado) echo '<td>'.botao('receber ('.$resultado.')', 'Receber '.$resultado.' '.ucfirst(($resultado>1 ? $config['projetos']: $config['projeto'])),'Clique neste bot�o receber '.$resultado.' '.($resultado>1 ? $config['projetos']: $config['projeto']).' de outra'.($resultado>1 ? 's '.$config['organizacoes']: ' '.$config['organizacao']).'.','','document.env.a.value=\'receber_projeto\'; document.env.tab.value=0; document.env.submit();').'</td>';

				if (is_array($projetos_status) && (count($projetos_status)==$tab)){
					$sql->adTabela('projeto_observado');
					$sql->adCampo('count(projeto_id)');
					$sql->adOnde('aprovado = 1');
					$sql->adOnde('cia_para ='.(int)$Aplic->usuario_cia);
					$resultado=$sql->Resultado();
					$sql->limpar();
					if ($resultado) echo '<td>'.botao('administrar recebidos','Administrar Recebidos' ,'Clique neste bot�o para administrar '.($resultado>1 ? $config['genero_projeto'].'s '.$config['projetos'].' recebid'.$config['genero_projeto'].'s de outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].'.': $config['genero_projeto'].' '.$config['projeto'].' recebid'.$config['genero_projeto'].' de outr'.$config['genero_organizacao'].' '.$config['organizacao']),'','document.env.a.value=\'administrar_projetos\'; document.env.tab.value=0; document.env.submit();').'</td>';
					}

				}

			//echo '<td align="right">'.dica('Modificar o Status', 'Modificar o status d'.$config['genero_projeto'].'s '.$config['projetos'].' acima selecionad'.$config['genero_projeto'].'s').'Status: '.dicaF().selecionaVetor($vetorStatus, 'projeto_status', 'size="1" class="texto" onChange="mudarStatus();"').'&nbsp;&nbsp;&nbsp;&nbsp;';
            if( $Aplic->profissional ){
                echo '<td>'.botao('Deslocar no Tempo','Deslocar no Tempo' ,'Clique neste bot�o para deslocar no tempo '.$config['genero_projeto'].'s '.$config['projetos'].'selecionados','','selecionarDeslocamentoProjetos();').'</td>';
            }
            else{
                echo '<td align="right">'.dica('Deslocar no Tempo '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Deslocar todas as datas d'.$config['genero_projeto'].'s '.$config['projetos'].' acima selecionad'.$config['genero_projeto'].'s com '.$config['genero_tarefa'].'s respectiv'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Deslocar: '.dicaF().selecionaVetor($mover, 'mover_semanas', 'size="1" class="texto" onChange="deslocar();"');
            }
			echo '<input type="hidden" name="atualizar_projeto_status" id="atualizar_projeto_status" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto" id="modificar_datas_projeto" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto_dias" id="modificar_datas_projeto_dias" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto_semanas" id="modificar_datas_projeto_semanas" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto_meses" id="modificar_datas_projeto_meses" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto_anos" id="modificar_datas_projeto_anos" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto_data" id="modificar_datas_projeto_data" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto_planejada" id="modificar_datas_projeto_planejada" value="" />';
			echo'</td></tr></table></td></tr>';

			echo '</td></tr></table>';
			}
		}

if (!$dialogo){
	echo '<tr><td colspan="20"><table border=0 cellpadding=0 cellspacing=0 class="std2" width="100%"><tr>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#ffffff">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Futur'.$config['genero_projeto'], ucfirst($config['projeto']).' futur'.$config['genero_projeto'].' � '.$config['genero_projeto'].' que a data de �nicio ainda n�o ocorreu.').ucfirst($config['projeto']).' futur'.$config['genero_projeto'].dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#e6eedd">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Iniciad'.$config['genero_projeto'].' e Dentro do Prazo', ucfirst($config['projeto']).' iniciad'.$config['genero_projeto'].' e dentro do prazo � '.$config['genero_projeto'].' que a data de �nicio j� ocorreu, e j� est� acima de 0% executada, entretanto ainda n�o se chegou na data de t�rmino.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#ffeebb">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' que Deveria ter Iniciad'.$config['genero_projeto'], ucfirst($config['projeto']).' deveria ter iniciad'.$config['genero_projeto'].' � '.$config['genero_projeto'].' que a data de �nicio d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' j� ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_projeto'].'.').'Deveria ter iniciad'.$config['genero_projeto'].''.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#cc6666">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Atrasad'.$config['genero_projeto'], ucfirst($config['projeto']).' em atraso � '.$config['genero_projeto'].' que a data de t�rmino d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' j� ocorreu, entretanto ainda n�o se encontra em 100% executad'.$config['genero_projeto'].'.').'Em atraso'.dicaF().'</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#aaddaa">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Terminad'.$config['genero_projeto'], ucfirst($config['projeto']).' terminad'.$config['genero_projeto'].' � � quando est� 100% executad'.$config['genero_projeto'].'.').'Terminad'.$config['genero_projeto'].dicaF().'</td>';
	echo '<td width="100%">&nbsp;</td>';
	echo '</tr></table>';
	echo '</td></tr>';
	}
echo '</table>';



echo '</table>';

?>
<script>

function selecionar(projeto_id){
	var nome=document.getElementById('projeto_nome_'+projeto_id).innerHTML;
	setFechar(projeto_id, nome);
	}
	

function selecionar_multiplo(){
	var qnt=0;	
	var saida='';
	 with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			if (thiselm.checked && thiselm.name=='campos[]') saida=saida+(qnt++ ? ',' : '')+thiselm.value;
      }
    }
	setFechar(saida, '');
	}	


function setFechar(chave, valor){
	if(parent && parent.gpwebApp){
		if (chave) parent.gpwebApp._popupCallback(chave, valor); 
		else parent.gpwebApp._popupCallback(null, "");
		} 
	else {
		if (chave!=0) <?php echo 'window.opener.'.($chamarVolta ? $chamarVolta : 'vazio').'(chave, valor);'?>
		else <?php echo 'window.opener.'.($chamarVolta ? $chamarVolta : 'vazio').'(null, "");'?>  
		window.close();
		}
	}
	
function cancelarSelecao(){
	if(parent && parent.gpwebApp && parent.gpwebApp._popupWin) parent.gpwebApp._popupWin.close(); 
	else window.close();
	}

function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }



function valores_projetos(){
	if (verifica_selecao()>0){
		document.env.m.value='projetos';
		document.env.a.value='projetos_valores_pro';
		document.env.submit();
		}
	}



function mudarStatus(){
	if (verifica_selecao()>0){
		if(confirm('Tem certeza que deseja modificar o status?')){
			document.getElementById('atualizar_projeto_status').value=1;
			document.env.submit();
			}
		}
	}

function deslocar(){
	if (verifica_selecao()>0){
		if(confirm('Tem certeza que deseja deslocar <?php echo $config["genero_projeto"]." ".$config["projeto"]." com su".$config["genero_tarefa"]."s ".$config["tarefas"]?> no tempo? Este processo pode ser demorado!')){
			document.getElementById('modificar_datas_projeto').value=1;
			document.env.submit();
			}
		}
	}

function selecionarDeslocamentoProjetos(){
    if( verifica_selecao() ) {
        parent.gpwebApp.selecionarDeslocamentoProjetos(deslocarProfissional, window);
    }
}

function deslocarProfissional(dados){
    document.getElementById('modificar_datas_projeto').value= dados.tipo;
    document.getElementById('modificar_datas_projeto_dias').value= dados.dias;
    document.getElementById('modificar_datas_projeto_semanas').value= dados.semanas;
    document.getElementById('modificar_datas_projeto_meses').value= dados.meses;
    document.getElementById('modificar_datas_projeto_anos').value= dados.anos;
    document.getElementById('modificar_datas_projeto_data').value= dados.data;
    document.getElementById('modificar_datas_projeto_planejada').value= dados.planejada;
    document.env.submit();
}

function verifica_selecao(){
    var j = 0, i,
        elements = document.getElementsByName( 'projeto_id[]' );

    for( i = 0; i < elements.length; i++ ) {
        var element = elements[ i ];

        if( element.checked ) {
            j++;
        }
    }
    
    if( j > 0 ) {
        return 1;
    }
    else {
        alert( "Selecione ao menos um <?php echo $config[ 'projeto' ]?>!" );
        return 0;
    }
}

function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function enviar(){
	var achado=0;
	with(document.getElementById('env')) {
		  for(i=0; i < elements.length; i++) if (elements[i].checked == true) {achado=1; break;};
      }
	if (achado){
		document.env.a.value='enviar_projeto';
		document.env.submit();
		}
	else alert('Necessita selecionar ao menos um<?php echo ($config["genero_projeto"]=="a" ? "a" : "")." ".$config["projeto"]?>');
	}

function iluminar_tds(linha,alto,id){
    if(document.getElementsByTagName){
        var tcs = linha.getElementsByTagName('td'),
            checked = false,
            form = document.env,
            nome_celula, j, j_cmp;

        if(id && form && form['selecao_projeto_'+id]){
            checked = form['selecao_projeto_'+id].checked
        }

        for(j=0, j_cmp=tcs.length; j<j_cmp; j+=1){
            nome_celula = tcs[j].id;

            if(!(nome_celula.indexOf('ignore_td_')>=0)){
                if(alto==3) tcs[j].style.background='#FFFFCC';
                else if(alto==2||checked)

                    tcs[j].style.background='#FFCCCC';

                else if(alto==1) tcs[j].style.background='#FFFFCC';
                else tcs[j].style.background='#FFFFFF';
            }
        }
    }
}
</script>
