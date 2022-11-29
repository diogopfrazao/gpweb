<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

echo '<ul>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/manual_gpweb.doc">Manual do '.$config['gpweb'].'</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/inicio02_preferencias.mp4">Prefer�ncias�de usu�rio (5,5�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/inicio03_organograma_e_secao.mp4">Organiza��es e se��es (10,54�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/inicio04_nivel_acesso_informacao.mp4">N�vel acesso da informa��o/  perfil de acesso (16,42�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/inicio05_atualizar_cadastro.mp4">Atualiza��o de cadastro (3,01�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/inicio06_expediente.mp4">Expediente (19,6�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica1.mp4">Gest�o Estrat�gica Aula 01 - Perspectiva estrat�gica� (5,66�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica2.mp4">Gest�o Estrat�gica Aula 02 �- Tema� (3,38�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica3.mp4">Gest�o Estrat�gica Aula 03 - Objetivo estrat�gico � (3,62�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica5.mp4">Gest�o Estrat�gica Aula 04 - Fatores cr�ticos de Sucesso (1,54�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica4.mp4">Gest�o Estrat�gica Aula 05 - Iniciativa (1,83�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica6.mp4">Gest�o Estrat�gica Aula 06 - Progresso - manual e autom�tico da gest�o (4,72�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/gestao_estrategica7.mp4">Gest�o Estrat�gica Aula 07 - Arvore de gest�o estrat�gica / mapa estrat�gico (18,36�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador01.mp4">Indicador Aula 01 - B�sico sobre indicadores� (25,18�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador02.mp4">Indicador Aula 02 - F�rmula de vari�veis (5,56�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador03.mp4">Indicador Aula 03 - F�rmula de indicadores (5,09�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador04.mp4">Indicador Aula 04 - Indicadores checklist (14,42�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador05.mp4">Indicador Aula 05 - Indicador com valor Automatico de projeto ou tarefa (8,05�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador06.mp4">Indicador Aula 06 - Indicador de composi��o (6,13�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador07_odometro.mp4">Indicador Aula 07 - Transformar indicadores em odometros (6,3�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador08_painel.mp4">Indicador Aula 08 - Pain�is de indicadores (8,32�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador09_composicao_paineis.mp4">Indicador Aula 09 - Composi��o de pain�is (3,48�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/indicador10_slideshow.mp4">Indicador Aula 10 - Slideshow de composi��o (2,94�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto01_eap_texto.mp4">Projeto Aula 01 - Estrutura analitica de projeto texto (6,03�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto02_eap_grafica.mp4">Projeto Aula 02 - Estrutura analitica de projeto grafica (4,99�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto03_gantt_interativo.mp4">Projeto Aula 03 - Grafico Gantt interativo (24,28�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto04_campos_projeto_tarefa.mp4">Projeto Aula 04 - Principais campos de projetos e tarefas (26,94�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto05_registro_ocorrencia.mp4">Projeto Aula 05 - Registro de ocorrencia (10,39�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto06_custo.mp4">Projeto Aula 06 - Custos e gastos no projeto� (13,62�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto07_mao_obra.mp4">Projeto Aula 07 - Trabalhar com m�o de obra (9,63�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto09_areas.mp4">Projeto Aula 08 - Inserir �reas no projeto com georreferenciamento (12,59�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto11_risco.mp4">Projeto Aula 09 - Risco e respostas ao risco (14,09�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto12_portfolio.mp4">Projeto Aula 10 - Portifolho de projetos (5,96�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto13_arquivo.mp4">Projeto Aula 11 - Arquivos (6,32�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto14_baseline.mp4">Projeto Aula 12 - Linha de base (5,38�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto15_demanda.mp4">Projeto Aula 13 - Ciclo de Demanda at� termo de abertura (7,96�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto16_eventos.mp4">Projeto Aula 14 - Eventos (reuni�es) (16,67�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto17_licao_aprendida.mp4">Projeto Aula 15 - Li��o aprendida (3,9�MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/projeto18_moeda_estrangera.mp4">Projeto Aula 16 - Moeda estrangeira (12,11 MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/matriz_swot.mp4">Matriz SWOT (6,76 MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/pendencia.mp4">Pend�ncias (4,32 MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/ata.mp4">Atas de Reuni�o (20,13 MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/aviso.mp4">M�dulo de Avisos (5,8 MB)</a></li>
<li><a href="'.get_protocol().'sistemagpweb.com.br/arquivos/video/campo_de_valores.mp4">Campo de Valores(19,29 MB)</a></li>
</ul>';

/*
echo '<ul>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/manual_gpweb.doc">Manual do '.$config['gpweb'].'</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/cron-tarefas-projetos-atrasados.mp4">Alerta de Tarefas-Projetos Atrasados</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/recurso.mp4">Aloca��o de recurso numa tarefa</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/baseline.mp4">Baseline</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/calendario.mp4">Calend�rio - b�sico</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/campos_formularios.mp4">Campos de�Formul�rios</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/projeto_editar.mp4">Campos do projeto</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/clonar_mover_tarefas.mp4">Clonar e Mover Tarefas</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/instalar-gpweb.mp4">Como Instalar o '.$config['gpweb'].'</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/homem_hora.mp4">Controle das horas trabalhadas por usu�rio</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/criar_arvore_gestao_estrategica_sem_indicador.mp4">Criar Arvore de gest�o Estrat�gica</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/avaliacao_indicadores.mp4">Criar avalia��es e indicadores</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/criar_recurso_alocar_tarefa.mp4">Criar e Alocar Recursos Monet�rio e Humano</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/IMPORTAR_PROJETOS_CRIAR_MODELOS.mp4">Criar Modelos e Clonar Projetos</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/eap_web.mp4">Criar projetos na EAP (estrutura anal�tica de projeto)</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/agil_web.mp4">Criar projetos na interface Gantt Interativa</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/customizar_valores_campos.mp4">Customizar Valores de Campos do Sistema</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/diagrama_causa_efeito.mp4">Diagrama de Causa e Efeito</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/forum.mp4">F�rum</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/PERFIS_DE_ACESSO_NOVO_AP_035.mp4">Gerenciamento de Perfis e Acesso</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/aula-paineis-composicao.mp4">Gr�fico em Paineis e Composi��o</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/importa_exporta_msproject.mp4">Importa e Exporta MSPROJECT</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/indicador - odometro.mp4">Indicador Od�metro</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/indicador_check_list_e_principal.mp4">Indicador tipo CheckList e�Principal</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/indicador_composicao_e_principal.mp4">Indicador tipo�Composi��o e Principal</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/indicador_basico.mp4">Indicadores - b�sico</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/lacunas_pautas.mp4">Lacunas de Indicadores</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/lembretes.mp4">Lembretes</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/links.mp4">Links</a></li>

<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/MAPA_ESTRATEGICO.mp4">Mapa estrat�gico</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/MATRIZ_GUT.mp4">Matriz GUT</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/msg_projeto.mp4">Mensagens vinculadas a projetos</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/mensagens.mp4">Mensagens</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/indicador_meta_progressiva.wmv">Meta Progressiva em Indicador</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/gantt.mp4">Novo gr�fico Gantt interativo</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/planilha_custo.mp4">Planilha de custos da tarefa</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/nova_org_pontuacao_arvore_hierarquica.mp4">Pontua��o em Forma de �rvore Hier�rquica</a></li>
<li><a href="'.get_protocol().'www.sistemagpweb.com/arquivos/portifolio.mp4">Portf�lio</a></li>
</ul>';
*/
?>

