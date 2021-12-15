<?php
// print_r($_POST);exit();
require_once '../vendor/autoload.php';
carregaTemplate('header');

// prepara dados da transação
$idTransacao = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);
$dataTransacao = filter_input(INPUT_POST, 'data', FILTER_DEFAULT);
$historicoTransacao = filter_input(INPUT_POST, 'historico', FILTER_DEFAULT);
if ($idTransacao === null) $idTransacao = gerarCodigoDeTransacao();
if ($dataTransacao === null) $dataTransacao = date('Y-m-d');
if ($historicoTransacao === null) $historicoTransacao = '';

// calcula o id do lançamento
if (array_key_exists('lancamentos', $_POST)) {
    $lancamentos = $_POST['lancamentos'];
    $proximoLancamento = sizeof($lancamentos) + 1;
} else {
    $proximoLancamento = 1;
    $lancamentos = [];
}

//monta os lançamentos existentes
$htmlDosLancamentos = '';
$debitoTotal = 0.0;
$creditoTotal = 0.0;
if (sizeof($lancamentos) > 0) {
    foreach ($lancamentos as $idLancamento => $item) {
        if($item['contaContabil'] == '') continue;
        if(key_exists('excluirLancamento', $_POST) && $_POST['excluirLancamento'] == $idLancamento) continue;
        $dadosContaContabil = buscarDadosDaContaContabil($item['contaContabil']);
        switch ($item['movimento']) {
            case 'debito':
                $movimento = 'Débito';
                $debitoTotal += $item['valor'];
                break;
            case 'credito':
                $movimento = 'Crédito';
                $creditoTotal += $item['valor'];
                break;
        }
        $htmlDosLancamentos .= '<tr><td>'. formatarCodigoContaContabil($dadosContaContabil['codigo']). ' '. $dadosContaContabil['nome']. '</td><td>'. $movimento. '</td><td class="right aligned">'. formatarMoeda($item['valor']). '</td><td>'. "<button class='ui right floated small negative icon button' type='submit' value='$idLancamento' name='excluirLancamento' form='transacao-manual' formaction='lancar-transacao-manual.php#lancamentos' formmethod='POST'><i class='trash icon'></i></button>". '</td></tr>'. PHP_EOL;
        $htmlDosLancamentos .= "<input type='hidden' name='lancamentos[$idLancamento][contaContabil]' value='{$item['contaContabil']}'>" . PHP_EOL;
        $htmlDosLancamentos .= "<input type='hidden' name='lancamentos[$idLancamento][movimento]' value='{$item['movimento']}'>" . PHP_EOL;
        $htmlDosLancamentos .= "<input type='hidden' name='lancamentos[$idLancamento][valor]' value='{$item['valor']}'>" . PHP_EOL;
        $idLancamento++;
    }
}
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="painel-contabil.php">Contábil</a>
    <div class="divider"> / </div>
    <div class="active section">Transação manual</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Transação manual
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2><!-- título -->

<!-- formulário -->
<form class="ui form" id="transacao-manual">
    <!-- formulário da transação -->
    <h4 class="ui dividing header">Transação</h4>
    <div class="fields">
        <div class="five wide field">
            <label>Código</label>
            <input type="text" name="id" readonly required value="<?= $idTransacao; ?>">
        </div>
        <div class="three wide field">
            <label>Data</label>
            <input type="date" name="data" autofocus required value="<?= $dataTransacao; ?>">
        </div>
    </div>
    <div class="field">
        <label>Histórico</label>
        <textarea name="historico" required><?= $historicoTransacao; ?></textarea>
    </div><!-- formulário da transação -->
    <!-- formulário dos lançamentos -->
    <h4 class="ui dividing header">Lançamentos</h4>
    <table class="ui table">
        <thead>
            <tr>
                <th>Conta Contábil</th>
                <th>Movimento a</th>
                <th>Valor</th>
                <th>Operações</th>
            </tr>
        </thead>
        <tbody>
            <a id="lancamentos"></a>
            <!-- novo lançamento -->
            <tr>
                <td>
                    <div class="field">
                        <select name="lancamentos[<?= $proximoLancamento; ?>][contaContabil]">
                            <option value="">Selecione a conta contábil</option>
                            <?= montaOptionsDasContasContabeis(); ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="field">
                        <select name="lancamentos[<?= $proximoLancamento; ?>][movimento]">
                            <option value="">Selecione o movimento</option>
                            <option value="debito">Débito</option>
                            <option value="credito">Crédito</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="field">
                        <input type="number" min="0.01" name="lancamentos[<?= $proximoLancamento; ?>][valor]" step="0.01">
                    </div>
                </td>
                <td>
                    <button class="ui right floated small primary icon button" type="submit" form="transacao-manual" formaction="lancar-transacao-manual.php#lancamentos" formmethod="POST">
                        <i class="save icon"></i>
                    </button>
                </td>
            </tr><!-- novo lançamento -->
            <!-- lançamentos registrados -->
            <?= $htmlDosLancamentos; ?>
            <!-- lançamentos registrados -->
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th class="right aligned">Débitos</th>
                <th class="right aligned">Créditos</th>
                <th class="right aligned">Diferença</th>
            </tr>
            <tr>
                <td></td>
                <td class="right aligned"><?=formatarMoeda($debitoTotal);?></td>
                <td class="right aligned"><?=formatarMoeda($creditoTotal);?></td>
                <td class="right aligned"><?=formatarMoeda($debitoTotal - $creditoTotal);?></td>
            </tr>
        </tfoot>
    </table><!-- formulário dos lançamentos -->
    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" form="transacao-manual" formaction="lancar-transacao-manual.php" formmethod="GET"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" form="transacao-manual" formaction="adicionar-transacao-manual.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- formulário -->

<?php carregaTemplate('footer'); ?>