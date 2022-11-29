/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/

var itensCusto = {},
    custoND = {},
    custoTotal = {},
    itemCustoRow = 1;

function moeda2float(moeda){
    moeda = moeda.replace(".","");
    moeda = moeda.replace(".","");
    moeda = moeda.replace(".","");
    moeda = moeda.replace(".","");
    moeda = moeda.replace(".","");
    moeda = moeda.replace(".","");
    moeda = moeda.replace(".","");
    moeda = moeda.replace(",",".");
    if (moeda=="") moeda='0';
    return parseFloat(moeda);
}

function float2moeda(num){
    var x = 0,
        cents, ret;

    if( num < 0 ) {
        num = Math.abs( num );
        x   = 1;
    }

    if( isNaN( num ) ) num = "0";

    cents = Math.floor( (num * 100 + 0.5) % 100 );
    num = Math.floor( (num * 100 + 0.5) / 100 ).toString();

    if( cents < 10 ) cents = "0" + cents;

    for( var i = 0; i < Math.floor( (num.length - (1 + i ) ) / 3 ); i++ ) {
        num = num.substring( 0, num.length - (4 * i + 3 ) ) + '.' + num.substring( num.length - (4 * i + 3 ) );
    }

    ret = num + ',' + cents;

    if( x == 1 ) ret = ' - ' + ret;

    return ret;
}

function checar_quantidade(custoId){
    var item = window.itensCusto[custoId];

    if(item){
        var quantidade = moeda2float($jq('#item_custo_quantidade'+custoId).val());

        if(quantidade > item['quantidadeMaxima']){
            quantidade = item['quantidadeMaxima'];
            alert('A quantidade máxima permitida é de ' + float2moeda(quantidade) + ' para o ítem ' + item['nome']);
            $jq('#item_custo_quantidade'+custoId).val(float2moeda(quantidade));
        }

        var moeda = item['moeda'],
            moedaTexto = moedas[moeda] ? moedas[moeda] : '&nbsp;',
            valorUnitario = item['valorUnitario'],
            meses = item['meses'],
            valorTotal;

        valorTotal = ( (quantidade * valorUnitario) * (( 100+item['bdi'])/100));

        if(meses !== null){
            valorTotal *= meses;
        }

        $jq('#item_custo_valor_total'+custoId).html(moedaTexto + ' ' + float2moeda(valorTotal));

        item['total'] = valorTotal;
        item['quantidade'] = quantidade;

        atualizarTotaisItensCusto();
    }
}

function importar() {
    var itens = [],
        item;

    $jq('input.item_custo_chekbox').each(function(ind, fld){
        if(fld.checked){
            item = window.itensCusto[fld.value];
            if(item){
                itens.push({
                    id : item['custoId'],
                    tipo: item['tipo'],
                    quantidade: item['quantidade']
                })
            }
        }
    });

    setTimeout(function(){
        xajax_importar(document.getElementById('instrumento_id').value, itens);
        __buildTooltip();

        $jq('#itens_custo').hide();
        $jq('#importar').hide();

        $jq('#lista_itens_custo').html('');

        document.getElementById('sel_todas').checked = false;

        window.custoND    = {};
        window.custoTotal = {};
        window.itensCusto = {};
        window.itemCustoRow = 1;
    }, 10);
}

function marca_sel_todas() {
    var me = document.getElementById('sel_todas');

    $jq('input.item_custo_chekbox').each(function(ind, elm){
        elm.checked = me.checked
    });
}

function popSelecionarTR() {
    if( window.parent.gpwebApp ) {
        parent.gpwebApp.popUp(
            '<?php echo ucfirst( $config[ "tr" ] )?>',
            1000,
            700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_tr&tabela=tr&cia_id='
                 + document.getElementById( 'cia_id' ).value,
            window.set_tr,
            window
        );
    }
    else {
        window.open( './index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_tr&tabela=tr&cia_id='
                     + document.getElementById( 'cia_id' ).value, '<?php echo ucfirst( $config[ "tr" ] )?>',
            'left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes' );
    }
}

