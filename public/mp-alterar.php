<?php
// print_r($_POST);
require_once '../vendor/autoload.php';
carregaTemplate('header');

$cod = 0;
if (key_exists('cod', $_GET)) $cod = $_GET['cod'];
$detalhes = detalhesMeiosPagamento($cod);
// print_r($detalhes);
?>
<!-- trilha -->
<div class="ui breadcrumb">
    <a class="section" href="index.php">Início</a>
    <div class="divider"> / </div>
    <a class="section" href="despesas-gerenciar.php">Despesas</a>
    <div class="divider"> / </div>
    <a class="section" href="mp-gerenciar.php">Meios de pagamento</a>
    <div class="divider"> / </div>
    <div class="active section">Alteração</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Alteração de meio de pagamento
        <!-- <div class="sub header">Operações contábeis.</div> -->
    </div>
</h2>
<div class="ui divider"></div><!-- título -->

<!-- formulário -->
<form class="ui form">
    <div class="fields">
        <div class="five wide required field">
            <label>Código</label>
            <input type="text" name="cod" required value="<?= $detalhes['cod']; ?>" readonly>
        </div>
    </div>
    <div class="required field">
        <label>Meio de pagamento</label>
        <input type="text" name="mp" required autofocus autocomplete="off" value="<?= $detalhes['mp']; ?>">
    </div>
    <div class="inline field">
        <div class="ui toggle checkbox autopagar">
            <input type="checkbox" name="autopagar" class="hidden">
            <label>Autopagar</label>
        </div>
        <input type="hidden" id="autopagar" value="<?= $detalhes['autopagar']; ?>">
    </div>
    <div class="inline field">
        <div class="ui toggle checkbox status">
            <input type="checkbox" name="status" class="hidden">
            <label>Ativo</label>
        </div>
        <input type="hidden" id="status" value="<?= $detalhes['status']; ?>">
    </div>


    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <a class="ui left floated negative button" href="mp-gerenciar.php"><i class="cancel icon"></i>Cancelar</a>
    <button class="ui right floated positive button" type="submit" formaction="mp-alterar-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- formulário -->
<script>
    if (document.getElementById("autopagar").value == 1) {
        $('.ui.toggle.checkbox.autopagar').checkbox('set checked', true);
    } else {
        $('.ui.toggle.checkbox.autopagar').checkbox('set unchecked', true);
    }
    if (document.getElementById("status").value == 0) {
        $('.ui.toggle.checkbox.status').checkbox('set checked', true);
    } else {
        $('.ui.toggle.checkbox.status').checkbox('set unchecked', true);
    }
    $('.ui.checkbox')
        .checkbox();
</script>

<?php carregaTemplate('footer'); ?>