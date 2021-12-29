<?php

require_once '../vendor/autoload.php';

carregaTemplate('header');

$cod = '';
if (key_exists('cod', $_GET)) $cod = $_GET['cod'];

$detalhes = buscarDadosDaReceita($cod);
// print_r($detalhes);
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <div class="section">Receitas</div>
    <div class="divider"> / </div>
    <div class="active section">Detalhes</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="search plus icon"></i>
    <div class="content">
        Detalhes da receita
        <!--<div class="sub header">Operações envolvendo receitas.</div>-->
    </div>
</h2><!-- título -->

<!-- tabela -->
<table class="ui table">
    <thead>
        <tr>
            <th colspan="2" class="right aligned">
                <div class="ui icon buttons">
                    <a class="ui primary button" href="#alteracoes"><i class="random icon"></i></a>
                </div>
                <div class="ui icon buttons">
                    <a class="ui green button" href="#recebimentos"><i class="download icon"></i></a>
                </div>
                <div class="ui icon buttons">
                    <a class="ui red basic button" href=""><i class="edit icon"></i></a>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="active">Código</td>
            <td><?= $cod; ?></td>
        </tr>
        <tr>
            <td class="active">Período</td>
            <td><?= int2DateTime($detalhes['periodo'])->format('F/Y'); ?></td>
        </tr>
        <tr>
            <td class="active">Descrição</td>
            <td><?= $detalhes['descricao']; ?></td>
        </tr>
        <tr>
            <td class="active">Agrupador</td>
            <td><?= $detalhes['agrupador']; ?></td>
        </tr>
        <tr>
            <td class="active">Parcela</td>
            <td><?= $detalhes['parcela']; ?> de <?= $detalhes['parcelas']; ?></td>
        </tr>
        <tr>
            <td class="active">Tags</td>
            <td>
                <?php foreach ($detalhes['tags'] as $tag) : ?>
                    <a class="ui label">
                        <i class="hashtag icon"></i>
                        <?= $tag; ?>
                    </a>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td class="active">Previsão inicial</td>
            <td><?= formatNumber($detalhes['valorInicial']); ?></td>
        </tr>
        <tr>
            <td class="active">Atualizações</td>
            <td><?= formatNumber($detalhes['alteracao']); ?></td>
        </tr>
        <tr>
            <td class="active">Previsão atualizada</td>
            <td><?= formatNumber($detalhes['previsto']); ?></td>
        </tr>
        <tr>
            <td class="active">Valor recebido</td>
            <td><?= formatNumber($detalhes['recebido']); ?></td>
        </tr>
        <tr>
            <td class="active">Saldo a receber</td>
            <td><?= formatNumber($detalhes['areceber']); ?></td>
        </tr>
    </tbody>
</table>
<!-- tabela -->

<a id="alteracoes"></a>
<!-- alterações -->
<h3 class="ui header">
    <i class="random icon"></i>
    <div class="content">
        Alterações
    </div>
</h3>
<table class="ui table">
    <thead>
        <tr>
            <th>Data</th>
            <th class="right aligned">Valor</th>
            <th>Observação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalhes['alteracoes'] as $item) : ?>
            <tr>
                <td><?= $item['registro']; ?></td>
                <td class="right aligned"><?= formatNumber($item['valor']); ?></td>
                <td><?= $item['observacao']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="right aligned">Total</th>
            <th class="right aligned"><?= formatNumber($detalhes['alteracao']); ?></th>
            <th class="right aligned">
                <a class="ui primary icon button" href="receita-alterar-valor.php?cod=<?= $cod; ?>">
                    <i class="random icon"></i>
                </a>
            </th>
        </tr>
    </tfoot>
</table>

<!-- alterações -->

<a id="recebimentos"></a>
<!-- recebimentos -->
<!-- recebimentos -->

<?php carregaTemplate('footer'); ?>