function set_tr(chave, valor){
    $jq('#nome_custo_display').html(valor);

    var itens = xajax_exibir_custos(chave),
        temTotal = false;

    $jq('#lista_itens_custo').html('');

    document.getElementById('sel_todas').checked = false;

    window.custoND    = {};
    window.custoTotal = {};
    window.itensCusto = {};
    window.itemCustoRow = 1;

    if(!itens || !itens.length){
        $jq('#lista_itens_custo').html('<tr><td style="text-align: center; font-size: 1.2em; font-weight: bold; padding-bottom: 15px;" colspan="20">Nenhum item encontrado.</td></tr>')
        $jq('#importar').hide();
    }
    else{
        itens.forEach(adicionarItemCusto);

        $jq('#lista_itens_custo').append('<tr><td colspan="' + ( window.configBDI ? 8 : 7 ) + '"></td><td class="std" align="right"><div id="item_custo_totais_nd" style="text-align: right;"></div><td><div id="item_custo_totais" style="text-align: right;"></div></td><td colspan="20">&nbsp;</td>');

        atualizarTotaisItensCusto();

        $jq('#importar').show();
    }

    $jq('#itens_custo').show();



    __buildTooltip();
}

function adicionarItemCusto(dados, index){
    var custoId             = dados[ 'custoId' ],
        moeda = dados['moeda'],
        moedaTexto = moedas[moeda] ? moedas[moeda] : '&nbsp;',
        quantidade = dados['quantidade'],
        valorUnitario = dados['valorUnitario'],
        valorUnitarioAtual = dados['valorUnitarioAtual'],
        meses = dados['meses'],
        responsavel  = dados[ 'responsavel' ],
        categoriaEconomica  = dados[ 'categoriaEconomica' ],
        grupoDespesa        = dados[ 'grupoDespesa' ],
        modalidadeAplicacao = dados[ 'modalidadeAplicacao' ],
        nd                  = (categoriaEconomica && grupoDespesa && modalidadeAplicacao ? categoriaEconomica + '.' + grupoDespesa + '.' + modalidadeAplicacao + '.' : '') + dados[ 'nd' ],
        valorTotal;

    valorTotal = ( (quantidade * valorUnitario) * (( 100+dados['bdi'])/100));

    if(meses !== null){
        valorTotal *= meses;
    }

    var html = '<tr id="item_custo' + custoId + '">'
               + '<td><input type="checkbox" class="item_custo_chekbox" value="' + custoId + '"></td>'
               + '<td>' + window.itemCustoRow + ' - '+dados['nome'] + '</td>'
               + '<td>' + (dados['descricao'] ? dados['descricao'] : '&nbsp;') + '</td>'
               + '<td>' + (nd ? nd : '&nbsp;') + '</td>'
               + '<td style="white-space: nowrap; text-align: right">' + moedaTexto + ' ' + float2moeda(valorUnitario) + '</td>'
               + '<td style="white-space: nowrap; text-align: right">' + ( !valorUnitarioAtual ? moedaTexto + ' ' + float2moeda(valorUnitarioAtual) : '') + '</td>'
               + '<td style="white-space: nowrap; text-align: right"><input type="text" id="item_custo_quantidade' + custoId + '" onkeypress="return entradaNumerica(event, this, true, true);" value="' + float2moeda( quantidade ) + '" onchange="checar_quantidade(' + custoId  +')" class="texto" style="width:90px;" /></td>'
               + '<td style="white-space: nowrap; text-align: right">' + (meses !== null ? meses : '&nbsp;') + '</td>'
               + (window.configBDI ? '<td style="white-space: nowrap; text-align: right">' + float2moeda(dados['bdi']) + '</td>' : '')
               + '<td id="item_custo_valor_total'+custoId+'" style="white-space: nowrap; text-align: right">' + moedaTexto + ' ' + float2moeda(valorTotal) + '</td>'
               + '<td style="white-space: nowrap; text-align: right">' + (responsavel ? responsavel : '&nbsp;') + '</td>'
               + '<td align="center">' + (dados['data'] ? dados['data'] : '&nbsp;') + '</td>'

               + '</tr>';

    $jq('#lista_itens_custo').append(html);

    ++window.itemCustoRow;

    dados['total'] = valorTotal;
    dados['quantidadeMaxima'] = quantidade;
    window.itensCusto[custoId] = dados;
}

