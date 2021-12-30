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
    <a class="section" href="receitas-gerenciar.php">Receitas</a>
    <div class="divider"> / </div>
    <a class="section" href="receita-detalhe.php?cod=<?=$cod;?>">Previsão</a>
    <div class="divider"> / </div>
    <div class="active section">Alteração</div>
</div><!-- trilha -->

<!-- título -->
<h2 class="ui header">
    <i class="edit icon"></i>
    <div class="content">
        Alteração da previsão da receita
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
            <label>Valor</label>
            <input type="number" name="valor" required step="0.01" autofocus>
        </div>
    </div>
    <div class="field">
        <label>Observação</label>
        <input type="text" name="observacao" placeholder="Insira uma observação, se necessário" autocomplete="off">
    </div>

    <!-- botões do formulário -->
    <div class="ui divider"></div>
    <button class="ui left floated negative button" onclick="history.back()"><i class="cancel icon"></i>Cancelar</button>
    <button class="ui right floated positive button" type="submit" formaction="receita-alterar-valor-salvar.php" formmethod="POST"><i class="save icon"></i>Salvar</button>
    <!-- botões do formulário -->
</form><!-- formulário -->


<?php carregaTemplate('footer'); ?>