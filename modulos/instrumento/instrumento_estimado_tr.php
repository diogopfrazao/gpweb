<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


if (!$podeEditar && $instrumento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

$instrumento_id=getParam($_REQUEST, 'instrumento_id', 0);

$sql = new BDConsulta;
$obj = new CInstrumento();
$obj->load($instrumento_id);

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$obj->instrumento_cia.'" />';

if (!$dialogo) {
	$botoesTitulo = new CBlocoTitulo('Planilha de Custos Estimados ('.$config['tr'].') - '.nome_instrumento($instrumento_id), '../../../modulos/instrumento/imagens/os.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","voltar",dica(ucfirst($config['instrumento']),'Retornar aos detalhes d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_ver&instrumento_id=".$instrumento_id."\");");
	$km->Add("root","imprimir",dica('Imprimir a Planilha', 'Clique neste ícone '.imagem('icones/imprimir_p.png').' para imprimir a planilha.').imagem('icones/imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=instrumento&a=instrumento_estimado_tr&dialogo=1&instrumento_id=".$instrumento_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}

echo '<table cellpadding=0 cellspacing=0 '.($dialogo ? 'width=1080' : 'class="std" width="100%"').' >';



if ($dialogo) echo '<tr><td align=center><h1>Planilha de Custos ('.$config['tr'].')<br>'.nome_instrumento($instrumento_id).'</h1></td></tr>';


if (!$dialogo) {
	echo '<tr><td colspan="20">'.botao('selecionar '.$config['tr'], 'Selecionar '.ucfirst($config['tr']), 'Selecione '.($config['genero_tr']=='a' ? 'uma ': 'um ').$config['tr'].' para importar a planilha de custos estimados.','','popSelecionarTR()').'</td></tr>';
	echo '<tr><td id="nome_custo_display" style="font-size: 1.5em; font-weight: bold; text-align: center; padding-bottom: 5px;" colspan="20"></td></tr>';
	echo '<tr><td colspan=20><div id="itens_custo" style="display: none">';
	
	echo '<table width="100%" cellpadding=2 cellspacing=0 class="tbl1">';
	
	echo '<thead><th width=16><input type="checkbox" id="sel_todas" value="1" onclick="marca_sel_todas();"></th>';
	echo '<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>';
	echo '<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>';
	echo '<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>';
	echo '<th>'.dica('Valor Unitário Atualizado', 'O valor de uma unidade do item atualizado.').'Unit. Atual'.dicaF().'</th>';
	echo '<th>'.dica('Quantidade', 'A quantidade do ítem').'Qnt.'.dicaF().'</th>';
	echo '<th>'.dica('Quantidade de meses', 'A quantidade de meses de serviço do ítem').'Meses'.dicaF().'</th>';
	if($config['bdi']) echo '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>';

	echo '<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>';
	echo '<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>';
	echo '<th>'.dica('Data Limite', 'A data limite para receber o material ou serviço com oportunidade.').'Data'.dicaF().'</th>';
	echo '</thead>';

	echo '<tbody id="lista_itens_custo"></tbody>';

	echo '</table>';

	echo '</div></td></tr>';
	echo '<tr id="importar" style="display:none"><td colspan=20>'.botao('incluir', 'Incluir Planilha de Custo Estimado ', 'Acrescente as planilhas de custos estimados selecionadas.','','importar()').'</td></tr>';
	}

$sql->adTabela('instrumento_custo', 'instrumento_custo');
$sql->esqUnir('tr_custo', 'trcusto', 'trcusto.tr_custo_id = instrumento_custo.instrumento_custo_tr');
$sql->esqUnir('tr', 'tr', 'tr.tr_id = trcusto.tr_custo_tr');

$sql->esqUnir('tr_avulso_custo', 'travulsocusto', 'travulsocusto.tr_avulso_custo_id = trcusto.tr_custo_avulso');
$sql->esqUnir('tarefa_custos', 'tarefacusto', 'tarefacusto.tarefa_custos_id = trcusto.tr_custo_tarefa');
$sql->esqUnir('demanda_custo', 'demandacusto', 'demandacusto.demanda_custo_id = trcusto.tr_custo_demanda');


//responsável custo tr avulso
$sql->esqUnir('usuarios', 'utravulso', 'utravulso.usuario_id = travulsocusto.tr_avulso_custo_usuario');
$sql->esqUnir('contatos', 'ctravulso', 'ctravulso.contato_id = utravulso.usuario_contato');
$sql->adCampo('concatenar_tres(ctravulso.contato_posto, \' \', ctravulso.contato_nomeguerra) AS responsavel_travulsocusto');

//responsável custo tarefa
$sql->esqUnir('usuarios', 'utarefacusto', 'utarefacusto.usuario_id = tarefacusto.tarefa_custos_usuario');
$sql->esqUnir('contatos', 'ctarefacusto', 'ctarefacusto.contato_id = utarefacusto.usuario_contato');
$sql->adCampo('concatenar_tres(ctarefacusto.contato_posto, \' \', ctarefacusto.contato_nomeguerra) AS responsavel_tarefacusto');

//responsável custo demanda
$sql->esqUnir('usuarios', 'udemandacusto', 'udemandacusto.usuario_id = demandacusto.demanda_custo_usuario');
$sql->esqUnir('contatos', 'cdemandacusto', 'cdemandacusto.contato_id = udemandacusto.usuario_contato');
$sql->adCampo('concatenar_tres(cdemandacusto.contato_posto, \' \', cdemandacusto.contato_nomeguerra) AS responsavel_demandacusto');

$sql->adCampo('instrumento_custo_id, instrumento_custo_quantidade');
$sql->adCampo('tr_id, tr_nome');
$sql->adCampo('tr_custo_id, tr_custo_avulso, tr_custo_tarefa, tr_custo_demanda, tr_custo_quantidade');
$sql->adCampo('tr_avulso_custo_nome, tr_avulso_custo_descricao, tr_avulso_custo_nd, tr_avulso_custo_custo, tr_avulso_custo_custo_atual, tr_avulso_custo_bdi, tr_avulso_custo_servico, tr_avulso_custo_meses, tr_avulso_custo_moeda, tr_avulso_custo_data_limite, tr_avulso_custo_categoria_economica, tr_avulso_custo_grupo_despesa, tr_avulso_custo_modalidade_aplicacao');
$sql->adCampo('tarefa_custos_nome, tarefa_custos_descricao, tarefa_custos_nd, tarefa_custos_custo, tarefa_custos_bdi, tarefa_custos_moeda, tarefa_custos_data_limite, tarefa_custos_categoria_economica, tarefa_custos_grupo_despesa, tarefa_custos_modalidade_aplicacao');
$sql->adCampo('demanda_custo_nome, demanda_custo_descricao, demanda_custo_nd, demanda_custo_data_limite, demanda_custo_custo, demanda_custo_bdi, demanda_custo_moeda, demanda_custo_categoria_economica, demanda_custo_grupo_despesa, demanda_custo_modalidade_aplicacao');
$sql->adOnde('instrumento_custo.instrumento_custo_tr IS NOT NULL');
$sql->adOnde('instrumento_custo.instrumento_custo_instrumento='.(int)$instrumento_id);
$sql->adOrdem('tr_id, instrumento_custo_ordem');
$lista=$sql->Lista();
$sql->limpar();


echo '<tr><td colspan=20><div id="combo_custo">';
echo '<table width="100%" cellpadding=2 cellspacing=0 class="tbl1">';

echo '<thead>';
echo '<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>';
echo '<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>';
echo '<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>';
echo '<th>'.dica('Valor Unitário Atualizado', 'O valor de uma unidade do item atualizado.').'Unit. Atual'.dicaF().'</th>';
echo '<th>'.dica('Quantidade', 'A quantidade do ítem').'Qnt.'.dicaF().'</th>';
echo '<th>'.dica('Quantidade de meses', 'A quantidade de meses de serviço do ítem').'Meses'.dicaF().'</th>';
if($config['bdi']) echo '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>';
echo '<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>';
echo '<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>';
echo '<th>'.dica('Data Limite', 'A data limite para receber o material ou serviço com oportunidade.').'Data'.dicaF().'</th>';

echo (!$dialogo ? '<th></th>' : '').'</tr></thead>';

echo '<tbody id="instrumento_lista_itens">';

if(!empty($lista)) {
    $tem_total = false;

    $numeroLinha = 0;
    $trAtual = null;
    foreach( $lista as $linha ) {
        $quantidade = (float) $linha['instrumento_custo_quantidade'];

        if( (int) $linha[ 'tr_custo_avulso' ] ) {
          $tipo = 'travulso';
          $nome = $linha[ 'tr_avulso_custo_nome' ];
          $descricao = $linha[ 'tr_avulso_custo_descricao' ];
          $valorUnitario = (float) $linha[ 'tr_avulso_custo_custo' ];
          $valorUnitarioAtual = (float) $linha[ 'tr_avulso_custo_custo_atual' ];
          $bdi = (float) $linha[ 'tr_avulso_custo_bdi' ];
          $data = new CData($linha['tr_avulso_custo_data_limite']);
          $nd = $linha[ 'tr_avulso_custo_nd' ];
          $categoriaEconomica = $linha[ 'tr_avulso_custo_categoria_economica' ];
          $grupoDespesa = $linha[ 'tr_avulso_custo_grupo_despesa' ];
          $modalidadeAplicacao = $linha[ 'tr_avulso_custo_modalidade_aplicacao' ];
          $moeda = $linha[ 'tr_avulso_custo_moeda' ];
          $responsavel = $linha[ 'responsavel_travulsocusto' ];
          $quantidadeMeses = $linha[ 'tr_avulso_custo_servico' ] == 1 ? (float) $linha[ 'tr_avulso_custo_meses' ] : null;
        	}
        else if( (int) $linha[ 'tr_custo_tarefa' ] ) {
          $tipo = 'tarefa';
          $nome = $linha[ 'tarefa_custos_nome' ];
          $descricao = $linha[ 'tarefa_custos_descricao' ];
          $valorUnitario = (float) $linha[ 'tarefa_custos_custo' ];
          $valorUnitarioAtual = null;
          $bdi = (float) $linha[ 'tarefa_custos_bdi' ];
          $data = new CData($linha['tarefa_custos_data_limite']);
          $nd = $linha[ 'tarefa_custos_nd' ];
          $categoriaEconomica = $linha[ 'tarefa_custos_categoria_economica' ];
          $grupoDespesa = $linha[ 'tarefa_custos_grupo_despesa' ];
          $modalidadeAplicacao = $linha[ 'tarefa_custos_modalidade_aplicacao' ];
          $moeda = $linha[ 'tarefa_custos_moeda' ];
          $responsavel = $linha[ 'responsavel_tarefacusto' ];
          $quantidadeMeses = null;
        	}
        else { //demanda
          $tipo = 'demanda';
          $nome = $linha[ 'demanda_custo_nome' ];
          $descricao = $linha[ 'demanda_custo_descricao' ];
          $valorUnitario = (float) $linha[ 'demanda_custo_custo' ];
          $valorUnitarioAtual = null;
          $bdi = (float) $linha[ 'demanda_custo_bdi' ];
          $data = new CData($linha['demanda_custo_data_limite']);
          $nd = $linha[ 'demanda_custo_nd' ];
          $categoriaEconomica = $linha[ 'demanda_custo_categoria_economica' ];
          $grupoDespesa = $linha[ 'demanda_custo_grupo_despesa' ];
          $modalidadeAplicacao = $linha[ 'demanda_custo_modalidade_aplicacao' ];
          $moeda = $linha[ 'demanda_custo_moeda' ];
          $responsavel = $linha[ 'responsavel_demandacusto' ];
          $quantidadeMeses = null;
        	}

        $custoId = (int) $linha[ 'tr_custo_id' ];
        $valorTotal = (($quantidade*($valorUnitarioAtual > 0 ? $valorUnitarioAtual : $valorUnitario)) * ((100+$bdi)/100));
        if($quantidadeMeses !== null) $valorTotal *= $quantidadeMeses;
        if($valorTotal > 0)$tem_total = true;
        $saida = '<tr>';

        if($trAtual != $linha['tr_id']){
          $trAtual = $linha['tr_id'];
          $saida .= '<td colspan=20><b>'.$linha['tr_nome'].'</b></td></tr><tr>';
        	}
        $saida .= '<td align="left">' . ++$numeroLinha . ' - ' . $nome . '</td>';
        $saida .= '<td align="left">' . ( $descricao ? $descricao : '&nbsp;' ) . '</td>';
        $nd = ( $categoriaEconomica && $grupoDespesa && $modalidadeAplicacao ? $categoriaEconomica . '.' . $grupoDespesa . '.' . $modalidadeAplicacao . '.' : '' ) . $nd;
        $saida .= '<td>' . $nd . '</td>';
        $moedaTexto = array_key_exists( $moeda, $moedas ) ? $moedas[ $moeda ] : '&nbsp;';
        $saida .= '<td style="white-space: nowrap; text-align: right">' . $moedaTexto . ' ' . number_format( $valorUnitario, 2, ',', '.' ) . '</td>';
				$saida .= '<td style="white-space: nowrap; text-align: right">' . ($valorUnitarioAtual > 0 ? $moedaTexto . ' ' . number_format( $valorUnitarioAtual, 2, ',', '.' ) : '') . '</td>';
        $saida .= '<td style="white-space: nowrap; text-align: right">' . number_format( $quantidade, 2, ',', '.' ) . '</td>';
        $saida .= '<td style="white-space: nowrap; text-align: right">' . ( $quantidadeMeses !== null ? $quantidadeMeses : '&nbsp;' ) . '</td>';
        if($config['bdi']) $saida .= '<td style="white-space: nowrap; text-align: right">' . number_format( $bdi, 2, ',', '.' ) . '</td>';
        $saida .= '<td style="white-space: nowrap; text-align: right">' . $moedaTexto . ' ' . number_format( $valorTotal, 2, ',', '.' ) . '</td>';
        $saida .= '<td style="white-space: nowrap; text-align: right">' . $responsavel . '</td>';
				$data = $data ? $data->format('%d/%m/%Y') : '&nbsp;';
        $saida .= '<td align="center">' . $data . '</td>';
        if (!$dialogo) $saida .= '<td width="16" align="left">'. dica('Excluir Item', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$nome.'.').'<a href="javascript:void(0);" onclick="excluir_custo('.$linha['instrumento_custo_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF(). '</td>';
       	$saida .= '</tr>';

        echo $saida;

        if( isset( $custo[ $moeda ][ $nd ] ) ) {
            $custo[ $moeda ][ $nd ] += (float) $valorTotal;
        }
        else {
            $custo[ $moeda ][ $nd ] = (float) $valorTotal;
        }

        if( isset( $total[ $moeda ] ) ) {
            $total[ $moeda ] += $valorTotal;
        }
        else {
            $total[ $moeda ] = $valorTotal;
        }
    }

    $saidaND = '';
    $saidaTotal = '';

    if( $tem_total ) {
        foreach( $custo as $tipo_moeda => $linha ) {
            $saidaND .= '<div style="border-bottom: 1px solid; white-space: nowrap; text-align: right">';
            foreach( $linha as $indice_nd => $somatorio ) {
                if( $somatorio > 0 ) {
                    $saidaND .= '<br>' . ( $indice_nd ? $indice_nd : 'Sem ND' );
                }
            }

            $saidaND .= '<br><b>Total</b></div>';

            $saidaTotal .= '<div style="border-bottom: 1px solid;">';

            foreach( $linha as $indice_nd => $somatorio ) {
                if( $somatorio > 0 ) {
                    $saidaTotal .= '<br>' . $moedas[ $tipo_moeda ] . ' ' . number_format( $somatorio, 2, ',', '.' );
                }
            }

            $saidaTotal .= '<br><b>' . $moedas[ $tipo_moeda ] . ' ' . number_format( $total[ $tipo_moeda ], 2, ',', '.' ) . '</b></div>';
        }
    }

    echo '<tr><td colspan="' . ( $config['bdi'] ? 7 : 6 ) . '">&nbsp;</td><td class="std" style="white-space: nowrap; text-align: right"><div id="item_custo_totais_nd" style="white-space: nowrap; text-align: right"">'
         . $saidaND
         . '</div><td><div id="item_custo_totais" style="white-space: nowrap; text-align: right">'
         . $saidaTotal
         . '</div></td><td colspan="20">&nbsp;</td>';
}
else {
  echo '<tr><td style="text-align: center; font-size: 1.2em; font-weight: bold; padding-bottom: 15px;" colspan="20">Nenhum item encontrado.</td></tr>';
	}

echo '</tbody></table>';

echo '</div></td></tr>';

echo '</table></form>';

if (!$dialogo){
    echo estiloFundoCaixa();
}
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)){
    echo '<script language=Javascript>self.print();</script>';
}
?>

<script type="text/javascript">
var moedas = <?php echo json_encode(toUtf8($moedas));?>;
var configBDI = <?php echo $config['bdi'] ? '1' : '0';?>;

</script>


