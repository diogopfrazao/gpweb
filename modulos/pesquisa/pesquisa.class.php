<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';


if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

class pesquisa {
	public $tabela = null;
	public $tabela_apelido = null;
	public $tabela_modulo = null;
	public $tabela_chave = null;
	public $tabela_chave2 = null;
	public $tabela_link = null;
	public $tabela_link2 = null;
	public $tabela_titulo = null;
	public $tabela_ordem_por = null;
	public $tabela_extra = null;
	public $buscar_campos = array();
	public $mostrar_campos = array();
	public $tabela_unioes = array();
	public $palavraChave = null;
	public $palavrasChave = null;
	public $padraoTemporario = '';
	public $mostrar_valor = '';
	public $pesquisa_options = null;
	public $tabela_agruparPor = null;
	public $funcao = null;

	public function __construct() {
		return null;
		}

	public function pegarResultados( &$registro_contagem) {
		global $Aplic, $config;
		$q = $this->_construirConsulta();
		$resultados = null;
		if ($q) $resultados = $q->Lista();
		$textoSaida = '';
		if ($resultados) {
			$subregistro_contagem = 0;
			foreach ($resultados as $registros) {
				if ($Aplic->checarModulo($this->tabela_modulo, 'acesso')) {
					$registro_contagem += 1;
					$subregistro_contagem += 1;
					$ii = 0;
					$mostrar_valor = '';
					foreach ($this->mostrar_campos as $campos) {
						$ii++;
						if (isset($registros[$campos]) && $registros[$campos] && tem_chave($registros[$campos], $this->palavrasChave)) $mostrar_valor = $mostrar_valor.' '.$registros[$campos];
						}
					$tmplink = '';
					if (isset($this->tabela_link) && isset($this->tabela_chave)) $tmplink = $this->tabela_link.$registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)];
					if (isset($this->tabela_link2) && isset($this->tabela_chave2)) $tmplink = $this->tabela_link.$registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)].$this->tabela_link2.$registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave2)];

					if (($this->funcao=='registro') && isset($this->tabela_chave2)) $textoSaida .= '<tr><td>'.link_registro($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave2)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='mensagem') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_mensagem($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='organizacao') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_cia($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='departamento') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_dept($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='contato') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_contato($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)],'','','').'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='usuario') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_usuario($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)],'','','').'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
						
					elseif (($this->funcao=='tarefa') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_tarefa($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';
					elseif (($this->funcao=='projeto') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_projeto($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='perspectiva') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_perspectiva($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='tema') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_tema($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='objetivo') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_objetivo($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='fator') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_fator($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='estrategia') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_estrategia($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='meta') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_meta($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='pratica') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_pratica($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='indicador') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_indicador($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='acao') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_acao($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='canvas') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_canvas($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='risco') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_risco($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='risco_resposta') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_risco_resposta($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='calendario') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_calendario($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='monitoramento') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_monitoramento($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='ata') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_ata_pro($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='mswot') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_mswot($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='swot') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_swot($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='operativo') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_operativo($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='instrumento') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_instrumento($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='recurso') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_recurso($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='problema') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_problema($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='demanda') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_demanda($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='programa') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_programa($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='licao') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_licao($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='evento') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_evento($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='link') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_link($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='avaliacao') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_avaliacao($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='tgn') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_tgn($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='brainstorm') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_brainstorm($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='gut') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_gut($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='causa_efeito') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_causa_efeito($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='arquivo') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_arquivo($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='forum') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_forum($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif (($this->funcao=='checklist') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_checklist($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
						
					elseif ($Aplic->profissional && ($this->funcao=='agrupamento') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_agrupamento($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='patrocinador') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_patrocinador($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='template') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_template($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='painel') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_painel($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='painel_odometro') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_painel_odometro($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='painel_composicao') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_painel_composicao($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='tr') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_tr($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='me') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_me($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='plano_acao_item') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_acao_item($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='beneficio') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_beneficio($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='painel_slideshow') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_painel_slideshow($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
						
						
					elseif ($Aplic->profissional && ($this->funcao=='pdcl') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_pdcl($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='pdcl_item') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_pdcl_item($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
					elseif ($Aplic->profissional && ($this->funcao=='os') && isset($this->tabela_chave)) $textoSaida .= '<tr><td>'.link_os($registros[preg_replace('/^.*\.([^\.]+)$/', '$1', $this->tabela_chave)]).'<br>'.iluminar($mostrar_valor, $this->palavrasChave).'</a></span></td></tr>';	
									
					else $textoSaida .= '<tr><td><a href = "'.$tmplink.'">'.iluminar($mostrar_valor, $this->palavrasChave).'</a></td></tr>';
					}
				}
			$textoSaida = '<tr><th><b>'.(isset($config[$this->tabela_titulo])? ucfirst($config[$this->tabela_titulo]) : $this->tabela_titulo).' ('.$subregistro_contagem.')'.'</b></th></tr> '."\n".$textoSaida;
			}
		elseif ($this->opcoes_pesquisa['mostrar_vazio'] == 'on') $textoSaida = '<tr><th><b>'.$this->tabela_titulo.' (0)'.'</b></th></tr><tr><td>Vazio</td></tr>';
		return $textoSaida;
		}
	public function setPalavraChave( $chavew) {
		$this->palavraChave = $chavew;
		}
	public function setAvancado( $pesquisa_opts) {
		$this->opcoes_pesquisa = $pesquisa_opts;
		$this->palavrasChave = $pesquisa_opts['palavrasChave'];
		}
	public function _construirConsulta() {
		$q = new BDConsulta;
		if ($this->tabela_apelido) $q->adTabela($this->tabela, $this->tabela_apelido);
		else $q->adTabela($this->tabela);
		$q->adCampo($this->tabela_chave);
		if (isset($this->tabela_chave2)) $q->adCampo($this->tabela_chave2);
		foreach ($this->tabela_unioes as $unir)	$q->adUnir($unir['tabela'], $unir['apelido'], $unir['unir']);
		foreach ($this->mostrar_campos as $campos) $q->adCampo($campos);
		$q->adOrdem($this->tabela_ordem_por);
		if ($this->tabela_extra) $q->adOnde($this->tabela_extra);
		if ($this->tabela_agruparPor) $q->adGrupo($this->tabela_agruparPor);
		$sql = '';
		foreach (array_keys($this->palavrasChave) as $palavraChave) {
			$sql .= '(';
			foreach ($this->buscar_campos as $campo) {
				$or_palavrasChave = preg_split('/[\s,;]+/', $palavraChave);
				foreach ($or_palavrasChave as $or_palavraChave) {
					if ($this->opcoes_pesquisa['ignorar_caract_especial'] == 'on') {
						$padraoTemporario = dadoParaRegexp_utf8($or_palavraChave);
						if ($this->opcoes_pesquisa['ignorar_caixa'] == 'on') $sql .= ' '.$campo.' REGEXP \''.$padraoTemporario.'\' or ';
						else $sql .= ' '.$campo.' REGEXP BINARY \''.$padraoTemporario.'\' or ';
						}
					elseif ($this->opcoes_pesquisa['ignorar_caixa'] == 'on') $sql .= ' '.$campo.' LIKE "%'.$or_palavraChave.'%" or ';
					else $sql .= ' '.$campo.' LIKE BINARY "%'.$or_palavraChave.'%" or ';
					}
				}
			$sql = substr($sql, 0, -4);
			if ($this->opcoes_pesquisa['todas_palavras'] == 'on') $sql .= ') and ';
			else $sql .= ') or ';
			}
		$sql = substr($sql, 0, -4);
		if ($sql) {
			$q->adOnde($sql);
			return $q;
			}
		else return null;
		}
	}

function tem_chave($texto, $valorChave) {
	global $spesquisa;
	$txt = $texto;
	$corRealce = array('#FFFF66', '#ADD8E6', '#90EE8A', '#FF99FF');
	$chaves = array();
	if (!is_array($valorChave))	$chaves = array($valorChave);
	else $chaves = $valorChave;
	foreach ($chaves as $chave) {
		if (strlen($chave[0]) > 0) {
			$chave[0] = stripslashes($chave[0]);
			if (isset($spesquisa['ignorar_caract_especial']) && ($spesquisa['ignorar_caract_especial'] == 'on')) {
				if ($spesquisa['ignorar_caixa'] == 'on') $txt = preg_replace('/('.preg_quote(dadoParaRegexp_utf8($chave[0],'/')).')/i', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
				else $txt = preg_replace('/('.preg_quote(dadoParaRegexp_utf8($chave[0],'/')).')/', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
				}
			else if(!isset($spesquisa['ignorar_caract_especial']) || ($spesquisa['ignorar_caract_especial'] == '')) {
				if ($spesquisa['ignorar_caixa'] == 'on') $txt = preg_replace('/('.preg_quote($chave[0],'/').')/i', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
				else $txt = preg_replace('/('.preg_quote($chave[0],'/').')/', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt); 
				}
			else $txt = preg_replace('/('.preg_quote(sql_regcase($chave[0]),'/').')/i', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
			}
		}
	return ($txt!=$texto);
	}



function iluminar($texto, $valorChave) {
	global $spesquisa;
	$txt = $texto;
	$corRealce = array('#FFFF66', '#ADD8E6', '#90EE8A', '#FF99FF');
	$chaves = array();
	if (!is_array($valorChave))	$chaves = array($valorChave);
	else $chaves = $valorChave;
	foreach ($chaves as $chave) {
		if (strlen($chave[0]) > 0) {
			$chave[0] = stripslashes($chave[0]);

			if(isset($spesquisa['ignorar_caract_especial']) && ($spesquisa['ignorar_caract_especial'] == 'on')) {
				if($spesquisa['ignorar_caixa'] == 'on'){
                    $txt = preg_replace('/('.preg_quote(dadoParaRegexp_utf8($chave[0],'/')).')/i', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
                    }
				else{
                    $txt = preg_replace('/('.preg_quote(dadoParaRegexp_utf8($chave[0],'/')).')/', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
                    }
				}
			else if(!isset($spesquisa['ignorar_caract_especial']) || ($spesquisa['ignorar_caract_especial'] == '')) {
				if($spesquisa['ignorar_caixa'] == 'on'){
                    $txt = preg_replace('/('.preg_quote($chave[0],'/').')/i', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
                    }
				else{
                    $txt = preg_replace('/('.preg_quote($chave[0],'/').')/', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
                    }
				}
			else{
                $txt = preg_replace('/('.preg_quote(sql_regcase($chave[0],'/')).')/i', '<span style="background:'.$corRealce[$chave[1]].'" >\\0</span>', $txt);
                }
			}
		}
	return $txt;
	}

function dadoParaRegexp_utf8($input) {
	$resultado = '';
	for ($i = 0, $i_cmp = strlen($input); $i < $i_cmp; ++$i)
		switch ($input[$i]) {
			case 'A':
			case 'a':
				$resultado .= '(a|A!|A?|A?|A?)';
				break;
			case 'C':
			case 'c':
				$resultado .= '(c|??|?O)';
				break;
			case 'D':
			case 'd':
				$resultado .= '(d|??|Ď)';
				break;
			case 'E':
			case 'e':
				$resultado .= '(e|A?|ě|A?|Ě)';
				break;
			case 'I':
			case 'i':
				$resultado .= '(i|A?|A?)';
				break;
			case 'L':
			case 'l':
				$resultado .= '(l|?o|?3|?1|?1)';
				break;
			case 'N':
			case 'n':
				$resultado .= '(n|A^|A?)';
				break;
			case 'O':
			case 'o':
				$resultado .= '(o|A3|A?|A?|A?)';
				break;
			case 'R':
			case 'r':
				$resultado .= '(r|A?|A?|A?|A~)';
				break;
			case 'S':
			case 's':
				$resultado .= '(s|A!|A?)';
				break;
			case 'T':
			case 't':
				$resultado .= '(t|AY|A?)';
				break;
			case 'U':
			case 'u':
				$resultado .= '(u|Ao|A?|A?|A?)';
				break;
			case 'Y':
			case 'y':
				$resultado .= '(y|A1|A?)';
				break;
			case 'Z':
			case 'z':
				$resultado .= '(z|A3|A1)';
				break;
			default:
				$resultado .= $input[$i];
			}
	return $resultado;
	}
?>