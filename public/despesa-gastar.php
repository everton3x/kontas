<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_GET)) $cod = $_GET['cod'];

?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-gerenciar.php">Despesas</a>
    <div class="divider"> / </div>
    <a class="active section" href="despesa-detalhe.php?cod=<?=$cod;?>">Gastar</a>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Gastar
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- formulário -->
<form class="ui form">
    <div class="fields">
        <div class="five wide required field">
            <label>Código</label>
            <input type="text" name="cod" required value="<?= $cod; ?>" readonly>
        </div>
    </div>
    <div class="fields">
        <div class="three wide required field">
            <label>Gasto em</label>
            <input type="date" name="data" required autofocus value="<?=date('Y-m-d');?>">
        </div>
        <div class="three wide required field">
            <label>Valor</label>
            <input type="number" name="valor" required step="0.01" min="0.01">
        </div>
        <div class="three wide field">
            <label>Vencimento</label>
            <input type="date" name="vencimento">
        </div>
    </div>
    <div class="field">
        <label>Observação</label>
        <input type="text" name="observacao" placeholder="Insira uma observação, se necessário" autocomplete="off">
    </div>
    <div class="fields">
        <div class="six wide required field">
            <label>Meio de pagamento</label>
            <div class="ui selection dropdown">
                <input type="hidden" name="mp">
                <i class="dropdown icon"></i>
                <div class="default text">Meio de pagamento</div>
                <div class="menu">
                    <!-- <div class="item" data-value="1">Male</div>
                    <div class="item" data-value="0">Female</div>-->
                    <?php foreach (listarMeiosPagamento(0) as $item) : ?>
                        <div class="item" data-value="<?= $item['cod']; ?>"><?= $item['mp']; ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="ui toggle checkbox">
            <input type="checkbox" class="hidden" name="autopagar">
            <label>Autopagar</label>
        </div>
    </div>

    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" onclick="history.back()"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="despesa-gastar-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- formulário -->

<script>

    $('.ui.selection.dropdown')
        .dropdown();
    $('.ui.checkbox')
        .checkbox();
</script>

<?php carregaTemplate('footer'); ?>