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
global $m, $a, $u;

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if (!$dialogo) $Aplic->salvarPosicao();

$usuario_id=$Aplic->usuario_id;

$projeto_status_aguardando = 4;
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TabParaFazerTarefa', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TabParaFazerTarefa') !== null ? $Aplic->getEstado('TabParaFazerTarefa') : 0;

//$evento_filtro = $Aplic->getEstado('IdxFiltro' , $Aplic->usuario_prefs['filtroevento']);
if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('IdxDept', getParam($_REQUEST, 'dept_id', null));

$escolhe_projeto='';

//$evento_filtro_lista = array('meu' => 'Meus eventos', 'dono' => 'Eventos que eu criei', 'todos' => 'Todos os eventos');
$evento_filtro='todos';

$sql = new BDConsulta;

if (!isset($ver_min) || !$ver_min) {
	$botoesTitulo = new CBlocoTitulo('A Fazer', 'afazer.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	}
if ($a == 'parafazer') {
	$podeAcessar_email=$Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso');
	$podeAcessar_calendario=$Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso');
	$podeAcessar_tarefas=$Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso');
	$podeAcessar_praticas=$Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso');
	$caixaTab = new CTabBox('m=tarefas&a=parafazer', '', $tab);
	
	
	$total=0;

	//quantidade Eventos
	$sql->adTabela('eventos', 'e');
	$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
	$sql->adOnde('(evento_dono IN ('.$Aplic->usuario_lista_grupo.') OR (evento_participante_usuario IN ('.$Aplic->usuario_lista_grupo.') AND (evento_participante_aceito=1)))');		
	$sql->adOnde('evento_fim >= \''.date('Y-m-d H:i:s').'\'');
	$sql->adCampo('count(DISTINCT e.evento_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	$evento_filtro='todos_aceitos';
	if ($podeAcessar_calendario && $qnt) {
		if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/evento_lista_idx_pro', 'Eventos ('.$qnt.')',null,null,'Eventos','Visualizar os eventos em que esteja envolvido.');
		else $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.eventos', 'Eventos ('.$qnt.')',null,null,'Eventos','Visualizar os eventos em que esteja envolvido.');
		$total++;
		}

	$sql->adTabela('eventos', 'e');
	$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
	$sql->adOnde('evento_dono NOT IN ('.$Aplic->usuario_lista_grupo.') AND (evento_participante_usuario IN ('.$Aplic->usuario_lista_grupo.') AND evento_participante_aceito=0)');		
	$sql->adOnde('evento_fim >= \''.date('Y-m-d H:i:s').'\'');
	$sql->adCampo('count(DISTINCT e.evento_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	//$evento_filtro='todos_pendentes';
	if ($podeAcessar_calendario && $qnt) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/calendario/convite', '<span id="qnt_confirmar">Confirmar ('.$qnt.')</span>',null,null,'Confirmar Eventos','Visualizar os eventos em que se necessite confirmar presença.');
		$total++;
		}	
		
		
		
	$sql->adTabela('agenda');
	$sql->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = agenda.agenda_id');
	$sql->adOnde('(agenda_dono IN ('.$Aplic->usuario_lista_grupo.') OR (agenda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND (agenda_usuarios.aceito=1 || agenda_usuarios.aceito=0)))');		
	$sql->adOnde('agenda_inicio >= \''.date('Y-m-d H:i:s').'\'');
	$sql->adCampo('count(DISTINCT agenda.agenda_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	if ($podeAcessar_email && $qnt) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.compromissos', 'Compromissos ('.$qnt.')',null,null,'Compromissos','Visualizar os compromissos em que esteja envolvido.');
		$total++;
		}
	
	if ($podeAcessar_tarefas){
		$sql->adTabela('tarefas', 'ta');
		$sql->esqUnir('projetos', 'pr','pr.projeto_id=tarefa_projeto');
		$sql->esqUnir('tarefa_designados', 'td','td.tarefa_id = ta.tarefa_id');
		$sql->adCampo('count(DISTINCT ta.tarefa_id)');
		$sql->adOnde('projeto_template = 0 OR projeto_template IS NULL');
		$sql->adOnde('ta.tarefa_percentagem < 100 OR ta.tarefa_percentagem IS NULL');
		$sql->adOnde('projeto_ativo = 1');
		$sql->adOnde('td.usuario_id IN ('.$Aplic->usuario_lista_grupo.') OR tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/parafazer_tarefas_sub', ucfirst($config['tarefas']).' ('.$qnt.')',null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

    if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) {
        $sql->adTabela('demandas');
        $sql->adCampo('count(DISTINCT demandas.demanda_id)');

        $sql->esqUnir('demanda_usuarios', 'demanda_usuarios', 'demanda_usuarios.demanda_id = demandas.demanda_id');
        $sql->adOnde('(demanda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') OR demanda_usuario IN ('.$Aplic->usuario_lista_grupo.') OR demanda_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR demanda_autoridade IN ('.$Aplic->usuario_lista_grupo.'))');

        $sql->adOnde('demanda_caracteristica_projeto IS NULL OR demanda_caracteristica_projeto=0');
        $sql->adOnde('demanda_projeto IS NULL OR demanda_projeto=0');
        $sql->adOnde('demanda_ativa=1');

        $qnt = $sql->Resultado();
        $sql->limpar();

        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/demanda_tabela', 'Demandas ('.$qnt.')',null,null, 'Demandas','Visualizar as demandas não analisadas que seja responsável, supervisor, autoridade ou foi designado.');
            $total++;
            }
        }

    if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso', null, 'programa')) {
        $sql->adTabela('programa');
        $sql->adCampo('count(DISTINCT programa.programa_id)');

        $sql->esqUnir('programa_usuario', 'programa_usuarios', 'programa_usuarios.programa_usuario_programa = programa.programa_id');
        $sql->adOnde('(programa_usuarios.programa_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') OR programa_usuario IN ('.$Aplic->usuario_lista_grupo.'))');

        $sql->adOnde('programa_percentagem < 100');
        $sql->adOnde('programa_ativo=1');

        $qnt = $sql->Resultado();
        $sql->limpar();

        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/programa_pro_ver_idx', ucfirst($config['programa']).' ('.$qnt.')',null,null, ucfirst($config['programa']),'Visualizar '.$config['genero_programa'].'s '.$config['programas'].' não analisadas que seja responsável ou foi designado.');
            $total++;
        }
    }

    if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso', null, 'beneficio')) {
        $sql->adTabela('beneficio');
        $sql->adCampo('count(DISTINCT beneficio.beneficio_id)');

        $sql->esqUnir('beneficio_usuario', 'beneficio_usuarios', 'beneficio_usuarios.beneficio_usuario_beneficio = beneficio.beneficio_id');
        $sql->adOnde('(beneficio_usuarios.beneficio_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') OR beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.'))');

        $sql->adOnde('beneficio_percentagem < 100');
        $sql->adOnde('beneficio_ativo=1');

        $qnt = $sql->Resultado();
        $sql->limpar();

        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/beneficio_pro_ver_idx', ucfirst($config['beneficio']).' de '.ucfirst($config['programa']).' ('.$qnt.')',null,null, ucfirst($config['beneficio']).' do '.ucfirst($config['programa']),'Visualizar '.$config['genero_beneficio'].'s '.$config['beneficios'].' dos '.$config['programas'].' não analisadas que seja responsável ou foi designado.');
            $total++;
        }
    }
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) {
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		$sql->esqUnir('pratica_indicador_usuarios','pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) { 
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores ('.$qnt.')',null,null,'Indicadores','Visualizar os indicadores que seja responsável ou foi designado.');
			$total++;
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) {
		$sql->adTabela('praticas');
		$sql->esqUnir('pratica_usuarios', 'pratica_usuarios', 'pratica_usuarios.pratica_id=praticas.pratica_id');
		$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pratica_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('pratica_ativa=1');
		$sql->adCampo('count(DISTINCT praticas.pratica_id)');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_praticas', ucfirst($config['praticas']).' ('.$qnt.')',null,null,ucfirst($config['praticas']),'Visualizar '.$config['genero_pratica'].'s '.$config['praticas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'perspectiva')) {
		$sql->adTabela('perspectivas');
		$sql->esqUnir('perspectivas_usuarios', 'perspectivas_usuarios', 'perspectivas_usuarios.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
		$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.') OR perspectivas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('pg_perspectiva_percentagem < 100');
        $sql->adOnde('pg_perspectiva_ativo=1');
		$sql->adCampo('count(DISTINCT perspectivas.pg_perspectiva_id)');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/perspectivas_ver_idx', ucfirst($config['perspectiva']).' ('.$qnt.')',null,null,ucfirst($config['perspectivas']),'Visualizar '.$config['genero_perspectiva'].'s '.$config['perspectivas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'tema')) {
		$sql->adTabela('tema');
		$sql->esqUnir('tema_usuarios', 'tema_usuarios', 'tema_usuarios.tema_id=tema.tema_id');
		$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.') OR tema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('tema_percentagem < 100');
        $sql->adOnde('tema_ativo=1');
		$sql->adCampo('count(DISTINCT tema.tema_id)');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tema_ver_idx', ucfirst($config['tema']).' ('.$qnt.')',null,null,ucfirst($config['temas']),'Visualizar '.$config['genero_tema'].'s '.$config['temas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'objetivo')) {
		$sql->adTabela('objetivo');
		$sql->esqUnir('objetivo_usuario', 'objetivo_usuario', 'objetivo_usuario.objetivo_usuario_objetivo=objetivo.objetivo_id');
		$sql->adOnde('objetivo_usuario IN ('.$Aplic->usuario_lista_grupo.') OR objetivo_usuario.objetivo_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('objetivo_percentagem < 100');
        $sql->adOnde('objetivo_ativo=1');
		$sql->adCampo('count(DISTINCT objetivo.objetivo_id)');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/obj_estrategicos_ver_idx', ucfirst($config['objetivo']).' ('.$qnt.')',null,null,ucfirst($config['objetivos']),'Visualizar '.$config['genero_objetivo'].'s '.$config['objetivos'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

    if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) {
        $sql->adTabela('me');
        $sql->esqUnir('me_usuario', 'me_usuario', 'me_usuario.me_usuario_me=me.me_id');
        $sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.') OR me_usuario.me_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('me_percentagem < 100');
        $sql->adOnde('me_ativo=1');
        $sql->adCampo('count(DISTINCT me.me_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/me_ver_idx_pro', ucfirst($config['me']).' ('.$qnt.')',null,null,ucfirst($config['mes']),'Visualizar '.$config['genero_me'].'s '.$config['mes'].' que seja responsável ou foi designado.');
            $total++;
            }
        }

    if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) {
        $sql->adTabela('fator');
        $sql->esqUnir('fator_usuario', 'fator_usuario', 'fator_usuario.fator_usuario_fator=fator.fator_id');
        $sql->adOnde('fator_usuario IN ('.$Aplic->usuario_lista_grupo.') OR fator_usuario.fator_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('fator_percentagem < 100');
        $sql->adOnde('fator_ativo=1');
        $sql->adCampo('count(DISTINCT fator.fator_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/fatores_ver_idx', ucfirst($config['fator']).' ('.$qnt.')',null,null,ucfirst($config['fator']),'Visualizar '.$config['genero_fator'].'s '.$config['fatores'].' que seja responsável ou foi designado.');
            $total++;
            }
        }
    
    if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'iniciativa')) {
        $sql->adTabela('estrategias');
        $sql->esqUnir('estrategias_usuarios', 'estrategias_usuarios', 'estrategias_usuarios.pg_estrategia_id=estrategias.pg_estrategia_id');
        $sql->adOnde('pg_estrategia_usuario IN ('.$Aplic->usuario_lista_grupo.') OR estrategias_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('pg_estrategia_percentagem < 100');
        $sql->adOnde('pg_estrategia_ativo=1');
        $sql->adCampo('count(DISTINCT estrategias.pg_estrategia_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx', ucfirst($config['iniciativa']).' ('.$qnt.')',null,null,ucfirst($config['iniciativa']),'Visualizar '.$config['genero_iniciativa'].'s '.$config['iniciativas'].' que seja responsável ou foi designado.');
            $total++;
            }
        }

    if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'meta')) {
        $sql->adTabela('metas');
        $sql->esqUnir('metas_usuarios', 'metas_usuarios', 'metas_usuarios.pg_meta_id=metas.pg_meta_id');
        $sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR metas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('pg_meta_percentagem < 100');
        $sql->adOnde('pg_meta_ativo=1');
        $sql->adCampo('count(DISTINCT metas.pg_meta_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', ucfirst($config['metas']).' ('.$qnt.')',null,null,ucfirst($config['metas']),'Visualizar '.$config['genero_meta'].'s '.$config['metas'].' que seja responsável ou foi designado.');
            $total++;
            }
        }

    if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'planejamento')) {
        $sql->adTabela('plano_gestao');
        $sql->esqUnir('plano_gestao_usuario', 'plano_gestao_usuario', 'plano_gestao_usuario.plano_gestao_usuario_plano=plano_gestao.pg_id');
        $sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.') OR plano_gestao_usuario.plano_gestao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('pg_percentagem < 100');
        $sql->adOnde('pg_ativo=1');
        $sql->adCampo('count(DISTINCT plano_gestao.pg_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gestao/gestao_tabela', 'Planejamento estratégico ('.$qnt.')',null,null,'Planejamento estratégico','Visualizar os planejamentos estratégicos que seja responsável ou foi designado.');
            $total++;
            }
        }
	
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {
		$sql->adTabela('plano_acao');
		$sql->adCampo('count(DISTINCT plano_acao.plano_acao_id) as soma');
		$sql->esqUnir('plano_acao_usuario', 'plano_acao_usuario', 'plano_acao_usuario_acao = plano_acao.plano_acao_id');
		$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR plano_acao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('plano_acao_percentagem < 100');
		$sql->adOnde('plano_acao_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']).' ('.$qnt.')',null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' que seja responsável ou foi designado.');
			$total++;
			}
		$sql->adTabela('plano_acao_item');
		$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
		$sql->adCampo('count(DISTINCT plano_acao_item.plano_acao_item_id) as soma');
		$sql->esqUnir('plano_acao_item_usuario', 'plano_acao_item_usuario', 'plano_acao_item_usuario_item = plano_acao_item.plano_acao_item_id');
		$sql->adOnde('plano_acao_item_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR plano_acao_item_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('plano_acao_item_percentagem < 100');
		$sql->adOnde('plano_acao_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt){ 
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_itens_idx', 'Itens de '.ucfirst($config['acoes']).' ('.$qnt.')',null,null,'Itens de '.ucfirst($config['acoes']),'Visualizar os itens de '.$config['genero_acao'].'s '.$config['acoes'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela('ata_acao');
		$sql->esqUnir('ata','ata','ata_acao_ata = ata.ata_id');
		$sql->adCampo('count(DISTINCT ata_acao.ata_acao_id)');
		$sql->esqUnir('ata_acao_usuario','ata_acao_usuario','ata_acao_usuario_acao=ata_acao.ata_acao_id');
	 	$sql->adOnde('ata_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR ata_acao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('ata_acao_percentagem < 100');
		$sql->adOnde('ata_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/atas/acao_tabela', 'Ações de Atas'.' ('.$qnt.')',null,null,'Ações de Atas de Reunião','Visualizar as ações de atas de reunião que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema');
		$sql->adCampo('count(DISTINCT problema.problema_id)');
		$sql->esqUnir('problema_usuarios','problema_usuarios','problema_usuarios.problema_id=problema.problema_id');
		$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('problema_percentagem < 100');
		$sql->adOnde('problema_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']).' ('.$qnt.')',null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

    if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) {
        $sql->adTabela('risco', 'risco');
        $sql->adCampo('count(DISTINCT risco.risco_id)');
        $sql->esqUnir('risco_usuarios','risco_usuarios', 'risco_usuarios.risco_id=risco.risco_id');
        $sql->adOnde('risco.risco_usuario IN ('.$Aplic->usuario_lista_grupo.') OR risco_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');

        $sql->adOnde('risco_percentagem < 100');
        $sql->adOnde('risco_ativo=1');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['risco']).' ('.$qnt.')',null,null,ucfirst($config['risco']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' que seja responsável ou foi designado.');
            $total++;
            }
        }

    if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco_resposta')) {
        $sql->adTabela('risco_resposta', 'risco_resposta');
        $sql->adCampo('count(DISTINCT risco_resposta.risco_resposta_id)');

        $sql->esqUnir('risco_resposta_usuarios','risco_resposta_usuarios', 'risco_resposta_usuarios.risco_resposta_id=risco_resposta.risco_resposta_id');
        $sql->adOnde('risco_resposta.risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.') OR risco_resposta_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');

        $sql->adOnde('risco_resposta_percentagem < 100');
        $sql->adOnde('risco_resposta_ativo=1');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_resposta_pro_ver_idx', ucfirst($config['risco_resposta']).' ('.$qnt.')',null,null,ucfirst($config['risco_resposta']),'Visualizar '.$config['genero_risco_resposta'].'s '.$config['risco_respostas'].' que seja responsável ou foi designado.');
            $total++;
            }
        }
	
	if ($Aplic->profissional) {
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
		$sql->adOnde('assinatura_data IS NULL');
		$sql->adOnde('assinatura_uuid IS NULL');
		$sql->adOnde('assinatura_bloqueado!=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_assinaturas_pro', 'Assinaturas'.' ('.$qnt.')',null,null,'Assinaturas','Visualizar os módulos em que necessita ainda aprovar.');
			$total++;
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso')) {
		$sql->adTabela('tr');
		$sql->adCampo('count(DISTINCT tr.tr_id)');
		$sql->esqUnir('tr_usuario', 'tr_usuario', 'tr_usuario_tr = tr.tr_id');
		$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR tr_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('tr_percentagem < 100');
		$sql->adOnde('tr_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/tr/tr_tabela', ucfirst($config['trs']).' ('.$qnt.')',null,null,ucfirst($config['trs']),'Visualizar '.$config['genero_tr'].'s '.$config['trs'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->profissional && $Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'acesso')) {
		$sql->adTabela('instrumento');
		$sql->adCampo('count(DISTINCT instrumento.instrumento_id)');
		$sql->esqUnir('instrumento_designados','instrumento_designados','instrumento_designados.instrumento_id=instrumento.instrumento_id');
		$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR instrumento_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('instrumento_porcentagem < 100');
		$sql->adOnde('instrumento_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/instrumento/instrumento_lista_idx', ucfirst($config['instrumentos']).' ('.$qnt.')',null,null,ucfirst($config['instrumentos']),'Visualizar '.$config['genero_instrumento'].'s '.$config['instrumentos'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

	if ($Aplic->profissional && $Aplic->modulo_ativo('os') && $Aplic->checarModulo('os', 'acesso')) {
		$sql->adTabela('os');
		$sql->adCampo('count(DISTINCT os.os_id) as soma');
		$sql->esqUnir('os_usuario', 'os_usuario', 'os_usuario_os = os.os_id');
		$sql->adOnde('os_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR os_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('os_percentagem < 100');
		$sql->adOnde('os_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/os/os_tabela', ucfirst($config['oss']).' ('.$qnt.')',null,null,ucfirst($config['oss']),'Visualizar '.$config['genero_os'].'s '.$config['oss'].' que seja responsável ou foi designado.');
			$total++;
			}
		}

    if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso', null, null)) {
        $sql->adTabela('ssti');
        $sql->esqUnir('ssti_usuario', 'ssti_usuario', 'ssti_usuario.ssti_usuario_ssti=ssti.ssti_id');
        $sql->adOnde('ssti_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR ssti_usuario.ssti_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('ssti_percentagem < 100');
        $sql->adOnde('ssti_ativo=1');
        $sql->adCampo('count(DISTINCT ssti.ssti_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/ssti/ssti_tabela', ucfirst($config['ssti']).' ('.$qnt.')',null,null,ucfirst($config['ssti']),'Visualizar '.$config['genero_ssti'].'s '.$config['sstis'].' que seja responsável ou foi designado.');
            $total++;
            }
        }

    if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso', null, 'laudo')) {
        $sql->adTabela('laudo');
        $sql->esqUnir('laudo_usuario', 'laudo_usuario', 'laudo_usuario.laudo_usuario_laudo=laudo.laudo_id');
        $sql->adOnde('laudo_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR laudo_usuario.laudo_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('laudo_percentagem < 100');
        $sql->adOnde('laudo_ativo=1');
        $sql->adCampo('count(DISTINCT laudo.laudo_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/ssti/laudo_tabela', ucfirst($config['laudo']).' ('.$qnt.')',null,null,ucfirst($config['laudo']),'Visualizar '.$config['genero_laudo'].'s '.$config['laudos'].' que seja responsável ou foi designado.');
            $total++;
            }
        }
    if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('pdcl', 'acesso', null, null)) {
        $sql->adTabela('pdcl');
        $sql->esqUnir('pdcl_usuario', 'pdcl_usuario', 'pdcl_usuario.pdcl_usuario_pdcl=pdcl.pdcl_id');
        $sql->adOnde('pdcl_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pdcl_usuario.pdcl_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
        $sql->adOnde('pdcl_percentagem < 100');
        $sql->adOnde('pdcl_ativo=1');
        $sql->adCampo('count(DISTINCT pdcl.pdcl_id)');
        $qnt = $sql->Resultado();
        $sql->limpar();
        if ($qnt) {
            $caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_ver_idx', ucfirst($config['pdcl']).' ('.$qnt.')',null,null,ucfirst($config['pdcl']),'Visualizar '.$config['genero_pdcl'].'s '.$config['pdcls'].' que seja responsável ou foi designado.');
            $total++;
            }
        }

	if ($total) $caixaTab->mostrar('','','','',true);
	else {
		
	echo estiloTopoCaixa();
	echo '<table cellspacing=1 cellpadding=0 width="100%" class="std"><tr><td>Não há nada a ser feito</td></tr></table>';
		}
	echo '</td></tr></table>';
	}
else include BASE_DIR.'/modulos/tarefas/parafazer_tarefas_sub.php';
if ($m !='calendario') echo estiloFundoCaixa();




?>
<script type="text/javascript">

function popLog(tarefa_id) {
	if(window.parent && window.parent.gpwebApp)	window.parent.gpwebApp.popUp('Registro',800, 465,'m=tarefas&a=ver_log_atualizar&dialogo=1&tarefa_id='+tarefa_id,window.retornoLog, window);
	else window.open('./index.php?m=tarefas&a=ver_log_atualizar&dialogo=1&tarefa_id='+tarefa_id, 'Registro','height=322,width=800px,resizable,scrollbars=no');
	}

function retornoLog(update){
	if(update){
		url_passar(false,'m=tarefas&a=parafazer');
	}
}

function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	document.escolherFiltro.submit();
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>