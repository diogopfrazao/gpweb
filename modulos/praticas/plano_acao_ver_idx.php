<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $m, $a, $u, $tab, $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $Aplic, $cia_id, $tab, $favorito_id, $dialogo, $usuario_id, $pesquisar_texto, $lista_cias, $dept_id, $lista_depts, $plano_acao_ano, $filtro_prioridade_acao,
       $projeto_id,
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

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina=getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_plano_acao']);
$xmin = $xtamanhoPagina * ($pagina - 1);


$ordenar=getParam($_REQUEST, 'ordenar', 'plano_acao_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');
if ($lista_depts) $lista_depts=(is_array($lista_depts) ? implode(',', $lista_depts) : $lista_depts);
if ($lista_cias) $lista_cias=(is_array($lista_cias) ? implode(',', $lista_cias) : $lista_cias);

$sql = new BDConsulta;

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'acoes\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if ($Aplic->profissional){
    $sql->adTabela('campo_formulario');
    $sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
    $sql->adOnde('campo_formulario_tipo = \'acoes\'');
    $sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
    $exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
    $sql->limpar();

    $diff = array_diff_key($exibir, $exibir2);
    if($diff) $exibir = array_merge($exibir2, $diff);
    else $exibir = $exibir2;
}

$from_lista = (isset($m) && is_string($m) && strtolower($m)==='praticas')
              && (!isset($u) || $u === '')
              && (isset($a) && is_string($a) && strtolower($a)==='plano_acao_lista');

$from_para_fazer = (isset($m) && is_string($m) && strtolower($m)==='tarefas')
                   && (!isset($u) || $u === '')
                   && (isset($a) && is_string($a) && strtolower($a)==='parafazer');

$sql->adTabela('plano_acao');
$sql->adCampo('count(DISTINCT(plano_acao.plano_acao_id)) as soma');

aplicar_filtros($sql, $from_lista, $from_para_fazer);

$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('plano_acao');

$sql->esqUnir('plano_acao_item', 'plano_acao_item','plano_acao_item_acao=plano_acao.plano_acao_id');
$sql->adCampo('plano_acao.*, count(plano_acao_item_id) AS qnt');

aplicar_filtros($sql, $from_lista, $from_para_fazer);


$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));

if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_acao=plano_acao.plano_acao_id) AS tem_aprovacao');
$sql->adGrupo('plano_acao.plano_acao_id');
$sql->setLimite($xmin, $xtamanhoPagina);

$plano_acoes=$sql->Lista();

