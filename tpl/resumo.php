<?php carregaTemplate('header');?>
        <!-- Seletor de períodos -->
        <div class="ui center aligned basic segment">
            <div class="ui buttons">
                <a class="ui grey basic button" href="index.php?periodo=<?=$mesAnterior->format('Ym');?>"><?=$mesAnterior->format('F/Y');?></a>
                <a class="ui grey button"><?=$mesAtual->format('F/Y');?></a>
                <a class="ui grey basic button" href="index.php?periodo=<?=$mesPosterior->format('Ym');?>"><?=$mesPosterior->format('F/Y');?></a>
            </div>
        </div>
        <!-- Fim do Seletor de períodos -->
        <div class="ui section divider"></div>
        <!-- Receitas -->
        <div class="ui section">
            <h3 class="ui dividing header">
                <i class="plus square icon"></i>
                <div class="content">
                    Receitas
                </div>
            </h3>
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th class="right aligned">Previsto</th>
                        <th class="right aligned">Recebido</th>
                        <th class="right aligned">A Receber</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Salário</td>
                        <td class="right aligned">7.000,00</td>
                        <td class="right aligned">7.000,00</td>
                        <td class="right aligned">0,00</td>
                    </tr>
                    <tr>
                        <td>Mercado da Marina</td>
                        <td class="right aligned">500,00</td>
                        <td class="right aligned">0,00</td>
                        <td class="right aligned">500,00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="right aligned">7.500,00</th>
                        <th class="right aligned">7.000,00</th>
                        <th class="right aligned">500,00</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Fim das Receitas -->
        <div class="ui section divider"></div>
        <!-- Despesas -->
        <div class="ui section">
            <h3 class="ui dividing header">
                <i class="minus square icon"></i>
                <div class="content">
                    Despesas
                </div>
            </h3>
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th class="right aligned">Previsto</th>
                        <th class="right aligned">Gasto</th>
                        <th class="right aligned">A Gastar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mercado</td>
                        <td class="right aligned">1.000,00</td>
                        <td class="right aligned">1.100,00</td>
                        <td class="right aligned error">
                            <i class="attention icon"></i>
                            (100,00)
                        </td>
                    </tr>
                    <tr>
                        <td>Mercado da Marina</td>
                        <td class="right aligned">500,00</td>
                        <td class="right aligned">500,00</td>
                        <td class="right aligned">0,00</td>
                    </tr>
                    <tr>
                        <td>Luz</td>
                        <td class="right aligned">100,00</td>
                        <td class="right aligned">0,00</td>
                        <td class="right aligned">100,00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="right aligned">1.600,00</th>
                        <th class="right aligned">1.600,00</th>
                        <th class="right aligned">0,00</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Fim das despesas -->
        <div class="ui section divider"></div>
        <!-- resultados -->
        <div class="ui section">
            <h3 class="ui dividing header">
                <i class="calculator icon"></i>
                <div class="content">
                    Resultados
                </div>
            </h3>
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>Resultado</th>
                        <th class="right aligned">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>do Período Atual</td>
                        <td class="right aligned">5.900,00</td>
                    </tr>
                    <tr>
                        <td>até o Período Anterior</td>
                        <td class="right aligned error">
                            <i class="attention icon"></i>
                            (6.000,00)
                        </td>
                    </tr>
                    <tr>
                        <td>Resultado Acumulado</td>
                        <td class="right aligned error">
                            <i class="attention icon"></i>
                            (100,00)
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- fim dos resultados -->
        <?php carregaTemplate('footer');?>