function atualizarTotaisItensCusto(){
    var temTotal = false;

    window.custoND = {};
    window.custoTotal = {};

    $jq.each( window.itensCusto, function( custoId, dados ) {
        var moeda = dados['moeda'],
            categoriaEconomica  = dados[ 'categoriaEconomica' ],
            grupoDespesa        = dados[ 'grupoDespesa' ],
            modalidadeAplicacao = dados[ 'modalidadeAplicacao' ],
            nd                  = (categoriaEconomica && grupoDespesa && modalidadeAplicacao ? categoriaEconomica + '.' + grupoDespesa + '.' + modalidadeAplicacao + '.' : '') + dados[ 'nd' ],
            valorTotal = dados['total'];

        if(!window.custoND[moeda]){
            window.custoND[moeda] = {};
        }

        nd = nd || 0;

        if(!window.custoND[moeda][nd]){
            window.custoND[moeda][nd] = valorTotal;
        }
        else{
            window.custoND[moeda][nd] += valorTotal;
        }

        if( !window.custoTotal[ moeda ] ) {
            window.custoTotal[ moeda ] = valorTotal;
        }
        else {
            window.custoTotal[ moeda ] += valorTotal;
        }

        if(valorTotal > 0){
            temTotal = true;
        }
    });

    var saidaND = '',
        saidaTotal = '';

    if(temTotal) {
        $jq.each( window.custoND, function( tipoMoeda, dados ) {
            saidaND += '<div style="border-bottom: 1px solid; white-space: nowrap; text-align: right">';
            $jq.each( dados, function( index, valor ) {
                if( valor > 0 ) {
                    saidaND += '<br/>' + (parseInt( index ) ? index : 'Sem ND' );
                }
            } );

            saidaND += '<br/><b>Total</b></div>';

            var moedaTexto = window.moedas[ tipoMoeda ] || '&nbsp;';

            saidaTotal += '<div style="border-bottom: 1px solid; white-space: nowrap; text-align: right">';
            $jq.each( dados, function( index, valor ) {
                if( valor > 0 ) {
                    saidaTotal += '<br/>' + moedaTexto + ' ' + float2moeda( valor );
                }
            } );

            saidaTotal += '<br/><b>'
                    + moedaTexto
                    + ' '
                    + float2moeda( window.custoTotal[ tipoMoeda ] || 0 )
                    + '</b></div>';

        } );
    }

    $jq('#item_custo_totais_nd').html(saidaND);
    $jq('#item_custo_totais').html(saidaTotal);
}

function excluir_custo(instrumento_custo_id){
    if (confirm('Tem certeza que deseja excluir?')) {
        setTimeout(function(){
            xajax_excluir_custo(instrumento_custo_id, document.getElementById('instrumento_id').value);
            __buildTooltip();
        }, 10);
    }
}

function entradaNumerica(event, campo, virgula, menos) {
    var unicode = event.charCode,
        unicode1 = event.keyCode;

    if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
        campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
    }

    if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
        campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
    }
    if(menos && campo.value.lastIndexOf("-") > 0){
        campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
    }
    if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
        if (unicode1 != 8) {
            if ((unicode >= 48 && unicode <= 57) || unicode1 == 39 || unicode1 == 9 || unicode1 == 46) return true;
            else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
            return false;
        }
    }
    if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
        if (unicode1 != 8) {
            if (unicode1 >= 48 && unicode1 <= 57) return true;
            else {
                if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
                return false;
            }
        }
    }
}
