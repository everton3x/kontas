<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_GET)) $cod = $_GET['cod'];

$detalhes = buscarDadosDaReceita($cod);

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="receitas-gerenciar.php">Receitas</a>
    <div class="divider"> / </div>
    <a class="section" href="receita-detalhe.php?cod=<?= $cod; ?>">Detalhe</a>
    <div class="divider"> / </div>
    <div class="active section">Receber</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Recebimento de receita
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- formulário -->
<form class="ui form warning">
    <div class="fields">
        <div class="five wide required field">
            <label>Código</label>
            <input type="text" name="cod" required value="<?= $cod; ?>" readonly>
        </div>
    </div>
    <div class="fields">
        <div class="three wide required field">
            <label>Valor</label>
            <input type="number" name="valor" required min="0.01" step="0.01" autofocus>
        </div>
        <div class="three wide required field">
            <label>Data</label>
            <input type="date" name="data" required value="<?= date('Y-m-d'); ?>">
        </div>
    </div>
    <div class="ui warning message">
        <p>O valor máximo é <?= formatNumber($detalhes['areceber']); ?></p>
        <p>Caso o valor do recebimento supere o valor máximo, a previsão será acrescida da diferença.</p>
    </div>
    <div class="field">
        <label>Observação</label>
        <input type="text" name="observacao" placeholder="Insira uma observação, se necessário" autocomplete="off">
    </div>

    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" onclick="history.back()"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="receita-receber-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- formulário -->
<div class="ui divider"></div>
<!-- tabela -->
<table class="ui table">
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


<?php carregaTemplate('footer'); ?>