$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['acao']), ucfirst($config['acoes']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';

if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';

if ($exibir['plano_acao_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identifica��o d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identifica��o d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Nome'.dicaF().'</a></th>';

if ($Aplic->profissional && $exibir['plano_acao_aprovado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_acao) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Prioriza��o', 'Clique para ordenar pela prioriza��o.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';

if ($exibir['plano_acao_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri��o', 'Neste campo fica a descri��o d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Descri��o'.dicaF().'</a></th>';
if ($exibir['plano_acao_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionado', 'A quais �reas do sistema est� relacionado.').'Relacionado'.dicaF().'</th>';
if ($exibir['plano_acao_inicio']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('In�cio', 'A data de �nicio d'.$config['genero_acao'].'s '.$config['acoes'].'.').'In�cio'.dicaF().'</a></th>';
if ($exibir['plano_acao_fim']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('T�rmino', 'A data de t�rmino d'.$config['genero_acao'].'s '.$config['acoes'].'.').'T�rmino'.dicaF().'</a></th>';
if ($exibir['plano_acao_percentagem']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem executada n'.$config['genero_acao'].'s '.$config['acoes'].'.').'%'.dicaF().'</a></th>';
if ($exibir['plano_acao_ano']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_ano&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_ano' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ano', 'O ano base dos'.$config['genero_acao'].'s '.$config['acoes'].'.').'Ano'.dicaF().'</a></th>';
if ($exibir['plano_acao_codigo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('C�digo', 'Os c�digos d'.$config['genero_acao'].'s '.$config['acoes'].'.').'C�digo'.dicaF().'</a></th>';


if ($exibir['plano_acao_cia']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_cia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_cia' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']), 'As '.$config['organizacoes'].' d'.$config['genero_acao'].'s '.$config['acoes'].'.').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['plano_acao_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['plano_acao_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' respons�vel.').($ordenar=='plano_acao_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['plano_acao_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), 'Neste campo fica '.$config['genero_dept'].'s '.$config['departamentos'].' envolvid'.$config['genero_dept'].'s n'.$config['genero_acao'].'s '.$config['acoes'].'.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['plano_acao_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'O '.$config['usuario'].' respons�vel pel'.$config['genero_acao'].'s '.$config['acoes'].'.').'Respons�vel'.dicaF().'</a></th>';
if ($exibir['plano_acao_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_acao'].'s '.$config['acoes'].'.').'Designados'.dicaF().'</th>';
if ($exibir['plano_acao_quantidade']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=qnt&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='qnt' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quantidade', 'A quantidade de linhas inseridas n'.$config['genero_acao'].'s '.$config['acoes'].'.').'Qnt'.dicaF().'</a></th>';
if(!$from_lista && !$from_para_fazer) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_ativo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar==='plano_acao_ativo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ativo', 'Neste campo consta se '.$config['genero_acao'].' '.$config['acao'].' est� ativ'.$config['genero_acao'].'.').'At.'.dicaF().'</a></th>';
echo '</tr>';
$qnt=0;
$agora =date('Y-m-d');

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'plano_acao');

foreach ($plano_acoes as $linha) {
    if (permiteAcessarPlanoAcao($linha['plano_acao_acesso'],$linha['plano_acao_id'])){
        $editar=permiteEditarPlanoAcao($linha['plano_acao_acesso'],$linha['plano_acao_id']);
        if ($edicao && !$editar && !$Aplic->usuario_ver_tudo) continue;
        if ($Aplic->profissional) $bloquear=($linha['plano_acao_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
        else $bloquear=0;

        $qnt++;
        $estilo ='';
        if($linha['plano_acao_inicio'] && $linha['plano_acao_fim']){
            if ($agora < $linha['plano_acao_inicio'] && $linha['plano_acao_percentagem'] < 100) $estilo = 'style="background-color:#ffffff"';
            if ($agora > $linha['plano_acao_inicio'] && $agora < $linha['plano_acao_fim'] && $linha['plano_acao_percentagem'] > 0 && $linha['plano_acao_percentagem'] < 100 ) $estilo = 'style="background-color:#e6eedd"';
            if ($agora > $linha['plano_acao_inicio'] && $agora < $linha['plano_acao_fim'] && $linha['plano_acao_percentagem'] == 0) $estilo = 'style="background-color:#ffeebb"';
            if ($agora > $linha['plano_acao_fim'] && $linha['plano_acao_percentagem'] < 100) $estilo = 'style="background-color:#cc6666"';
            elseif ($linha['plano_acao_percentagem'] == 100) $estilo = 'style="background-color:#aaddaa"';
        }

        echo '<tr>';
        if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($podeEditar && $editar && !$bloquear ? dica('Editar '.ucfirst($config['acao']), 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.$config['genero_acao'].'s '.$config['acoes'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_id='.$linha['plano_acao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';

        if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['plano_acao_id'].'" value="'.$linha['plano_acao_id'].'"  onclick="selecionar(this.value)" />';
        if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['plano_acao_id'].'" value="'.$linha['plano_acao_id'].'" '.(isset($selecionado[$linha['plano_acao_id']]) ? 'checked="checked"' : '').' />';


        if ($exibir['plano_acao_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['plano_acao_cor'].'">&nbsp;&nbsp;</td>';

        if ($selecao) echo '<td id="plano_acao_nome_'.$linha['plano_acao_id'].'">'.$linha['plano_acao_nome'].'</td>';
        else echo '<td '.$estilo.' >'.link_acao($linha['plano_acao_id'],'','','','',true).'</a></td>';

        if ($Aplic->profissional && $exibir['plano_acao_aprovado']) echo '<td style="width: 30px; text-align: center">'.($linha['plano_acao_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'N�o' : 'N/A')).'</td>';
        if ($filtro_prioridade_acao) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';


        if ($exibir['plano_acao_descricao']) echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_descricao'] ? $linha['plano_acao_descricao']: '&nbsp;').'</td>';

        if ($exibir['plano_acao_relacionado']){
            $sql->adTabela('plano_acao_gestao');
            $sql->adCampo('plano_acao_gestao.*');
            $sql->adOnde('plano_acao_gestao_acao ='.(int)$linha['plano_acao_id']);
            $sql->adOrdem('plano_acao_gestao_ordem');
            $lista = $sql->Lista();
            $sql->limpar();
            $qnt_gestao=0;
            echo '<td>';
            if (count($lista)) {
                foreach($lista as $gestao_data){
                    if ($gestao_data['plano_acao_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['plano_acao_gestao_tarefa']);
                    elseif ($gestao_data['plano_acao_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['plano_acao_gestao_projeto']);
                    elseif ($gestao_data['plano_acao_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['plano_acao_gestao_pratica']);

                    elseif ($gestao_data['plano_acao_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['plano_acao_gestao_semelhante']);

                    elseif ($gestao_data['plano_acao_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['plano_acao_gestao_perspectiva']);
                    elseif ($gestao_data['plano_acao_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['plano_acao_gestao_tema']);
                    elseif ($gestao_data['plano_acao_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['plano_acao_gestao_objetivo']);
                    elseif ($gestao_data['plano_acao_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['plano_acao_gestao_fator']);
                    elseif ($gestao_data['plano_acao_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['plano_acao_gestao_estrategia']);
                    elseif ($gestao_data['plano_acao_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['plano_acao_gestao_meta']);
                    elseif ($gestao_data['plano_acao_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['plano_acao_gestao_canvas']);
                    elseif ($gestao_data['plano_acao_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['plano_acao_gestao_risco']);
                    elseif ($gestao_data['plano_acao_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['plano_acao_gestao_risco_resposta']);
                    elseif ($gestao_data['plano_acao_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['plano_acao_gestao_indicador']);
                    elseif ($gestao_data['plano_acao_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['plano_acao_gestao_calendario']);
                    elseif ($gestao_data['plano_acao_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['plano_acao_gestao_monitoramento']);
                    elseif ($gestao_data['plano_acao_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['plano_acao_gestao_ata']);
                    elseif ($gestao_data['plano_acao_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['plano_acao_gestao_mswot']);
                    elseif ($gestao_data['plano_acao_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['plano_acao_gestao_swot']);
                    elseif ($gestao_data['plano_acao_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['plano_acao_gestao_operativo']);
                    elseif ($gestao_data['plano_acao_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['plano_acao_gestao_instrumento']);
                    elseif ($gestao_data['plano_acao_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['plano_acao_gestao_recurso']);
                    elseif ($gestao_data['plano_acao_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['plano_acao_gestao_problema']);
                    elseif ($gestao_data['plano_acao_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['plano_acao_gestao_demanda']);
                    elseif ($gestao_data['plano_acao_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['plano_acao_gestao_programa']);
                    elseif ($gestao_data['plano_acao_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['plano_acao_gestao_licao']);
                    elseif ($gestao_data['plano_acao_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['plano_acao_gestao_evento']);
                    elseif ($gestao_data['plano_acao_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['plano_acao_gestao_link']);
                    elseif ($gestao_data['plano_acao_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['plano_acao_gestao_avaliacao']);
                    elseif ($gestao_data['plano_acao_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['plano_acao_gestao_tgn']);
                    elseif ($gestao_data['plano_acao_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['plano_acao_gestao_brainstorm']);
                    elseif ($gestao_data['plano_acao_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['plano_acao_gestao_gut']);
                    elseif ($gestao_data['plano_acao_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['plano_acao_gestao_causa_efeito']);
                    elseif ($gestao_data['plano_acao_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['plano_acao_gestao_arquivo']);
                    elseif ($gestao_data['plano_acao_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['plano_acao_gestao_forum']);
                    elseif ($gestao_data['plano_acao_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['plano_acao_gestao_checklist']);
                    elseif ($gestao_data['plano_acao_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['plano_acao_gestao_agenda']);
                    elseif ($gestao_data['plano_acao_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['plano_acao_gestao_agrupamento']);
                    elseif ($gestao_data['plano_acao_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['plano_acao_gestao_patrocinador']);
                    elseif ($gestao_data['plano_acao_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['plano_acao_gestao_template']);
                    elseif ($gestao_data['plano_acao_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['plano_acao_gestao_painel']);
                    elseif ($gestao_data['plano_acao_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['plano_acao_gestao_painel_odometro']);
                    elseif ($gestao_data['plano_acao_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['plano_acao_gestao_painel_composicao']);
                    elseif ($gestao_data['plano_acao_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['plano_acao_gestao_tr']);
                    elseif ($gestao_data['plano_acao_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['plano_acao_gestao_me']);
                    elseif ($gestao_data['plano_acao_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['plano_acao_gestao_acao_item']);
                    elseif ($gestao_data['plano_acao_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['plano_acao_gestao_beneficio']);
                    elseif ($gestao_data['plano_acao_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['plano_acao_gestao_painel_slideshow']);
                    elseif ($gestao_data['plano_acao_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['plano_acao_gestao_projeto_viabilidade']);
                    elseif ($gestao_data['plano_acao_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['plano_acao_gestao_projeto_abertura']);
                    elseif ($gestao_data['plano_acao_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['plano_acao_gestao_plano_gestao']);
                    elseif ($gestao_data['plano_acao_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['plano_acao_gestao_ssti']);
                    elseif ($gestao_data['plano_acao_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['plano_acao_gestao_laudo']);
                    elseif ($gestao_data['plano_acao_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['plano_acao_gestao_trelo']);
                    elseif ($gestao_data['plano_acao_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['plano_acao_gestao_trelo_cartao']);
                    elseif ($gestao_data['plano_acao_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['plano_acao_gestao_pdcl']);
                    elseif ($gestao_data['plano_acao_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['plano_acao_gestao_pdcl_item']);
                    elseif ($gestao_data['plano_acao_gestao_os']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/os_p.png').link_os($gestao_data['plano_acao_gestao_os']);

                }
            }
            echo '</td>';
        }







        if ($exibir['plano_acao_inicio'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_inicio'] ? retorna_data($linha['plano_acao_inicio'], false): '&nbsp;').'</td>';
        if ($exibir['plano_acao_fim'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_fim'] ? retorna_data($linha['plano_acao_fim'], false): '&nbsp;').'</td>';
        if ($exibir['plano_acao_percentagem'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_percentagem'] ? number_format($linha['plano_acao_percentagem'], 1, ',', '.') : '&nbsp;').'</td>';

        if ($exibir['plano_acao_ano'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_ano'] ? $linha['plano_acao_ano'] : '&nbsp;').'</td>';
        if ($exibir['plano_acao_codigo'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_codigo'] ? $linha['plano_acao_codigo'] : '&nbsp;').'</td>';


        if ($exibir['plano_acao_cia'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_cia'] ? link_cia($linha['plano_acao_cia']): '&nbsp;').'</td>';

        if ($exibir['plano_acao_cias']){
            $sql->adTabela('plano_acao_cia');
            $sql->adCampo('plano_acao_cia_cia');
            $sql->adOnde('plano_acao_cia_plano_acao = '.(int)$linha['plano_acao_id']);
            $cias = $sql->carregarColuna();
            $sql->limpar();
            $saida_cias='';
            if (isset($cias) && count($cias)) {
                $plural=(count($cias)>1 ? 's' : '');
                $saida_cias.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
                $saida_cias.= '<tr><td style="border:0px;">'.link_cia($cias[0]);
                $qnt_cias=count($cias);
                if ($qnt_cias > 1) {
                    $lista='';
                    for ($j = 1, $i_cmp = $qnt_cias; $j < $i_cmp; $j++) $lista.=link_cia($cias[$j]).'<br>';
                    $saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['plano_acao_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['plano_acao_id'].'"><br>'.$lista.'</span>';
                }
                $saida_cias.= '</td></tr></table>';
                $plural=(count($cias)>1 ? 's' : '');
            }
            echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
        }
        if ($exibir['plano_acao_dept']) echo '<td>'.link_dept($linha['plano_acao_dept']).'</td>';

        if ($exibir['plano_acao_depts']){
            $sql->adTabela('plano_acao_dept');
            $sql->adCampo('plano_acao_dept_dept');
            $sql->adOnde('plano_acao_dept_acao = '.(int)$linha['plano_acao_id']);
            $depts = $sql->carregarColuna();
            $sql->limpar();

            $saida_dept='';
            if ($depts && count($depts)) {
                $saida_dept.= link_dept($depts[0]);
                $qnt_depts=count($depts);
                if ($qnt_depts > 1) {
                    $lista='';
                    for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts[$i]).'<br>';
                    $saida_dept.= dica('Outros Participantes', 'Clique para visualizar os demais depts.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'depts\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="depts"><br>'.$lista.'</span>';
                }
            }
            echo '<td align="left" style="white-space: nowrap">'.($saida_dept ? $saida_dept : '&nbsp;').'</td>';
        }


        if ($exibir['plano_acao_responsavel']) echo '<td>'.link_usuario($linha['plano_acao_responsavel'],'','','esquerda').'</td>';

        if ($exibir['plano_acao_designados']){
            $sql->adTabela('plano_acao_usuario');
            $sql->adCampo('plano_acao_usuario_usuario');
            $sql->adOnde('plano_acao_usuario_acao = '.(int)$linha['plano_acao_id']);
            $participantes = $sql->carregarColuna();
            $sql->limpar();

            $saida_quem='';
            if ($participantes && count($participantes)) {
                $saida_quem.= link_usuario($participantes[0], '','','esquerda');
                $qnt_participantes=count($participantes);
                if ($qnt_participantes > 1) {
                    $lista='';
                    for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';
                    $saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['plano_acao_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['plano_acao_id'].'"><br>'.$lista.'</span>';
                }
            }
            echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
        }

        if ($exibir['plano_acao_quantidade'])echo '<td width="25" align=center>'.$linha['qnt'].'</td>';

        if (!$from_lista && !$from_para_fazer) echo '<td style="width: 30px; text-align: center">'.($linha['plano_acao_ativo'] ? 'Sim' : 'N�o').'</td>';

        echo '</tr>';
    }
}
if (!count($plano_acoes)) echo '<tr><td colspan="20"><p>Nenhum'.($config['genero_acao']=='a' ? 'a': '').' '.$config['acao'].' encontrad'.$config['genero_acao'].'.</p></td></tr>';
elseif(count($plano_acoes) && !$qnt) echo '<tr><td colspan="20"><p>N�o teve permiss�o de visualizar qualquer d'.$config['genero_acao'].'s '.$config['acoes'].'.</p></td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste bot�o para confirmar as op��es marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste bot�o para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste bot�o para fechar esta janela de sele��o','','javascript:fecharPopupExtJS()').'</td></tr></table></td></tr>';

echo '</table>';

if (!$selecao){
    echo '<table border=0 cellpadding=2 cellspacing=2 '.($dialogo ? '' : 'class="std"').' width="100%"><tr>';

    echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#ffffff">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Futur'.$config['genero_acao'], ucfirst($config['acao']).' futur'.$config['genero_acao'].' � '.$config['genero_acao'].' que a data de �nicio ainda n�o ocorreu.').ucfirst($config['acao']).' futur'.$config['genero_acao'].dicaF().'</td><td>&nbsp; &nbsp;</td>';
    echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#e6eedd">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Iniciad'.$config['genero_acao'].' e Dentro do Prazo', ucfirst($config['acao']).' iniciad'.$config['genero_acao'].' e dentro do prazo � '.$config['genero_acao'].' que a data de �nicio j� ocorreu, e j� est� acima de 0% executada, entretanto ainda n�o se chegou na data de t�rmino.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
    echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#ffeebb">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['acao']).' que Deveria ter Iniciad'.$config['genero_acao'], ucfirst($config['acao']).' deveria ter iniciad'.$config['genero_acao'].' � '.$config['genero_acao'].' que a data de �nicio d'.$config['genero_acao'].' mesm'.$config['genero_acao'].' j� ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_acao'].'.').'Deveria ter iniciad'.$config['genero_acao'].''.dicaF().'</td><td>&nbsp; &nbsp;</td>';
    echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#cc6666">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Atrasad'.$config['genero_acao'], ucfirst($config['acao']).' em atraso � '.$config['genero_acao'].' que a data de t�rmino d'.$config['genero_acao'].' mesm'.$config['genero_acao'].' j� ocorreu, entretanto ainda n�o se encontra em 100% executad'.$config['genero_acao'].'.').'Em atraso'.dicaF().'</td>';
    echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#aaddaa">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Terminad'.$config['genero_acao'], ucfirst($config['acao']).' terminad'.$config['genero_acao'].' � � quando est� 100% executad'.$config['genero_acao'].'.').'Terminad'.$config['genero_acao'].dicaF().'</td>';
    echo '<td width="100%">&nbsp;</td>';
    echo '</tr></table>';
}

function aplicar_filtros($sql, $from_lista, $from_para_fazer){
    if($from_lista){
        aplicar_filtros_from_index($sql);
    }
    else if($from_para_fazer){
        aplicar_filtros_from_para_fazer($sql);
    }

    aplicar_filtros_relacionados($sql);
}

function aplicar_filtros_from_index($sql){
    global $Aplic, $config, $tab, $filtro_prioridade_acao, $favorito_id, $dept_id, $lista_depts,
           $envolvimento, $cia_id, $lista_cias, $plano_acao_ano, $pesquisar_texto, $usuario_id;

    if ($filtro_prioridade_acao){
        $sql->esqUnir('priorizacao', 'priorizacao', 'plano_acao.plano_acao_id=priorizacao_acao');
        if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_acao = plano_acao.plano_acao_id AND priorizacao_modelo IN ('.$filtro_prioridade_acao.')) AS priorizacao');
        else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_acao = plano_acao.plano_acao_id AND priorizacao_modelo IN ('.$filtro_prioridade_acao.')) AS priorizacao');
        $sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_acao.')');
    }

    if ($favorito_id){
        $sql->internoUnir('favorito_lista', 'favorito_lista', 'plano_acao.plano_acao_id=favorito_lista_campo');
        $sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
        $sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
    }
    elseif ($dept_id && !$lista_depts) {
        $sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
        $sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_dept_dept='.(int)$dept_id);
    }
    elseif ($lista_depts) {
        $sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
        $sql->adOnde('plano_acao_dept IN ('.$lista_depts.') OR plano_acao_dept_dept IN ('.$lista_depts.')');
    }
    elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
        $sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
        $sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
    }
    elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
    elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');

    if ($pesquisar_texto) $sql->adOnde('plano_acao_nome LIKE \'%'.$pesquisar_texto.'%\' OR plano_acao_descricao LIKE \'%'.$pesquisar_texto.'%\' OR plano_acao_codigo LIKE \'%'.$pesquisar_texto.'%\'');

    if ($plano_acao_ano) $sql->adOnde('plano_acao_ano = "'.$plano_acao_ano.'"');

    if ($usuario_id) {
        $sql->esqUnir('plano_acao_usuario', 'plano_acao_usuario', 'plano_acao_usuario_acao = plano_acao.plano_acao_id');
        $sql->adOnde('plano_acao_responsavel = '.(int)$usuario_id.' OR plano_acao_usuario_usuario='.(int)$usuario_id);
    }

    if ($tab==0) $sql->adOnde('plano_acao_percentagem < 100');
    if ($tab==1) $sql->adOnde('plano_acao_percentagem = 100');

    if ($tab==2) $sql->adOnde('plano_acao_ativo = 0');
    else $sql->adOnde('plano_acao_ativo = 1');
}

function aplicar_filtros_from_para_fazer($sql){
    global $Aplic;

    $sql->esqUnir('plano_acao_usuario', 'plano_acao_usuario', 'plano_acao_usuario_acao = plano_acao.plano_acao_id');
    $sql->adOnde('plano_acao_responsavel IN('.$Aplic->usuario_lista_grupo.') OR plano_acao_usuario_usuario IN('.$Aplic->usuario_lista_grupo.')');

    $sql->adOnde('plano_acao_percentagem < 100');
    $sql->adOnde('plano_acao_ativo = 1');
}

function aplicar_filtros_relacionados($sql){
    global $tarefa_id, $projeto_id, $pg_perspectiva_id, $tema_id, $objetivo_id, $fator_id,
           $pg_estrategia_id, $pg_meta_id, $pratica_id, $pratica_indicador_id, $plano_acao_id,
           $canvas_id, $risco_id, $risco_resposta_id, $calendario_id, $monitoramento_id, $ata_id,
           $mswot_id, $swot_id, $operativo_id, $instrumento_id, $recurso_id, $problema_id, $demanda_id,
           $programa_id, $licao_id, $evento_id, $link_id, $avaliacao_id, $tgn_id, $brainstorm_id, $gut_id,
           $causa_efeito_id, $arquivo_id, $forum_id, $checklist_id, $agenda_id, $agrupamento_id, $patrocinador_id,
           $template_id, $painel_id, $painel_odometro_id, $painel_composicao_id, $tr_id, $me_id, $plano_acao_item_id,
           $beneficio_id, $painel_slideshow_id, $projeto_viabilidade_id, $projeto_abertura_id, $pg_id, $ssti_id,
           $laudo_id, $trelo_id, $trelo_cartao_id, $pdcl_id, $pdcl_item_id, $os_id;

    $sql->esqUnir('plano_acao_gestao','plano_acao_gestao','plano_acao_gestao_acao = plano_acao.plano_acao_id');

    if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa IN ('.$tarefa_id.')');
    elseif ($projeto_id){
        $sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=plano_acao_gestao_tarefa');
        $sql->adOnde('plano_acao_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
    }
    elseif ($pg_perspectiva_id) $sql->adOnde('plano_acao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
    elseif ($tema_id) $sql->adOnde('plano_acao_gestao_tema IN ('.$tema_id.')');
    elseif ($objetivo_id) $sql->adOnde('plano_acao_gestao_objetivo IN ('.$objetivo_id.')');
    elseif ($fator_id) $sql->adOnde('plano_acao_gestao_fator IN ('.$fator_id.')');
    elseif ($pg_estrategia_id) $sql->adOnde('plano_acao_gestao_estrategia IN ('.$pg_estrategia_id.')');
    elseif ($pg_meta_id) $sql->adOnde('plano_acao_gestao_meta IN ('.$pg_meta_id.')');
    elseif ($pratica_id) $sql->adOnde('plano_acao_gestao_pratica IN ('.$pratica_id.')');
    elseif ($pratica_indicador_id) $sql->adOnde('plano_acao_gestao_indicador IN ('.$pratica_indicador_id.')');
    elseif ($plano_acao_id) $sql->adOnde('plano_acao_gestao_semelhante IN ('.$plano_acao_id.')');
    elseif ($canvas_id) $sql->adOnde('plano_acao_gestao_canvas IN ('.$canvas_id.')');
    elseif ($risco_id) $sql->adOnde('plano_acao_gestao_risco IN ('.$risco_id.')');
    elseif ($risco_resposta_id) $sql->adOnde('plano_acao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
    elseif ($calendario_id) $sql->adOnde('plano_acao_gestao_calendario IN ('.$calendario_id.')');
    elseif ($monitoramento_id) $sql->adOnde('plano_acao_gestao_monitoramento IN ('.$monitoramento_id.')');
    elseif ($ata_id) $sql->adOnde('plano_acao_gestao_ata IN ('.$ata_id.')');
    elseif ($mswot_id) $sql->adOnde('plano_acao_gestao_mswot IN ('.$mswot_id.')');
    elseif ($swot_id) $sql->adOnde('plano_acao_gestao_swot IN ('.$swot_id.')');
    elseif ($operativo_id) $sql->adOnde('plano_acao_gestao_operativo IN ('.$operativo_id.')');
    elseif ($instrumento_id) $sql->adOnde('plano_acao_gestao_instrumento IN ('.$instrumento_id.')');
    elseif ($recurso_id) $sql->adOnde('plano_acao_gestao_recurso IN ('.$recurso_id.')');
    elseif ($problema_id) $sql->adOnde('plano_acao_gestao_problema IN ('.$problema_id.')');
    elseif ($demanda_id) $sql->adOnde('plano_acao_gestao_demanda IN ('.$demanda_id.')');
    elseif ($programa_id) $sql->adOnde('plano_acao_gestao_programa IN ('.$programa_id.')');
    elseif ($licao_id) $sql->adOnde('plano_acao_gestao_licao IN ('.$licao_id.')');
    elseif ($evento_id) $sql->adOnde('plano_acao_gestao_evento IN ('.$evento_id.')');
    elseif ($link_id) $sql->adOnde('plano_acao_gestao_link IN ('.$link_id.')');
    elseif ($avaliacao_id) $sql->adOnde('plano_acao_gestao_avaliacao IN ('.$avaliacao_id.')');
    elseif ($tgn_id) $sql->adOnde('plano_acao_gestao_tgn IN ('.$tgn_id.')');
    elseif ($brainstorm_id) $sql->adOnde('plano_acao_gestao_brainstorm IN ('.$brainstorm_id.')');
    elseif ($gut_id) $sql->adOnde('plano_acao_gestao_gut IN ('.$gut_id.')');
    elseif ($causa_efeito_id) $sql->adOnde('plano_acao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
    elseif ($arquivo_id) $sql->adOnde('plano_acao_gestao_arquivo IN ('.$arquivo_id.')');
    elseif ($forum_id) $sql->adOnde('plano_acao_gestao_forum IN ('.$forum_id.')');
    elseif ($checklist_id) $sql->adOnde('plano_acao_gestao_checklist IN ('.$checklist_id.')');
    elseif ($agenda_id) $sql->adOnde('plano_acao_gestao_agenda IN ('.$agenda_id.')');
    elseif ($agrupamento_id) $sql->adOnde('plano_acao_gestao_agrupamento IN ('.$agrupamento_id.')');
    elseif ($patrocinador_id) $sql->adOnde('plano_acao_gestao_patrocinador IN ('.$patrocinador_id.')');
    elseif ($template_id) $sql->adOnde('plano_acao_gestao_template IN ('.$template_id.')');
    elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel IN ('.$painel_id.')');
    elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
    elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
    elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr IN ('.$tr_id.')');
    elseif ($me_id) $sql->adOnde('plano_acao_gestao_me IN ('.$me_id.')');
    elseif ($plano_acao_item_id) $sql->adOnde('plano_acao_gestao_acao_item IN ('.$plano_acao_item_id.')');
    elseif ($beneficio_id) $sql->adOnde('plano_acao_gestao_beneficio IN ('.$beneficio_id.')');
    elseif ($painel_slideshow_id) $sql->adOnde('plano_acao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
    elseif ($projeto_viabilidade_id) $sql->adOnde('plano_acao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
    elseif ($projeto_abertura_id) $sql->adOnde('plano_acao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
    elseif ($pg_id) $sql->adOnde('plano_acao_gestao_plano_gestao IN ('.$pg_id.')');
    elseif ($ssti_id) $sql->adOnde('plano_acao_gestao_ssti IN ('.$ssti_id.')');
    elseif ($laudo_id) $sql->adOnde('plano_acao_gestao_laudo IN ('.$laudo_id.')');
    elseif ($trelo_id) $sql->adOnde('plano_acao_gestao_trelo IN ('.$trelo_id.')');
    elseif ($trelo_cartao_id) $sql->adOnde('plano_acao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
    elseif ($pdcl_id) $sql->adOnde('plano_acao_gestao_pdcl IN ('.$pdcl_id.')');
    elseif ($pdcl_item_id) $sql->adOnde('plano_acao_gestao_pdcl_item IN ('.$pdcl_item_id.')');
    elseif ($os_id) $sql->adOnde('plano_acao_gestao_os IN ('.$os_id.')');
}
?>
<script type="text/javascript">

    function selecionar(plano_acao_id){
        var nome=document.getElementById('plano_acao_nome_'+plano_acao_id).innerHTML;
        setFechar(plano_acao_id, nome);
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

    function expandir_colapsar(campo){
        if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
        else document.getElementById(campo).style.display='';
    }
</script>	