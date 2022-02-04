<?php

use Adianti\Widget\Base\TElement;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Template\THtmlRenderer;

class ContasDashboard extends TPage
{
    function __construct()
    {
        parent::__construct();
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';

        $div = new TElement('div');
        $div->class = "row";

        try
        {
            TTransaction::open('db_condominio');

            $valor = ContaPagar::where('valor', '>', 0)->sumBy('valor');
            $valor_pago = ContaPagar::where('valor_pago', '>', 0)->sumBy('valor_pago');
            $juros = ContaPagar::where('saldo', '>', 0)->sumBy('saldo');

            $valor_receber = ContaReceber::where('valor', '>', 0)->sumBy('valor');
            $valor_recebido = ContaReceber::where('valor_recebido', '>', 0)->sumBy('valor_recebido');
            $juros_recebido = ContaReceber::where('juros_recebido', '>', 0)->sumby('juros_recebido');

            TTransaction::close();

            $indicador1 = new THtmlRenderer('app/resources/info-box.html');
            $indicador2 = new THtmlRenderer('app/resources/info-box.html');
            $indicador3 = new THtmlRenderer('app/resources/info-box.html');
            $indicador4 = new THtmlRenderer('app/resources/info-box.html');
            $indicador5 = new THtmlRenderer('app/resources/info-box.html');
            $indicador6 = new THtmlRenderer('app/resources/info-box.html');

            $indicador1->enabledSection('main', ['title' => 'VALOR A PAGAR', 'icon' => 'money-bill', 'background' => 'blue',
                                                 'value' => 'R$'.number_format($valor, 2, ',', '.') ]);
            
            $indicador2->enabledSection('main', ['title' => 'VALOR PAGO', 'icon' => 'money-bill', 'background' => 'green',
                                                 'value' => 'R$'.number_format($valor_pago, 2, ',', '.') ]); 
                                                 
            $indicador3->enabledSection('main', ['title' => 'JUROS', 'icon' => 'money-bill', 'background' => 'blue',
                                                 'value' => 'R$'.number_format($juros, 2, ',', '.') ]);
            
            $indicador4->enabledSection('main', ['title' => 'VALOR A RECEBER', 'icon' => 'money-bill', 'background' => 'blue',
                                                 'value' => 'R$'.number_format($valor_receber, 2, ',', '.') ]); 
                                                 
            $indicador5->enabledSection('main', ['title' => 'VALOR RECEBIDO', 'icon' => 'money-bill', 'background' => 'blue',
                                                 'value' => 'R$'.number_format($valor_recebido, 2, ',', '.') ]);
                                                 
            $indicador6->enabledSection('main', ['title' => 'JUROS RECEBIDO', 'icon' => 'money-bill', 'background' => 'blue',
                                                 'value' => 'R$'.number_format($juros_recebido, 2, ',', '.') ]);                                     
            
            $div->add(TElement::tag('div', $indicador1, ['class' => 'col-sm-6']) );
            $div->add(TElement::tag('div', $indicador2, ['class' => 'col-sm-6']) );

            $table1 = TTable::create(['class' => 'table table->striped table-hover', 'style' => 'border-collapse:collapse'] );
            $table1->addSection('thead');
            $table1->addRowSet('', 'Valor', 'Valor Pago', 'Juros');

            if($Valor)
            {
                $Table->addSection('tbody');

                $table1->addSection('tfoot')->style = 'color:blue';
                $row = $table->adRow();
                $row->addCell('Total');
                $row->addCell('R$ '.number_format($valor, 2,',','.'))->style = 'text-align: left';
                $row->addCell('R$ '.number_format($valor_pago, 2,',','.'))->style = 'text-align: left';
                $row->addCell('R$ '.number_format($juros, 2,',','.'))->style = 'text-align: left';
            }
            $div->add(TElement::tag('div', TPanelGroup::pack('Valor Total a Pagar', $table1), ['class' => 'col-sm-6']));
        }
        catch (Exception $e)
        {
           new TMessage('error', $e->getMessage());
        }

        parent::add($vbox);
    }

}